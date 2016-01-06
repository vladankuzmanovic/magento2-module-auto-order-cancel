<?php
/**
 * @author	    Vladan Kuzmanovic (vladankuzmanovic@gmail.com)
 */
namespace Kuzman\AutoOrderCancel\Cron;

class CancelOrders
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var \Kuzman\AutoOrderCancel\Helper\Data
     */
    protected $helper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Kuzman\AutoOrderCancel\Helper\Data $helper,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
    )
    {
        $this->helper = $helper;
        $this->logger = $logger;
        $this->orderCollectionFactory = $collectionFactory;
    }

    /**
     * Order Cancel
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute()
    {
        $isEnabled = $this->helper->isEnabled();
        if($isEnabled){
            $statuses = $this->helper->getOrderStatuses();
            $olderThan = $this->helper->getOlderThan();
            $recentThan = $this->helper->getRecentThan();
            $comment = $this->helper->getComment();

            $orders = $this->orderCollectionFactory->create();
            $orders->addFieldToFilter('status', ['in' => $statuses]);
            $orders->getSelect()->where(
                new \Zend_Db_Expr('TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, `updated_at`)) >= ' . $olderThan * 60)
            );
            $orders->getSelect()->where(
                new \Zend_Db_Expr('TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, `updated_at`)) <= ' . $recentThan * 60)
            );
            $orders->getSelect()->limit(10);
            $orders->setOrder('entity_id', 'DESC');

            foreach ($orders->getItems() as $order) {
                if (!$order->canCancel()) {
                    continue;
                }
                try {
                    $order->cancel();
                    $order->addStatusHistoryComment($comment)
                        ->setIsCustomerNotified(false);
                    $order->save();
                } catch(\Exception $e){
                    $this->logger->critical($e);
                }
            }
        }

    }
}
