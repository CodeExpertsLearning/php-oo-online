<?php

$date = new DateTime();



$add = new DateInterval("P10YT1H2M");

$date->add($add);

print $date->format("d/m/Y H:i:s");

print '<hr>';

$sub = new DateInterval("P5Y");

print $date->sub($sub)->format("d/m/Y H:i:s");
