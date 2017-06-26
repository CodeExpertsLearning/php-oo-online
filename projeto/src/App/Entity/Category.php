<?php
namespace CodeExperts\App\Entity;

use CodeExperts\DB\Entity;

class Category extends Entity
{
    protected $table = 'categories';

    public static $rules = [
        'name'        => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
    ];
}
