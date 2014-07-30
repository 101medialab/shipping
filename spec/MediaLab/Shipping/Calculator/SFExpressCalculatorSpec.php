<?php

namespace spec\MediaLab\Shipping\Calculator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use MediaLab\Shipping\SFExpress\Client;

class SFExpressCalculatorSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MediaLab\Shipping\Calculator\SFExpressCalculator');
    }

    function it_implements_calculator_interface()
    {
        $this->shouldHaveType('MediaLab\Shipping\Calculator\CalculatorInterface');
    }
}
