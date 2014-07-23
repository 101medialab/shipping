<?php

namespace Medialab\Shipping\Calculator;

use Medialab\Shipping\Model\Cost;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;

class YamatoCalculator implements CalculatorInterface
{
    private $rates = [
        80  => 33,
        100 => 50,
        120 => 78,
        140 => 105,
        160 => 128,
    ];

    public function calculate(AddressInterface $origin, AddressInterface $destination, ShippableInterface $shippable)
    {
        $estimatedSize = $shippable->getShippingWidth() + $shippable->getShippingHeight() + $shippable->getShippingDepth();

        foreach ($this->rates as $size => $rate) {
            if ($estimatedSize <= $size) {
                return (new Cost())
                    ->setCurrency('HKD')
                    ->setAmount($rate)
                ;
            }
        }

        throw new CalculatorException(sprintf(
            'Unable to estimate rate for shippable of size %d. Maximal shippable size supported is %d.',
            $estimatedSize,
            $size
        ));
    }
}
