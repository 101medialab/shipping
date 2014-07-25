<?php

require_once 'bootstrap.php';

$calculator = new MediaLab\Shipping\Calculator\FedExCalculator($fedexKey, $fedexPassword, $fedexAccountNumber, $fedexMeterNumber);
$cost = $calculator->calculate($source, $destination, $shippable);

var_export($cost);
