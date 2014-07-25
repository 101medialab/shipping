<?php

namespace MediaLab\Shipping\Calculator;

use MediaLab\Shipping\Model\Cost;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;

/**
 * Package size = W + H + D cm.
 *
 * if package size within 60 cm - rate HK$33
 * if package size within 80 cm - rate HK$33
 * if package size within 100 cm - rate HK$50
 * if package size within 120 cm - rate HK$78
 * if package size within 140 cm - rate HK$105
 * if package size within 160 cm - rate HK$128
 */
class YamatoCalculator implements CalculatorInterface
{
    private $rates = [
        80  => 33,
        100 => 50,
        120 => 78,
        140 => 105,
        160 => 128,
    ];

    /**
     * @todo Check units.
     */
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
