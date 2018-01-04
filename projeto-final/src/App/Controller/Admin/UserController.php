<?php
namespace CodeExperts\App\Controller\Admin;

use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\App\Entity\User;
use CodeExperts\DB\Connection;
use CodeExperts\Traits\AuthVerify;

class UserController extends BaseController
{
    use AuthVerify;

    private $user;

    public function __construct()
    {
        if(!$this->isAuthenticated()) {
            $this->addFlash('warning', 'Acesso não permitido!');
            return $this->redirect('auth/login');
        }

        $this->user = new User(Connection::getInstance($this->getConfig('database')));
    }

    public function index()
    {
        $view = new View(VIEWS_FOLDER . 'admin/users/index.phtml');

        $view->users = $this->user->findAll();

        return $view->render();
    }

    public function new()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            // $data = $this->sanitizerString($data, User::$rules);

            $password = new \CodeExperts\Tools\PasswordHash();
            $password = $password->setPassword($data['password'])->generate();

            $user = $this->user;

            $user->name  = $data['name'];
            $user->email = $data['email'];
            $user->password = $password;

            if(!$user->insert()) {
                $this->addFlash('error', 'Erro durante o processo de inserção de usuário!');
                return $this->redirect('admin/user');
            }

            $this->addFlash('success', 'Usuário inserido com sucesso!');
            return $this->redirect('admin/user');

        }

        $view = new View(VIEWS_FOLDER . 'admin/users/save.phtml');

        return $view->render();
    }

    public function edit($id)
    {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            $user = $this->user;



            $user->id    = (int) $data['id'];
	        $user->name  = $data['name'];
	        $user->email = $data['email'];

	        if($data['password']) {
		        $password = new \CodeExperts\Tools\PasswordHash();
		        $password = $password->setPassword($data['password'])->generate();

		        $user->password = $password;
	        }

            if(!$user->update()) {
                $this->addFlash('error', 'Erro durante o processo de atualização de um usuário!');
                return $this->redirect('admin/user');
            }

            $this->addFlash('success', 'Usuário editado com sucesso!');
            return $this->redirect('admin/user/edit/' . $data['id']);

        }

        if(!$id) {
            $this->addFlash('warning', 'ID Inválido!');
            return $this->redirect('admin/user');
        }

        $view = new View(VIEWS_FOLDER . 'admin/users/edit.phtml');

        $view->user = $this->user->find($id);

        return $view->render();
    }

    public function remove($id)
    {
        if(!$id) {
            $this->addFlash('warning', 'ID Inválido!');
            return $this->redirect('admin/user');
        }

        $user = $this->user;

        if(!$user->delete($id)) {
            $this->addFlash('error', 'Erro ao tentar deletar um usuário!');
            return $this->redirect('admin/category');
        }

        $this->addFlash('success', 'Usuário removido com sucesso!');
        return $this->redirect('admin/user');
    }
}