<?php

require_once 'bootstrap.php';

$source = (new Sylius\Component\Addressing\Model\Address())
    ->setCountry((new Sylius\Component\Addressing\Model\Country())
        ->setName('Hong Kong')
        ->addProvince($province = (new Sylius\Component\Addressing\Model\Province())
            ->setName('New Territories')
        )
    )
    ->setProvince($province)
;

$destination = (new Sylius\Component\Addressing\Model\Address())
    ->setCountry((new Sylius\Component\Addressing\Model\Country())
        ->setName('China')
        ->addProvince($province = (new Sylius\Component\Addressing\Model\Province())
            ->setName('Shenzhen City')
        )
    )
    ->setProvince($province)
;

$calculator = new MediaLab\Shipping\Calculator\SFExpressCalculator();
$cost = $calculator->calculate($source, $destination, $shippable);

var_export($cost);
