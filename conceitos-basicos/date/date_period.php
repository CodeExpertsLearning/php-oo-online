<?php

$start = new DateTime("2016-03-26");
$end   = new DateTime("2016-04-01");
$end->format("+1day");

$intervalo = new DateInterval("P1W");

$period = new DatePeriod($start, $intervalo, $end);

foreach ($period as $p) {
    print $p->format("d/m/Y") . "</br>";
}