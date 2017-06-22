<?php

interface Car
{
    public function run();
}

class Fusca implements Car
{
    public function run()
    {
        return "Fusca is running";
    }
}

$fusca = new Fusca();
print $fusca->run();