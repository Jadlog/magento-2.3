<?php
namespace Jadlog\Embarcador\Model;

use Magento\Framework\DataObject;
use Jadlog\Embarcador\Api\Data\PudoInterface;

class Pudo extends DataObject implements PudoInterface {
  /**
  * @return string
  */
  public function getName() {
    return (string)$this->_getData('name');
  }

  /**
  * @return string
  */
  public function getLocation() {
    return (string)$this->_getData('location');
  }
}
?>