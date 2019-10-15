<?php
namespace Jadlog\Embarcador\Controller\Frete;
//http://magento.dev.local/jadlog_embarcador/frete/valor

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Jadlog\Embarcador\Integracao\Frete\Valor as FreteValor;

class Valor extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface {
	protected $_pageFactory;
	protected $_helperData;
	protected $request;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		\Jadlog\Embarcador\Helper\Data $helperData,
		\Magento\Framework\App\RequestInterface $request
	) {
		$this->_pageFactory = $pageFactory;
		$this->_helperData = $helperData;
		$this->request = $request;
		$this->resultJsonFactory = $resultJsonFactory;
		return parent::__construct($context);
	}

	public function getParam($param) {
		return $this->request->getParam($param);
	}

	public function execute() {
		//echo "Hello World " . print_r($this->getParam('cep'),true);
		//echo "42.42";
		//exit;
		$cep = $this->_helperData->getCep($this->getParam('cep'));
		$result = $this->resultJsonFactory->create();
		$f = new FreteValor(
			$this->_helperData,
			$cep['cep'],
			$this->getParam('peso'),
			$this->getParam('valor_declarado'),
			$this->getParam('modalidade')
		);
		$r = $f->getData();
		return $result->setData([$r] + ['success' => true] + ['valor' => $r['frete'][0]['vltotal']]);
	}
}
