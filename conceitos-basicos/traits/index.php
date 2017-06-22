<?php

trait MyTrait {
    public function showName()
    {
        print __CLASS__ . ' - ' . __METHOD__;
    }
}
trait MySecondTrait {
    public function showName()
    {
        print __CLASS__ . ' - ' . __METHOD__;
    }
}

class User
{
    use MyTrait, MySecondTrait {
        MySecondTrait::showName insteadof MyTrait;
    }
}

class Product
{
    use MyTrait;
}

$user = new User();

$user->showName();
print '<hr>';