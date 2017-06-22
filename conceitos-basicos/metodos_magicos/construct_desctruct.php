<?php

class People
{
    public function __construct()
    {
        print "Construct is running";
    }


    public function __destruct()
    {
        print "Destruct is running";
    }
}

$people = new People();