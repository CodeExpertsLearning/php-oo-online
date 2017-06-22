<?php 

class User
{
    private $name;

    public function setName($name = null)
    {
        if(is_null($name)) {
            throw new \InvalidParameterException("Parametro inválido!!!!");
        }

        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}