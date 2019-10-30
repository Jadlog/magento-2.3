<?php
namespace Jadlog\Embarcador\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Jadlog\Embarcador\Model\Carrier\JadlogExpresso;
use Jadlog\Embarcador\Model\Carrier\JadlogPickup;

class Data extends AbstractHelper {
  public function getCodigoPickup() {
    return 40; #Pickup
  }

  public function getCodigoExpresso() {
    return 9; #.COM = Expresso
  }

  public function getCep($cep) {
    $error = "";
    //extrai somente os números
    $cepNumbers = preg_replace("/\D/", "", $cep);
    if (strlen($cepNumbers) != 8) {
      $error = "CEP inválido. O CEP deve conter 8 algarismos.";
      $cepNumbers = $cep;
    }
    return array("error" => $error, "cep" => $cepNumbers);
  }

  public function sanitizeValue($x) {
    $r = $x;
    if (is_array($x)) {
      $r = join($x," ");
    }
    return trim($r);
  }

  public function getConfigValue($field) {
    return $this->scopeConfig->getValue($field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
  }

  public function getModalidades() {
    return array_map("intval",explode(",",$this->getConfigValue("jadlog_embarcador/geral/modalidades")));
  }

  public function getHabilitar() {
    return boolval($this->getConfigValue("jadlog_embarcador/geral/habilitar"));
  }

  public function getPickupHabilitado() {
    return in_array($this->getCodigoPickup(), $this->getModalidades());
  }

  public function getExpressoHabilitado() {
    return in_array($this->getCodigoExpresso(), $this->getModalidades());
  }

  public function getConfig() {
    return $this->getConfigValue("jadlog_embarcador");
  }

  public function isEntregaJadlogPickup($shippingMethod) {
    return (JadlogPickup::carrierMethod() === $shippingMethod);
  }

  public function isEntregaJadlogExpresso($shippingMethod) {
    return (JadlogExpresso::carrierMethod() === $shippingMethod);
  }

  public function isEntregaJadlog($shippingMethod) {
    return ($this->isEntregaJadlogPickup($shippingMethod) || $this->isEntregaJadlogExpresso($shippingMethod));
  }

  public function getToken() {
    return $this->getConfigValue("jadlog_embarcador/geral/token");
  }

  public function getNumeroContrato() {
    return $this->getConfigValue("jadlog_embarcador/geral/numero_contrato");
  }

  public function getContaCorrente() {
    return $this->getConfigValue("jadlog_embarcador/geral/conta_corrente");
  }

  public function getFreteURL() {
    return $this->getConfigValue("jadlog_embarcador/geral/frete_url");
  }

  public function getPedidoURL() {
    return $this->getConfigValue("jadlog_embarcador/geral/pedido_url");
  }

  public function getTrackingURL() {
    return $this->getConfigValue("jadlog_embarcador/geral/tracking_url");
  }

  public function getRemetenteCNPJ() {
    return $this->getConfigValue("jadlog_embarcador/remetente/cnpj");
  }

  public function getRemetenteIE() {
    return $this->getConfigValue("jadlog_embarcador/remetente/ie");
  }

  public function getRemetenteCep() {
    return $this->getConfigValue("jadlog_embarcador/remetente/cep");
  }

  public function getMyPudoURL() {
    return $this->getConfigValue("jadlog_embarcador/pickup/mypudo_url");
  }

  public function getMyPudoShipperId() {
    return $this->getConfigValue("jadlog_embarcador/pickup/mypudo_shipper_id");
  }

  public function getMyPudoKey() {
    return $this->getConfigValue("jadlog_embarcador/pickup/mypudo_key");
  }

}
?>
