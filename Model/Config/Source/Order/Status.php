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
namespace Kuzman\AutoOrderCancel\Model\Config\Source\Order;

class Status extends \Magento\Sales\Model\Config\Source\Order\Status
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