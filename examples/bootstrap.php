<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once 'config.php';

$faker = Faker\Factory::create();

// $source = (new Sylius\Component\Addressing\Model\Address())
//     ->setStreet($faker->streetAddress)
//     ->setCity($faker->city)
//     ->setPostcode($faker->postcode)
//     ->setCountry((new Sylius\Component\Addressing\Model\Country())
//         ->setIsoName($faker->countryCode)
//     )
// ;

// $destination = (new Sylius\Component\Addressing\Model\Address())
//     ->setStreet($faker->streetAddress)
//     ->setCity($faker->city)
//     ->setPostcode($faker->postcode)
//     ->setCountry((new Sylius\Component\Addressing\Model\Country())
//         ->setIsoName($faker->countryCode)
//     )
// ;

$source = (new Sylius\Component\Addressing\Model\Address())
    ->setStreet('10 Fed Ex Pkwy')
    ->setCity('Memphis')
    ->setPostcode(38115)
    ->setCountry((new Sylius\Component\Addressing\Model\Country())
        ->setIsoName('US')
        ->addProvince($province = (new Sylius\Component\Addressing\Model\Province())
            ->setIsoName('TN')
        )
    )
    ->setProvince($province)
;

$destination = (new Sylius\Component\Addressing\Model\Address())
    ->setStreet('13450 Farmcrest Ct')
    ->setCity('Herndon')
    ->setPostcode(20171)
    ->setCountry((new Sylius\Component\Addressing\Model\Country())
        ->setIsoName('US')
        ->addProvince($province = (new Sylius\Component\Addressing\Model\Province())
            ->setIsoName('VA')
        )
    )
    ->setProvince($province)
;

$shippable = (new Sylius\Component\Core\Model\ProductVariant())
    ->setWeight($faker->numberBetween(1, 10))
    ->setWidth($faker->numberBetween(1, 10))
    ->setHeight($faker->numberBetween(1, 100))
    ->setDepth($faker->numberBetween(1, 10))
;
