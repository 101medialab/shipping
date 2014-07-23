<?php

namespace spec\Medialab\Shipping\Calculator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;

class YamatoCalculatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Medialab\Shipping\Calculator\YamatoCalculator');
    }

    function it_implements_calculator_interface()
    {
        $this->shouldHaveType('Medialab\Shipping\Calculator\CalculatorInterface');
    }

    function it_calculates_shipping_cost_based_on_size(AddressInterface $origin, AddressInterface $destination, ShippableInterface $shippable)
    {
        $shippable->getShippingWidth()->shouldBeCalled()->willReturn(5);
        $shippable->getShippingHeight()->shouldBeCalled()->willReturn(100);
        $shippable->getShippingDepth()->shouldBeCalled()->willReturn(10);

        $cost = $this->calculate($origin, $destination, $shippable);
        $cost->shouldHaveType('Medialab\Shipping\Model\CostInterface');
        $cost->getCurrency()->shouldReturn('HKD');
        $cost->getAmount()->shouldReturn(78);
    }

    function it_throws_exception_if_unable_to_calculate_shipping_cost(AddressInterface $origin, AddressInterface $destination, ShippableInterface $shippable)
    {
        $shippable->getShippingWidth()->shouldBeCalled()->willReturn(5);
        $shippable->getShippingHeight()->shouldBeCalled()->willReturn(200);
        $shippable->getShippingDepth()->shouldBeCalled()->willReturn(10);

        $this->shouldThrow('Medialab\Shipping\Calculator\CalculatorException')->duringCalculate($origin, $destination, $shippable);
    }
}
