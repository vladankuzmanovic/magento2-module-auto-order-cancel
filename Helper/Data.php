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
namespace Kuzman\AutoOrderCancel\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**#@+
     * Minimum advertise price constants
     */
    const XML_PATH_AUTOORDERCANCEL_ENABLED = 'sales/autoordercancel/enabled';
    const XML_PATH_AUTOORDERCANCEL_ORDER_STATUSES = 'sales/autoordercancel/order_statuses';
    const XML_PATH_CANCEL_ORDERS_OLDER_THAN = 'sales/autoordercancel/older_than';
    const XML_PATH_CANCEL_ORDERS_RECENT_THAN = 'sales/autoordercancel/recent_than';
    const XML_PATH_CANCEL_ORDERS_COMMENT = 'sales/autoordercancel/comment';


    //const XML_PATH_MSRP_EXPLANATION_MESSAGE_WHATS_THIS = 'sales/msrp/explanation_message_whats_this';
    /**#@-*/

    /**
     * @var \Magento\Store\Model\ScopeInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var -Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var int
     */
    protected $storeId;

    /**
     * @param \Magento\Store\Model\ScopeInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Escaper $escaper
     */
    public function __construct(
        \Magento\Store\Model\ScopeInterface $scopeConfig,
        \StoreManagerInterface $storeManager,
        \Magento\Framework\Escaper $escaper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
    }

    /**
     * Set a specified store ID value
     *
     * @param int $store
     * @return $this
     */
    public function setStoreId($store)
    {
        $this->storeId = $store;
        return $this;
    }

    /**
     * Check if Auto Order Cancel is enabled
     *
     * @return bool
     * @api
     */
    public function isEnabled()
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_AUTOORDERCANCEL_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
    }

    /**
     * Return Chosen Order Statuses Array
     *
     * @return array
     */
    public function getOrderStatuses()
    {
        $statuses = $this->scopeConfig->getValue(
            self::XML_PATH_AUTOORDERCANCEL_ORDER_STATUSES,
            ScopeInterface::SCOPE_STORE,
            $this->storeId
        );
        if (empty($statuses)) {
            return array();
        }

        return explode(',', $statuses);
    }

    /**
     * Return Cancel Orders Older Than (minutes)
     *
     * @return string
     */
    public function getOlderThan()
    {
        return $this->escaper->escapeHtml(
            $this->scopeConfig->getValue(
                self::XML_PATH_CANCEL_ORDERS_OLDER_THAN,
                ScopeInterface::SCOPE_STORE,
                $this->storeId
            ),
            ['b', 'br', 'strong', 'i', 'u', 'p', 'span']
        );
    }

    /**
     * Return Cancel Orders Recent Than (minutes)
     *
     * @return string
     */
    public function getRecentThan()
    {
        return $this->escaper->escapeHtml(
            $this->scopeConfig->getValue(
                self::XML_PATH_CANCEL_ORDERS_RECENT_THAN,
                ScopeInterface::SCOPE_STORE,
                $this->storeId
            ),
            ['b', 'br', 'strong', 'i', 'u', 'p', 'span']
        );
    }

    /**
     * Retrieve Comment for automatically canceled orders
     *
     * @return string
     */
    public function getComment()
    {
        return $this->escaper->escapeHtml(
            $this->scopeConfig->getValue(
                self::XML_PATH_CANCEL_ORDERS_COMMENT,
                ScopeInterface::SCOPE_STORE,
                $this->storeId
            ),
            ['b', 'br', 'strong', 'i', 'u', 'p', 'span']
        );
    }
}