<?php

namespace spec\MediaLab\Shipping\Calculator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FedExCalculatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MediaLab\Shipping\Calculator\FedExCalculator');
    }

    function it_implements_calculator_interface()
    {
        $this->shouldHaveType('MediaLab\Shipping\Calculator\CalculatorInterface');
    }
}
