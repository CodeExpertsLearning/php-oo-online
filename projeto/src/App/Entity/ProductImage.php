<?php
namespace CodeExperts\App\Entity;

use CodeExperts\DB\Entity;

class ProductImage extends Entity
{
    protected $table = 'products_images';

    public function deleteImage($data)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id AND product_id = :product_id";

        $delete = $this->conn->prepare($sql);
        $delete->bindValue(":id", $data['image_id'], PDO::PARAM_INT);
        $delete->bindValue(":product_id", $data['product_id'], PDO::PARAM_INT);

        return $delete->execute();
    }
}
