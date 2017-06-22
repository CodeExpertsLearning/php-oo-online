<?php

class Car
{
    public $color;

    public $year;

    public function run()
    {
        return "Car is running!";
    }

    public function getSpecifications()
    {
        return "Year: " . $this->year . " and Color: " . $this->color;
    }
}

$car = new Car();

$car->color = "Vermelha";
$car->year  = 2017;

 print $car->run();
 print '<hr>';
 print $car->getSpecifications();