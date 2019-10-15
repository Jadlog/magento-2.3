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
    $this->helperData = $helper;
    $this->cepdes = $cep;
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

  public function getData() {
    $data = [
      "frete" => [
        0 => [
          "cepdes" => $this->cepdes,
          "cepori" => $this->cepori,
          "cnpj" => $this->cnpj,
          "conta" => $this->conta,
          "contrato" => $this->contrato,
          "modalidade" => $this->modalidade,
          "peso" => $this->peso,
          "tpentrega" => $this->tpentrega,
          "tpseguro" => $this->tpseguro,
          "vldeclarado" => $this->vldeclarado
        ]
      ]
    ];

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
      'result' => print_r($result, true)
    ];
    $this->writeLog(date('Y-m-d H:i:s'), $message);
    //log

    return $result;
  }
}

?>