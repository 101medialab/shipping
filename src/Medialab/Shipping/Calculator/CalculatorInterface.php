<?php

namespace Medialab\Shipping\Calculator;

use Sylius\Component\Shipping\Model\ShippableInterface;

interface CalculatorInterface
{
    /**
     * @todo Add origin and destination address as arguments.
     */
    public function calculate(ShippableInterface $shippable);
}
