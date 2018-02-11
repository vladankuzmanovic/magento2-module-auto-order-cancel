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
namespace Kuzman\AutoOrderCancel\Model\Config\Source\Payment;

class Method
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $appConfigScopeConfigInterface;

    /**
     * @var \Magento\Payment\Model\Config
     */
    protected $paymentModelConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $appConfigScopeConfigInterface
     * @param \Magento\Payment\Model\Config $paymentModelConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $appConfigScopeConfigInterface,
        \Magento\Payment\Model\Config $paymentModelConfig
    )
    {
        $this->appConfigScopeConfigInterface = $appConfigScopeConfigInterface;
        $this->paymentModelConfig = $paymentModelConfig;
    }

    public function toOptionArray()
    {
        $payments = $this->paymentModelConfig->getActiveMethods();
        $methods = array();
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->appConfigScopeConfigInterface->getValue('payment/' . $paymentCode . '/title');
            $methods[$paymentCode] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode
            );
        }

        return $methods;
    }
}