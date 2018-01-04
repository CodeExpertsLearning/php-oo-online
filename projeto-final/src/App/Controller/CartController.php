<?php
namespace CodeExperts\App\Controller;

use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\App\Entity\Product;
use CodeExperts\DB\Connection;
use CodeExperts\Tools\Session;
use CodeExperts\App\Service\Cart;

class CartController extends BaseController
{
	private $cart;
	private $session;

	public function __construct()
	{
		$this->session = new Session();

		$this->cart = new Cart($this->session);
	}

	public function index()
	{
		$view = new View(VIEWS_FOLDER . '/site/cart.phtml');

		$view->products = isset($_SESSION['products']) ? $_SESSION['products'] : [];

		if($view->products) {
			$products = [];
			$products['total'] = 0;

			foreach($view->products as $key => $p){
				$products['products'][$key] = (new Product(Connection::getInstance($this->getConfig('database'))))
												->getProductAndImagesById($p);

				$products['total'] = $products['total'] + $products['products'][$key]['price'];

			}
			(new Session())->sessionStart();
			$_SESSION['products_total'] = $products['total'];

			$view->products = $products;
		}

		$view->session  = $this->session;

		return $view->render();

	}

	public function add($id)
	{
		$id = (int) $id;

		$produto = $this->cart->add($id);

		header('Location: /product/' . $produto);
	}

	public function remove($id)
	{
		$id = (int) $id;
		$this->cart->remove($id);

		header('Location: /cart');
	}
}