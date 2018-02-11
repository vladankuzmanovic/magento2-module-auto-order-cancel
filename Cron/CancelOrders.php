<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Kuzman
 * @package    Kuzman_AutoOrderCancel
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Vladan Kuzmanovic (vladan.kuzman@gmail.com)
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

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory
     * @param \Kuzman\AutoOrderCancel\Helper\Data $helper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $collectionFactory,
        \Kuzman\AutoOrderCancel\Helper\Data $helper,
        \Psr\Log\LoggerInterface $logger    
    )
    {
        $this->orderCollectionFactory = $collectionFactory;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * Order Cancel
     *
     * @return void
     */
    public function execute()
    {
        $isEnabled = $this->helper->isEnabled();
        if($isEnabled){
            $statuses = $this->helper->getOrderStatuses();
            $methods = $this->helper->getPaymentMethods();
            $olderThan = $this->helper->getOlderThan();
            $recentThan = $this->helper->getRecentThan();
            $comment = $this->helper->getComment();

            $orders = $this->orderCollectionFactory->create();
            $orders->getSelect()->join(
                ['payment' => "sales_order_payment"],
                'main_table.entity_id = payment.parent_id',
                ['method']
            );

            $orders->addFieldToFilter('status', ['in' => $statuses]);
            $orders->addFieldToFilter('method', ['in' => $methods]);
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