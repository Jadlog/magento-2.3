<?php
namespace Jadlog\Embarcador\Plugin\Checkout;

/*
Plugin to save a Pudo address as Shipping Address
*/
class SaveAddressPlugin {
  private function writeLog($ident, $msg) {
    // tail -f /var/www/html/var/log/plugintest.log
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugintest.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    ob_start();
    $message = ["SaveAddressPlugin $ident" => $msg];
    print_r($message);
    $logger->info(ob_get_clean());
  }

  //private $pudoRepository;
  private $customerSession;
  private $addressDataFactory;

  public function __construct(
    //JadlogPudoRepositoryInterface $pudoRepository,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Customer\Api\Data\AddressInterfaceFactory $addressInterfaceFactory
  ) {
    //$this->pudoRepository = $pudoRepository;
    $this->customerSession    = $customerSession;
    $this->addressDataFactory = $addressInterfaceFactory;
  }

  public function beforeSaveAddressInformation(
    \Magento\Checkout\Api\ShippingInformationManagementInterface $subject,
    $cartId,
    \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
  ) {
    $shippingAddress = $addressInformation->getShippingAddress();
    $billingAddress  = $addressInformation->getBillingAddress();

    //log
    $message = [
      '$shippingAddress->getExtensionAttributes()->getJadlogPudo()' => $shippingAddress->getExtensionAttributes()->getJadlogPudo()
    ];
    $this->writeLog(date('Y-m-d H:i:s'), $message);
    //log

    if ($shippingAddress->getExtensionAttributes() && $shippingAddress->getExtensionAttributes()->getJadlogPudo()) {
      //aqui podemos carregar uma tabela com mais dados do pudo
      //$pudo = $this->jadlogPudoRepository->get($shippingAddress->getExtensionAttributes()->getJadlogPudoId());
      $pudo=$shippingAddress->getExtensionAttributes()->getJadlogPudo();
      if ($pudo) {

        //log
        $message = [
          '$pudo' => $pudo
        ];
        $this->writeLog(date('Y-m-d H:i:s'), $message);
        //log

        $shippingAddress->setJadlogPudo($pudo);
        /*
        $address = $this->addressDataFactory->create(
          ['data' => $pudo->getAddress()->getData()]
        );
        $shippingAddress->importCustomerAddressData($address);
        $shippingAddress->setCompany($pudo->getName());
        $shippingAddress->setJadlogPudoId((int) $pudo->getId());

        // Potentially copy billing fields (if present, this is not the case when customer is not logged in).
        if (!$shippingAddress->getFirstname()) {
          $shippingAddress->setFirstname($billingAddress->getFirstname());
        }
        if (!$shippingAddress->getLastname()) {
          $shippingAddress->setLastname($billingAddress->getLastname());
        }
        if (!$shippingAddress->getTelephone()) {
          $shippingAddress->setTelephone($billingAddress->getTelephone());
        }
        */
      }
    }
  }
}
?>