<?php

namespace MediaLab\Shipping\Calculator;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;

interface CalculatorInterface
{
    public function calculate(AddressInterface $origin, AddressInterface $destination, ShippableInterface $shippable);
}
