<?php

class People
{
    public function __toString()
    {
        return "Printing Object";
    }
}

$people = new People();

print $people;