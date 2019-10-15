<?php
namespace Jadlog\Embarcador\Plugin\Quote;

use Magento\Quote\Model\Quote\Address;
use Jadlog\Embarcador\Model\Carrier\JadlogPickup;

class AddressPlugin {

  private function writeLog($ident, $msg) {
    // tail -f /var/www/html/var/log/plugintest.log
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugintest.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    ob_start();
    $message = ["AddressPlugin $ident" => $msg];
    print_r($message);
    $logger->info(ob_get_clean());
  }

  public function around__call($subject, $proceed, $method, $vars) {
    $result = $proceed($method, $vars);
    if ($method == 'setShippingMethod'
      && $vars[0] == JadlogPickup::carrierMethod()
      && $subject->getExtensionAttributes()
      && $subject->getExtensionAttributes()->getJadlogPudo()
    ) {
      $subject->setJadlogPudo($subject->getExtensionAttributes()->getJadlogPudo());
      $message = [
        "method" => $method,
        "vars" => $vars,
        "JadlogPickup::carrierMethod()" => JadlogPickup::carrierMethod(),
        '$subject->getExtensionAttributes()->getJadlogPudo()' => $subject->getExtensionAttributes()->getJadlogPudo()
      ];
      $this->writeLog(date('Y-m-d H:i:s'), $message);
    }
    elseif (
      $method == 'setShippingMethod'
      && $vars[0] != "JadlogPickup::CARRIER_CODE.'_'.JadlogPickup::METHOD_CODE"
    ) {
      //reset pudo when changing shipping method
      $subject->setJadlogPudo(null);
      $this->writeLog(date('Y-m-d H:i:s'), "limpar");
    }
    return $result;
  }
}
?>