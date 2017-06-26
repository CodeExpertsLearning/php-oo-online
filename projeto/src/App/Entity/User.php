<?php
namespace CodeExperts\App\Entity;

use CodeExperts\DB\Entity;

class User extends Entity
{
    protected $table = 'users';

    public static $rules = [
        'name'     => FILTER_SANITIZE_STRING,
        'email'    => FILTER_SANITIZE_STRING,
        'password' => FILTER_SANITIZE_STRING
    ];
}
