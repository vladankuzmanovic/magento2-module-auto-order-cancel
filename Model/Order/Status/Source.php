<?php
/**
 * @author	    Vladan Kuzmanovic (vladankuzmanovic@gmail.com)
 */
namespace Kuzman\AutoOrderCancel\Model\Order\Status;

class Source extends \Magento\Sales\Model\Config\Source\Order\Status
{
    protected $_stateStatuses = [];
    /**
     * Retrieve order statuses as options for select
     *
     * @see \Magento\Sales\Model\Config\Source\Order\Status:toOptionArray()
     * @return array
     */
    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        array_shift($options);
        // Remove '--please select--' option
        return $options;
    }
}