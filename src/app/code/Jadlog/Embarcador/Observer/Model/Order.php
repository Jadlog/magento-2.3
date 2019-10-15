<?php
namespace Jadlog\Embarcador\Observer\Model;
use Magento\Framework\Event\ObserverInterface;
use Jadlog\Embarcador\Model\SalesOrderFactory;
use Jadlog\Embarcador\Model\QuoteFactory;

class Order implements ObserverInterface {

  protected $_helperData;
  protected $_orderFactory;
  protected $_quoteFactory;

  private function writeLog($ident, $msg) {
    // tail -f /var/www/html/var/log/plugintest.log
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugintest.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    ob_start();
    $message = ["Jadlog\Embarcador\Observer\Model\Order $ident" => $msg];
    print_r($message);
    $logger->info(ob_get_clean());
  }

  public function __construct(
    \Jadlog\Embarcador\Helper\Data $helperData,
    SalesOrderFactory $orderFactory,
    QuoteFactory $quoteFactory
  ) {
    //$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $this->_helperData = $helperData;
    $this->_orderFactory = $orderFactory;
    $this->_quoteFactory = $quoteFactory;
  }

  public function execute(\Magento\Framework\Event\Observer $observer) {
    $order = $observer->getOrder();
    $customerId = $order->getCustomerId();

    $jadlog_quote = $this->_quoteFactory->create();
    $jadlog_quote->load($order->getQuoteId(), 'quote_id');

    $pudo = $jadlog_quote->getPickup();

    $message = [
      '$this->_helperData->isEntregaJadlog($order->getShippingMethod())' => $this->_helperData->isEntregaJadlog($order->getShippingMethod()),
      '$order->getShippingMethod()' => $order->getShippingMethod(),
      '$order->getId()' => $order->getId(),
      '$jadlog_quote->getId()' => $jadlog_quote->getId(),
      "customerId" => $customerId,
      "pudo" => $pudo
    ];
    $this->writeLog(date('Y-m-d H:i:s'), $message);

    if ($order->getId() && $this->_helperData->isEntregaJadlog($order->getShippingMethod())) {
      $jadlog_sales_order = $this->_orderFactory->create();
      $jadlog_sales_order->load($order->getId(), 'order_id');

      //salvar jadlog sales order
      $jadlog_sales_order->
        setOrderId($order->getId())->
        setPickup($pudo)->
        save();
      $message = [
        'salvar jadlog_sales_order' => 'sim',
        '$order->getId()' => $order->getId(),
        '$jadlog_sales_order->getOrderId()' => $jadlog_sales_order->getOrderId(),
        '$jadlog_sales_order->getPickup()' => $jadlog_sales_order->getPickup()
      ];
      $this->writeLog(date('Y-m-d H:i:s'), $message);
    }

    return $this;
  }
}
?>