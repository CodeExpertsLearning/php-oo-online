<?php
namespace CodeExperts\App\Entity;

use CodeExperts\DB\Entity;

class Product extends Entity
{
    protected $table = 'products';

    public static $rules = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
        'price'       => FILTER_SANITIZE_STRING,
        'slug'        => FILTER_SANITIZE_STRING,
        'amount'	  => FILTER_SANITIZE_STRING,
        'category_id' => FILTER_SANITIZE_NUMBER_INT
    ];

    public function getProductsAndImages()
    {
        $sql = "SELECT 
				p.*,
				c.name as category,
				(SELECT pi.image FROM products_images pi WHERE p.id = pi.product_id LIMIT 1) AS image
			FROM 
				products p 
			LEFT JOIN 
			   categories c 
			ON
			   p.category_id = c.id
		    ORDER BY id DESC";

        $get = $this->conn->query($sql);

        return $get->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductAndImagesById($id)
    {
        $sql = "SELECT 
					p.id, p.name, p.category_id, p.price, p.description, p.slug, p.amount, pi.id as image_id, pi.image
				FROM products p
				LEFT JOIN 
				    products_images pi
				ON 
				    pi.product_id = p.id
				WHERE p.id = :id";

        $get = $this->conn->prepare($sql);
        $get->bindValue(":id", $id, \PDO::PARAM_INT);
        $get->execute();

        if(!$product = $get->fetchAll(\PDO::FETCH_ASSOC)) {
            return false;
        }

        $return = [];

        foreach($product as $p) {
            $return['id'] = $p['id'];
            $return['name'] = $p['name'];
            $return['category_id'] = $p['category_id'];
            $return['price'] = $p['price'];
            $return['description'] = $p['description'];
            $return['slug'] = $p['slug'];
            $return['amount'] = $p['amount'];

            if($p['image'] && $p['image_id']) {
                $return['images'][] = array('id' => $p['image_id'], 'image' => $p['image']);
            }
        }

        return $return;
    }
}
