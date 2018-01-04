<?php
namespace CodeExperts\App\Controller;

use CodeExperts\App\Entity\Product;
use CodeExperts\App\Entity\UserOrder;
use CodeExperts\DB\Connection;
use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\Tools\Session;

class PaymentsController extends BaseController
{
	private function initPagSeguro()
	{
		(new Session())->sessionStart();

		if(!isset($_SESSION['session_pagseguro'])) {

			//Init PagSeguro
			\PagSeguroLibrary::init();
			\PagSeguroConfig::init();
			\PagSeguroResources::init();

			//Session ID
			$credentials = \PagSeguroConfig::getAccountCredentials();
			$sessionToken = \PagSeguroSessionService::getSession($credentials);

			$_SESSION['session_pagseguro'] = $sessionToken;
		}

	}

	public function index()
	{
		$this->initPagSeguro();

		$view = new View(VIEWS_FOLDER . 'site/payments.phtml');

		return $view->render();
	}

	public function proccess()
	{
		$methodsAllowed = ['BOLETO', 'CREDIT_CARD'];

		(new Session())->sessionStart();

		$products = $_SESSION['products'];
		$user     = $_SESSION['user'];
		$hash     = $_POST['hash'];
		$method   = $_POST['method'];

		if(!in_array($method, $methodsAllowed)) {
			return json_encode([
				'success' => false,
				'msg' => 'Método não disponivel!'
			]);
		}

		$paymentDirect = new \PagSeguroDirectPaymentRequest();
		$paymentDirect->setPaymentMode('DEFAULT');
		$paymentDirect->setPaymentMethod($method);
		$paymentDirect->setCurrency('BRL');
		$paymentDirect->setReceiverEmail('nandokstro@gmail.com');

		$total = 0;

		$order = new UserOrder(Connection::getInstance($this->getConfig('database')));
		$order->user_id = $user['id'];
		$order->products = serialize($products);

		$order = $order->insert();



		foreach($products as $prod) {
			$aProd = (new Product(Connection::getInstance($this->getConfig('database'))))->find($prod);

			$paymentDirect->addItem($order, $aProd['name'], 1, $aProd['price']);

			$total += $aProd['price'];
		}

		$paymentDirect->setSender(
			'Jose Comprador',
			'joao@sandbox.pagseguro.com.br',
			'11',
			'984283645',
			'CPF',
			'000.000.000-00'
		);

		$paymentDirect->setSenderHash($hash);

		$sedexCode = \PagSeguroShippingType::getCodeByType('SEDEX');

		$paymentDirect->setShippingType($sedexCode);

		$paymentDirect->setShippingAddress(
			'01452002',
			'Av. Brig. Faria Lima',
			'1384',
			'apto. 114',
			'Jardim Paulistano',
			'São Paulo',
			'SP',
			'BRA'
		);

		if($method == 'CREDIT_CARD') {
			$token       = $_POST['token'];
			$installment = explode('|', $_POST['installments']);

			$installmentsNumber   = $installment[0];
			$valueInstallment     = $installment[1];

			$creditCardData = new \PagSeguroCreditCardCheckout(
				array(
					'token' => $token,
					'installment' => new \PagSeguroDirectPaymentInstallment([
						'quantity' => $installmentsNumber,
						'value' => $valueInstallment,
						"noInterestInstallmentQuantity" => 3
					]),
					'billing' => new \PagSeguroBilling(
						array(
							'postalCode' => '01452002',
							'street' => 'Av. Brig. Faria Lima',
							'number' => '1384',
							'complement' => 'apto. 114',
							'district' => 'Jardim Paulistano',
							'city' => 'São Paulo',
							'state' => 'SP',
							'country' => 'BRA'
						)
					),
					'holder' => new \PagSeguroCreditCardHolder(
						array(
							'name' => 'João Comprador',
							'birthDate' => date('01/10/1979'),
							'areaCode' => '11',
							'number' => '56273440',
							'documents' => array(
								'type' => 'CPF',
								'value' => '156.009.442-76'
							)
						)
					)
				)
			);

			$paymentDirect->setCreditCard($creditCardData);
		}

		try {
			$credentials = \PagSeguroConfig::getAccountCredentials();

			$response = $paymentDirect->register($credentials);

			$updateOrder = new UserOrder(Connection::getInstance($this->getConfig('database')));
			$updateOrder->id = $order;
			$updateOrder->pagseguro_code = $response->getCode();
			$updateOrder->update();

			$return = [
				'success' => true,
			];

			(new Session())->sessionStart();
			$_SESSION['dataPurchase'] = $response;

			return json_encode($return);

		} catch (\PagSeguroServiceException $e) {
			return json_encode([
				'message' => $e->getMessage(),
				'success' => false
			]);
		}

	}

	public function success()
	{
		(new Session())->sessionStart();

		if(!isset($_SESSION['dataPurchase']))
			return header('Location: ' . HOME);


		$view = new View(VIEWS_FOLDER . 'site/success.phtml');

		$view->dataPurchase = $_SESSION['dataPurchase'];

		unset($_SESSION['dataPurchase']);
		unset($_SESSION['user']);
		unset($_SESSION['products']);
		unset($_SESSION['session_pagseguro']);
		unset($_SESSION['products_total']);

		return $view->render();
	}
}