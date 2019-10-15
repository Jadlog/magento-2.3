<?php
namespace Jadlog\Embarcador\Plugin\Quote;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\ShippingInterface;
use Magento\Quote\Model\ShippingAssignment;
use Jadlog\Embarcador\Model\Carrier\JadlogPickup;

class ShippingAssignmentPlugin {

  private function writeLog($ident, $msg) {
    // tail -f /var/www/html/var/log/plugintest.log
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugintest.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    ob_start();
    $message = ["ShippingAssignmentPlugin $ident" => $msg];
    print_r($message);
    $logger->info(ob_get_clean());
  }

  public function beforeSetShipping($subject, ShippingInterface $value) {
    $method = $value->getMethod();
    $address = $value->getAddress();

    $message = [
      "aqui" => 1,
      "method" => $method,
      '$address->getExtensionAttributes()' => $address->getExtensionAttributes(),
      '$address->getExtensionAttributes()->getJadlogPudo()' => $address->getExtensionAttributes()->getJadlogPudo()
    ];
    $this->writeLog(date('Y-m-d H:i:s'), $message);

    if ($method === JadlogPickup::carrierMethod()
      && $address->getExtensionAttributes()
      && $address->getExtensionAttributes()->getJadlogPudo()
    ) {
      $message = [
        "method" => $method,
        "JadlogPickup::carrierMethod()" => JadlogPickup::carrierMethod(),
        '$address->getExtensionAttributes()->getJadlogPudo()' => $address->getExtensionAttributes()->getJadlogPudo()
      ];
      $this->writeLog(date('Y-m-d H:i:s'), $message);
      $address->setJadlogPudo($address->getExtensionAttributes()->getJadlogPudo());
    } elseif ($method !== JadlogPickup::carrierMethod()) {
      //reset pudo when changing shipping method
      $address->setJadlogPudo(null);
      $this->writeLog(date('Y-m-d H:i:s'), "limpar");
    }
    return [$value];
  }
}
?>