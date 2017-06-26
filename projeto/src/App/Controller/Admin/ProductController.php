<?php
namespace CodeExperts\App\Controller\Admin;

use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\App\Entity\Product;
use CodeExperts\App\Entity\ProductImage;
use CodeExperts\App\Entity\Category;
use CodeExperts\DB\Connection;
use CodeExperts\Upload\ProductUpload;

class ProductController extends BaseController
{
    private $product;

    public function __construct()
    {
        $this->product = new Product(Connection::getInstance($this->getConfig('database')));
    }

    public function index()
    {
        $view = new View(VIEWS_FOLDER . 'admin/products/index.phtml');

        $view->products = $this->product->getProductsAndImages();

        return $view->render();
    }

    public function new()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $images = $_FILES['images'];

            $data = $this->sanitizerString($data, Product::$rules);

            $product = $this->product;

            $product->name        = $data['name'];
            $product->description = $data['description'];
            $product->amount      = $data['amount'];
            $product->slug        = $data['slug'];
            $product->price       = $data['price'];
            $product->Category_id = $data['category_id'];

            if(!$saved = $product->insert()) {
                $this->addFlash('error', 'Ocorreu um erro durante a inserção do produto!');
                return $this->redirect('admin/product');
            }

            if(isset($images['name'][0])
                && $images['name'][0] != '') {

                $productUpload = (new ProductUpload())->setFile($images);

                $imgUploadeds  = $productUpload->upload();

                foreach($imgUploadeds as $img) {
                    $productImages = new ProductImage(Connection::getInstance($this->getConfig('database')));
                    $productImages->product_id = $saved;
                    $productImages->image      = $img;

                    $productImages->insert();
                }
            }

            $this->addFlash('success', 'Produto inserido com sucesso!');
            return $this->redirect('admin/product');
        }

        $view = new View(VIEWS_FOLDER . 'admin/products/save.phtml');

        $view->categories = (new Category(Connection::getInstance($this->getConfig('database'))))->findAll();

        return $view->render();
    }

    public function edit($id)
    {

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $images = $_FILES['images'];

            $product = $this->product;

            $product->id          = (int) $data['id'];
            $product->name        = $data['name'];
            $product->description = $data['description'];
            $product->amount      = $data['amount'];
            $product->slug        = $data['slug'];
            $product->price       = $data['price'];

            if(!$product->update()) {
                $this->addFlash('error', 'Ocorreu um erro durante a atualização do produto!');
                return $this->redirect('admin/product');
            }

            if(isset($images['name'][0])
                && $images['name'][0] != '') {

                $productUpload = (new ProductUpload())->setFile($images);

                $imgUploadeds  = $productUpload->upload();

                foreach($imgUploadeds as $img) {
                    $productImages = new ProductImage(Connection::getInstance($this->getConfig('database')));

                    $productImages->product_id = (int) $data['id'];
                    $productImages->image      = $img;

                    $productImages->insert();
                }
            }

            $this->addFlash('success', 'Produto editado com sucesso!');
            return $this->redirect('admin/product/edit/' . $data['id']);
        }

        if(!$id) {
            $this->addFlash('warning', 'ID Inválido!');
            return $this->redirect('admin/product');
        }

        $view = new View(VIEWS_FOLDER . 'admin/products/edit.phtml');

        $view->product = $this->product->getProductAndImagesById($id);

        $view->categories = (new Category(Connection::getInstance($this->getConfig('database'))))->findAll();

        return $view->render();
    }

    public function remove($id)
    {
        if(!$id) {
            $this->addFlash('warning', 'ID Inválido!');
            return $this->redirect('admin/product');
        }

        $product = $this->product;

        if(!$product->delete($id)) {
            $this->addFlash('error', 'Erro ao tentar remover um produto!');
            return $this->redirect('admin/product');
        }

        $this->addFlash('success', 'Produto removido com sucesso!');
        return $this->redirect('admin/product');

    }
}