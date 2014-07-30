<?php

require_once 'bootstrap.php';

$calculator = new MediaLab\Shipping\Calculator\SFExpressCalculator();
$cost = $calculator->calculate($source, $destination, $shippable);

var_export($cost);
