<?php
class Printer
{
    public function exec()
    {
        return "Printing data";
    }
}

class HP
{
    public function exec()
    {
        return "HP Printing data";
    }
}

class Epson
{
    public function exec()
    {
        return "Epson Printing data";
    }
}

$printer = new Epson();

print $printer->exec();