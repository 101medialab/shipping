<?php

require_once 'bootstrap.php';

$source = (new Sylius\Component\Addressing\Model\Address())
    ->setCountry((new Sylius\Component\Addressing\Model\Country())
        ->setName('Hong Kong')
        ->setIsoName('HK')
        ->addProvince($province = (new Sylius\Component\Addressing\Model\Province())
            ->setName('New Territories')
        )
    )
    ->setProvince($province)
;

$destination = (new Sylius\Component\Addressing\Model\Address())
    ->setCountry((new Sylius\Component\Addressing\Model\Country())
        ->setName('China')
        ->setIsoName('CN')
        ->addProvince($province = (new Sylius\Component\Addressing\Model\Province())
            ->setName('Guangdong')
            ->setIsoName('CN-44')
        )
    )
    ->setProvince($province)
;

$calculator = new MediaLab\Shipping\Calculator\SFExpressCalculator();
$cost = $calculator->calculate($source, $destination, $shippable);

var_dump($cost);
