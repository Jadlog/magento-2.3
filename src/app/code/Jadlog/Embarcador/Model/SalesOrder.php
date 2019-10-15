<?php
namespace Jadlog\Embarcador\Model;

class SalesOrder extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface {
  const CACHE_TAG = 'jadlog_sales_order';
  protected $_cacheTag = 'jadlog_sales_order';
  protected $_eventPrefix = 'jadlog_sales_order';

  protected function _construct() {
    $this->_init('Jadlog\Embarcador\Model\ResourceModel\SalesOrder');
  }

  public function getIdentities() {
    return [self::CACHE_TAG . '_' . $this->getId()];
  }

  public function getDefaultValues() {
    $values = [];
    return $values;
  }
}
?>
