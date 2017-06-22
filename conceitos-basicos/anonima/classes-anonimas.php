<?php
class User
{
    private $anonynimous;

    public function __construct($anon)
    {
        $this->anonynimous = $anon;
    }

    public function getAnonymoys()
    {
        return $this->anonynimous;
    }
}

/**
 * Classes
 */
$anonymousClass = new class extends User{
    private $name;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
};

$user = new User($anonymousClass);

$user->getAnonymoys()->setName("Nanderson");

print $user->getAnonymoys()->getName();

