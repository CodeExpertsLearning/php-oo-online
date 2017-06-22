<?php

$date = new DateTime();

print $date->format("d/m/Y");

$date->setDate(2016, 02, 02);
print '<br>';
print $date->format("d/m/Y");


