<?php

class Car
{
    private $data = array();

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function returnData()
    {
        var_dump($this->data);
    }
}

$car = new Car();

$car->name = "Fusca";
$car->year = 2018;

$car->returnData();

print " " . $car->name;
