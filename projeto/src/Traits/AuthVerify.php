<?php
namespace CodeExperts\Traits;

use CodeExperts\Tools\Session;

trait AuthVerify
{
    public function isAuthenticated()
    {
        $session = (new Session)->sessionStart();

        return isset($_SESSION['user']);
    }
}