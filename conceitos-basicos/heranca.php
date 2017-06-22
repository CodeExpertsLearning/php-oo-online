<?php
class Car
{
    public $color = "red";

    public $year = 2017;

    public $name;

    public function run()
    {
        return "Car is running!";
    }

    public function getSpecifications()
    {
        return $this->name . " - Year: " . $this->year . " and Color: " . $this->color;
    }
}

class Fusca extends Car
{
    public $name = "Fusca";

    public function run()
    {
        return "Fusca is running";
    }
}

$fusca = new Fusca();

print $fusca->run();