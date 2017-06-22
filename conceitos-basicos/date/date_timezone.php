<?php

$date = new DateTime("now", new DateTimezone("Asia/Tokyo"));

print $date->format("d/m/Y H:i:s");