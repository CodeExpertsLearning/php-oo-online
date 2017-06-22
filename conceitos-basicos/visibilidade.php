<?php
class Animal
{
    private $name = "Animal";

    public function wakeUp()
    {
        return "Wake UP";
    }
}

class Lion extends Animal
{
    public function getName()
    {
        return parent::$name;
    }
}

$animal = new Animal();

/**
 * Visibilidade Publica
 * Eu posso acessar os metodos e atributos com este tipo
 * de visibilidade de fora da minha classe!
 */
//$animal->name = "Lion";
//print $animal->name;

/**
 * 3 Tipo de Visibilidade
 * Public | Protected | Private
 */

/**
 * Protected
 * Você não pode acessar de fora da classe, como os publics,
 * Mas você pode acessar a partir da classe e de suas classes
 * filhas
 */
$lion = new Lion();

print $lion->getName();

/**
 * Private
 * Só pode ser acessado pela própria classe
 */







