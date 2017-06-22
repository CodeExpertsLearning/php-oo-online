<?php

$date = $_GET['date'];

$date = DateTime::createFromFormat("d/m/Y", $date);

print $date->format("Y-m-d");