<?php
namespace Jadlog\Embarcador\Api;

interface PudoManagementInterface {

  /**
  * Find pudos for the customer
  *
  * @param string $postcode
  * @param string $city
  * @return \Jadlog\Embarcador\Api\Data\PudoInterface[]
  */
  public function fetchPudos($postcode, $city);
}
?>