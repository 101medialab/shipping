<?php

namespace spec\MediaLab\Shipping\Calculator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FedExCalculatorSpec extends ObjectBehavior
{
    function let()
    {
        $key = $password = $accountNumber = $meterNumber = null;

        $this->beConstructedWith($key, $password, $accountNumber, $meterNumber);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MediaLab\Shipping\Calculator\FedExCalculator');
    }

    function it_implements_calculator_interface()
    {
        $this->shouldHaveType('MediaLab\Shipping\Calculator\CalculatorInterface');
    }
}
