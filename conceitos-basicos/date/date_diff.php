<?php

$date1 = new DateTime("2017-03-26");
$date2 = new DateTime("2017-04-11");

$intervalo = $date2->diff($date1);

print $intervalo->format("%R%a");