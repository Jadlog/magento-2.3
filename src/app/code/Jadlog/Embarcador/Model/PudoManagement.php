<?php
namespace Jadlog\Embarcador\Model;

use Jadlog\Embarcador\Api\PudoManagementInterface;
use Jadlog\Embarcador\Api\Data\PudoInterfaceFactory;
use Jadlog\Embarcador\Integracao\MyPudo\PudoService as PudoService;
use Jadlog\Embarcador\Integracao\Frete\Valor as FreteValor;
use Magento\Checkout\Model\Session as CheckoutSession;

class PudoManagement implements PudoManagementInterface {
  protected $PudoFactory;
  protected $_helperData;
  protected $_checkoutSession;
  protected $_modalidade;
  CONST WEEK_DAYS = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

  /**
  * PudoManagement constructor.
  * @param PudoInterfaceFactory $PudoInterfaceFactory
  * @param \Jadlog\Embarcador\Helper\Data $helperData
  */
  public function __construct(
    PudoInterfaceFactory $PudoInterfaceFactory,
    \Jadlog\Embarcador\Helper\Data $helperData,
    CheckoutSession $checkoutSession
  ) {
    $this->PudoFactory = $PudoInterfaceFactory;
    $this->_helperData = $helperData;
    $this->_checkoutSession = $checkoutSession;
    $this->_modalidade = $this->_helperData->getCodigoPickup();
  }

  /**
  * Set Pudo for current CheckoutSession
  *
  * @param mixed $pudo
  * @return boolean
  */
  public function setPudo($pudo) {
    $this->_checkoutSession->setJadlogPudoData(json_encode($pudo));
    return true;
  }

  /**
  * Get Pudos for the given postcode and city
  *
  * @param string $postcode
  * @param string $city
  * @return \Jadlog\Embarcador\Api\Data\PudoInterface[]
  */
  public function fetchPudos($postcode, $city) {
    $f = new PudoService(
      $this->_helperData,
      $postcode,
      $city
    );
    $r = $f->getData();

    $weight = $this->_checkoutSession->getJadlogWeight();
    $value = $this->_checkoutSession->getJadlogValue();
    $result = [];
    foreach($r['PUDO_ITEMS']['PUDO_ITEM'] as $pudo_item) {
      $cep = $this->_helperData->getCep($pudo_item['ZIPCODE']);
      if (empty($cep["error"])) {
        $Pudo = $this->PudoFactory->create();

        $Pudo->setName($pudo_item['NAME']);
        $Pudo->setPudoId($pudo_item['PUDO_ID']);
        $Pudo->setLocation($this->humanLocation($pudo_item));
        $Pudo->setOpeningHours($this->humanOpeningHours($pudo_item));
        $Pudo->setLatitude($pudo_item['LATITUDE']);
        $Pudo->setLongitude($pudo_item['LONGITUDE']);
        $Pudo->setDistance($pudo_item['DISTANCE']);
        $Pudo->setZipcode($cep['cep']);
        $Pudo->setCity($pudo_item['CITY']);
        $Pudo->setRate($this->getPudoShippingPrice($Pudo->getZipcode(), $weight, $value));

        $Pudo->setId(json_encode([
          'Name' => $Pudo->getName(),
          'PudoId' => $Pudo->getPudoId(),
          'Location' => $Pudo->getLocation(),
          'OpeningHours' => $Pudo->getOpeningHours(),
          'Latitude' => $Pudo->getLatitude(),
          'Longitude' => $Pudo->getLongitude(),
          'Distance' => $Pudo->getDistance(),
          'Zipcode' => $Pudo->getZipcode(),
          'City' => $Pudo->getCity(),
          'Rate' => $Pudo->getRate()
        ]));

        $result[] = $Pudo;
      }
    }
    return $result;
  }

  private function getPudoShippingPrice($cep, $peso, $valor_declarado) {
    $f = new FreteValor($this->_helperData, $cep, $peso, $valor_declarado, $this->_modalidade);
    $r = $f->getData();

    $shippingPrice = $r[$cep]['frete'][0]['vltotal'];

    return $shippingPrice;
  }

  private function humanLocation($pi) {
    $address1 = $this->_helperData->sanitizeValue($pi['ADDRESS1']);
    $streetnum = $this->_helperData->sanitizeValue($pi['STREETNUM']);
    $address2 = $this->_helperData->sanitizeValue($pi['ADDRESS2']);
    $address3 = $this->_helperData->sanitizeValue($pi['ADDRESS3']);
    $city = $pi['CITY'];
    $zipcode = $this->_helperData->sanitizeValue($pi['ZIPCODE']);

    $message = "{$address1}, {$streetnum} - {$address2} - {$address3} - CEP: {$zipcode} - {$city}";
    return $message;
  }

  private function humanOpeningHours($pudo_item) {
    $h = [];
    foreach($pudo_item['OPENING_HOURS_ITEMS']['OPENING_HOURS_ITEM'] as $oh) {
      $day = intval($oh['DAY_ID']) % 7;
      if (empty($h[$day])) {
        $h[$day] = "das {$oh['START_TM']} às {$oh['END_TM']}";
      } else {
        $h[$day] = $h[$day] . " e das {$oh['START_TM']} às {$oh['END_TM']}";
      }
    }

    $message = "";
    for($j=1; $j<=7; $j++) {
      $i = $j % 7;
      if (empty($h[$i])) {
        $h[$i] = 'fechado';
      }
      $day = $this->humanDayId($i);
      $message = $message . "{$day}: {$h[$i]}.\n";
    }

    return $message;
  }

  private function humanDayId($day_id) {
    return self::WEEK_DAYS[$day_id % 7];
  }
}
?>