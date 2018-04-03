<?php
namespace Code\Controller;

use Code\DB\Connection;
use Code\View\View;
use Code\Entity\Product;

class ProductController
{
	public function index($id)
	{
	//	$id = (int) $id;

		$pdo = Connection::getInstance();

		$view = new View('site/single.phtml');
		var_dump((new Product($pdo))->where(
			['id' => 12]
		));
//		var_dump((new Product($pdo))->insert(
//			['name' => 'Teste', 'price' => 19.99, 'amount' => 10, 'description' => 'Teste', 'slug' => 'slug']
//		));

	//	return $view->render();
	}
}