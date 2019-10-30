<?php
namespace Jadlog\Embarcador\Integracao\Frete;

use \Jadlog\Embarcador\Helper\Data as HelperData;

class Valor {

  private $helperData;
  private $cepdes;
  private $cepori;
  private $cnpj;
  private $conta;
  private $contrato;
  private $modalidade;
  private $peso;
  private $tpentrega;
  private $tpseguro;
  private $vldeclarado;
  private $freteurl;
  private $token;


  private function writeLog($ident, $msg) {
    // tail -f /var/www/html/var/log/plugintest.log
    $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/plugintest.log');
    $logger = new \Zend\Log\Logger();
    $logger->addWriter($writer);
    ob_start();
    $message = ["Jadlog\Embarcador\Integracao\Frete\Valor $ident" => $msg];
    print_r($message);
    $logger->info(ob_get_clean());
  }

  public function __construct($helper, $cep, $peso, $valor_declarado, $modalidade) {
    $cep_array = (is_array($cep) ? $cep: [$cep]);
    $this->helperData = $helper;
    $this->cepdes = array_unique($cep_array);
    $this->cepori = $this->helperData->getRemetenteCep();
    $this->cnpj = $this->helperData->getRemetenteCNPJ();
    $this->conta = $this->helperData->getContaCorrente();
    $this->contrato = $this->helperData->getNumeroContrato();
    $this->modalidade = $modalidade;
    $this->peso = $peso;
    $this->tpentrega = "D";
    $this->tpseguro = "N";
    $this->vldeclarado = $valor_declarado;
    $this->freteurl = $this->helperData->getFreteURL();
    $this->token = $this->helperData->getToken();
  }

  /*TODO: cache de valores de frete */
  public function getData() {
    //return $this->getBulkRealData();
    //return $this->getFakeData();
    return $this->getRealData();
  }

  private function getDataFromService($data) {
    $data_string = json_encode($data);
    $ch = curl_init($this->freteurl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Authorization: ' . $this->token,
      'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    $result = json_decode($result, true);

    //log
    $message = [
      'data' => print_r($data, true),
      '$this->freteurl' => $this->freteurl,
      'result' => print_r($result, true)
    ];
    $this->writeLog(date('Y-m-d H:i:s'), $message);
    //log

    return $result;
  }

  private function getRealData() {
    $result = [];
    foreach($this->cepdes as $cep) {
      $data = [
        "frete" => [[
          "cepdes" => $cep,
          "cepori" => $this->cepori,
          "cnpj" => $this->cnpj,
          "conta" => $this->conta,
          "contrato" => $this->contrato,
          "modalidade" => $this->modalidade,
          "peso" => $this->peso,
          "tpentrega" => $this->tpentrega,
          "tpseguro" => $this->tpseguro,
          "vldeclarado" => $this->vldeclarado
        ]]
      ];
      $result[$cep] = $this->getDataFromService($data);
    }
    return $result;
  }

  private function getBulkRealData() {
    $data = [];
    foreach($this->cepdes as $cep) {
      array_push($data, [
        "cepdes" => $cep,
        "cepori" => $this->cepori,
        "cnpj" => $this->cnpj,
        "conta" => $this->conta,
        "contrato" => $this->contrato,
        "modalidade" => $this->modalidade,
        "peso" => $this->peso,
        "tpentrega" => $this->tpentrega,
        "tpseguro" => $this->tpseguro,
        "vldeclarado" => $this->vldeclarado
      ]);
    }
    $data = [
      "frete" => $data
    ];
    return $this->getDataFromService($data);
  }

  private function getFakeData() {
    $result = [];
    foreach($this->cepdes as $cep) {
      preg_match_all("/\d+/",$cep,$cep_a);
      $cep_i = join($cep_a[0],"");
      $valor = (intval($cep_i) % 10000) * 0.01 * $this->peso;
      $result[$cep] = ['frete' => [ 0 => ['vltotal' => $valor]]];
    }
    return $result;
  }
}

?>