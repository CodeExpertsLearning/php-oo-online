<?php
namespace CodeExperts\App\Controller\Admin;

use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\App\Entity\Category;
use CodeExperts\DB\Connection;
use CodeExperts\Traits\AuthVerify;

class CategoryController extends BaseController
{
    use AuthVerify;

    private $category;

    public function __construct()
    {
        if(!$this->isAuthenticated()) {
            $this->addFlash('warning', 'Acesso não permitido!');
            return $this->redirect('auth/login');
        }

        $this->category = new Category(Connection::getInstance($this->getConfig('database')));
    }

    public function index()
    {
        $view = new View(VIEWS_FOLDER . 'admin/categories/index.phtml');

        $view->categories = $this->category->findAll();

        return $view->render();
    }

    public function new()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            $data = $this->sanitizerString($data, category::$rules);

            $category = $this->category;

            $category->name        = $data['name'];
            $category->description = $data['description'];


            if(!$category->insert()) {
                $this->addFlash('success', 'Erro durante o processo de inserção de categoria!');
                return $this->redirect('admin/user');
            }

            $this->addFlash('success', 'Categoria inserida com sucesso!');
            return $this->redirect('admin/category');

        }

        $view = new View(VIEWS_FOLDER . 'admin/categories/save.phtml');

        $view->categories = (new Category(Connection::getInstance($this->getConfig('database'))))->findAll();

        return $view->render();
    }

    public function edit($id)
    {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            $category = $this->category;

            $category->id          = (int) $data['id'];
            $category->name        = $data['name'];
            $category->description = $data['description'];

            if(!$category->update()) {
                $this->addFlash('error', 'Erro durante o processo de atualização de categoria!');
                return $this->redirect('admin/user');
            }

            $this->addFlash('success', 'Categoria editada com sucesso!');
            return $this->redirect('admin/user');
        }

        if(!$id) {
            $this->addFlash('warning', 'ID inválido!');
            return $this->redirect('admin/user');
        }

        $view = new View(VIEWS_FOLDER . 'admin/categories/edit.phtml');

        $view->category = $this->category->find($id);

        return $view->render();
    }

    public function remove($id)
    {
        if(!$id) {
            $this->addFlash('warning', 'ID Inválido!');
            return $this->redirect('admin/category');
        }

        $category = $this->category;

        if(!$category->delete($id)) {
            $this->addFlash('error', 'Erro ao tentar deletar uma categoria!');
            return $this->redirect('admin/category');
        }

        $this->addFlash('success', 'Categoria removida com sucesso!');
        return $this->redirect('admin/user');
    }
}