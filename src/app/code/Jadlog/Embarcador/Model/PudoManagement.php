<?php
namespace Jadlog\Embarcador\Model;

use Jadlog\Embarcador\Api\PudoManagementInterface;
use Jadlog\Embarcador\Api\Data\PudoInterfaceFactory;

class PudoManagement implements PudoManagementInterface {
  protected $PudoFactory;

  /**
  * PudoManagement constructor.
  * @param PudoInterfaceFactory $PudoInterfaceFactory
  */
  public function __construct(PudoInterfaceFactory $PudoInterfaceFactory) {
    $this->PudoFactory = $PudoInterfaceFactory;
  }

  /**
  * Get Pudos for the given postcode and city
  *
  * @param string $postcode
  * @param string $city
  * @return \Jadlog\Embarcador\Api\Data\PudoInterface[]
  */
  public function fetchPudos($postcode, $city) {
    $result = [];
    for($i = 0; $i < 4; $i++) {
      $Pudo = $this->PudoFactory->create();
      $Pudo->setName("Pudo {$i}");
      $Pudo->setLocation("Address {$i}");
      $result[] = $Pudo;
    }

    return $result;
  }
}
?>