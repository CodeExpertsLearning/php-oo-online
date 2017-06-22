<?php
abstract class People
{
    public function getName($name)
    {
        return $name;
    }

    abstract public function getProfession();
}

class Programmer extends People
{
    public function getProfession()
    {
        return "Profession";
    }
}

$people = new Programmer();
print $people->getName("Nanderson");