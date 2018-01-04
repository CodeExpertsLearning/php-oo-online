<?php
namespace CodeExperts\App\Controller;

use CodeExperts\App\Entity\Product;
use CodeExperts\DB\Connection;
use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;

class ProductController extends BaseController
{
	public function index($id)
	{
		$id = (int) $id;

		$product = new Product(Connection::getInstance($this->getConfig('database')));
		$product = $product->getProductAndImagesById($id);

		$view = new View(VIEWS_FOLDER . 'site/single.phtml');

		$view->product = $product;

		return $view->render();
	}
}