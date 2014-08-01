<?php

require_once 'bootstrap.php';

$calculator = new MediaLab\Shipping\Calculator\YamatoCalculator();
$cost = $calculator->calculate($source, $destination, $shippable);

var_export($cost);
