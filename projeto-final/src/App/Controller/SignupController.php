<?php
namespace CodeExperts\App\Controller;

use CodeExperts\App\Entity\Product;
use CodeExperts\App\Entity\User;
use CodeExperts\App\Service\Authenticator;
use CodeExperts\DB\Connection;
use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\Tools\Session;

class SignupController extends BaseController
{
	public function index()
	{
		$view = new View(VIEWS_FOLDER . '/site/signup.phtml');

		return $view->render();
	}

	public function new()
	{
		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
			$data = $_POST;

			$password = new \CodeExperts\Tools\PasswordHash();
			$password = $password->setPassword( $data['password'] )->generate();

			$user = new User( Connection::getInstance( $this->getConfig( 'database' ) ) );

			$user->name     = $data['name'];
			$user->email    = $data['email'];
			$user->password = $password;

			if ( ! $user = $user->insert() ) {
				$this->addFlash( 'error', 'Erro ao cadastrar usuário!' );

				return $this->redirect( 'signup' );
			}

			(new Session())->sessionStart();
			$_SESSION['user']['id']   = $user;
			$_SESSION['user']['name'] = $data['name'];

			$this->addFlash( 'success', 'Escolha Uma Forma de Pagamento!' );

			return $this->redirect( 'payments' );
		}

		return $this->redirect( 'signup' );
	}

	public function login()
	{
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = $_POST;

			try {
				$user = new User(Connection::getInstance($this->getConfig('database')));

				$authenticator = new Authenticator($data, $user);

				$user = $authenticator->authenticate();

				$authenticator->setUserInSession(new Session(), $user);

				return $this->redirect('payments');

			} catch(\Exception $e) {
				$this->addFlash('warning', $e->getMessage());

				return $this->redirect('signup');
			}
		}
	}

}