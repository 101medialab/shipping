<?php

namespace spec\Medialab\Shipping\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CostSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Medialab\Shipping\Model\Cost');
    }

    function it_implements_cost_interface()
    {
        $this->shouldHaveType('Medialab\Shipping\Model\CostInterface');
    }
}
