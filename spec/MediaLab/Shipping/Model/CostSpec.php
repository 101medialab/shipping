<?php

namespace spec\MediaLab\Shipping\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CostSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MediaLab\Shipping\Model\Cost');
    }

    function it_implements_cost_interface()
    {
        $this->shouldHaveType('MediaLab\Shipping\Model\CostInterface');
    }
}
