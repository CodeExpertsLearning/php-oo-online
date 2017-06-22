<?php
require __DIR__ . '/InvalidParameterException.php';
require __DIR__ . '/User.php';

try {
    $user = new User();

    $user->setName();

    print $user->getName();

} catch(\InvalidParameterException $e) {
    print $e->getMessage();
} finally {
  print "Finally";
}