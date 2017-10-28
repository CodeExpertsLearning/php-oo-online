<?php
namespace CodeExperts\App\Controller;

use CodeExperts\App\Entity\Product;
use CodeExperts\DB\Connection;
use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;

class HomeController extends BaseController
{
	public function index()
	{
		$products = new Product(Connection::getInstance($this->getConfig('database')));
		$products = $products->getProductsAndImages();

		$view = new View(VIEWS_FOLDER . 'site/home.phtml');

		$view->products = $products;

		return $view->render();
	}
}