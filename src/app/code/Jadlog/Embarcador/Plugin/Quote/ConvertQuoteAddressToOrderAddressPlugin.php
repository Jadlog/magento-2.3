<?php
namespace Jadlog\Embarcador\Plugin\Quote;

/*
Plugin to copy "jadlog_pudo" field from quote_address to order_address.
Done via a plugin because fieldset.xml does not seem to work.
https://magento.stackexchange.com/questions/124712/magento-2-fieldset-xml-copy-fields-from-quote-to-order
https://github.com/magento/magento2/issues/5823
*/
class ConvertQuoteAddressToOrderAddressPlugin {
  private function writeLog($ident, $msg) {
    // tail -f /var/www/html/var/log/plugintest.log
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugintest.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    ob_start();
    $message = ["ConvertQuoteAddressToOrderAddressPlugin $ident" => $msg];
    print_r($message);
    $logger->info(ob_get_clean());
  }

  public function aroundConvert(
    \Magento\Quote\Model\Quote\Address\ToOrderAddress $subject,
    \Closure $proceed,
    \Magento\Quote\Model\Quote\Address $quoteAddress,
    $data = []
  ) {
    $orderAddress = $proceed($quoteAddress, $data);
    if ($quoteAddress->getJadlogPudo()) {
      $orderAddress->setJadlogPudo($quoteAddress->getJadlogPudo());
    }

    //log
    $message = [
      '$quoteAddress->getJadlogPudo()' => $quoteAddress->getJadlogPudo(),
      '$orderAddress->getJadlogPudo()' => $orderAddress->getJadlogPudo()
    ];
    $this->writeLog(date('Y-m-d H:i:s'), $message);
    //log

    return $orderAddress;
  }
}
?>