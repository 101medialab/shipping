<?php

namespace spec\MediaLab\Shipping\SFExpress;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class ClientSpec extends ObjectBehavior
{
    function let(Client $client, Request $request, Response $response)
    {
        $client->get(Argument::type('string'))->willReturn($request);
        $request->send()->willReturn($response);

        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('MediaLab\Shipping\SFExpress\Client');
    }

    function it_gets_regions(Client $client, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn([
            'code'         => 'A340100000',
            'name'         => 'Hefei City',
            'provinceName' => 'Anhui Province',
        ]);

        $regions = $this->getRegions()->shouldReturn([
            'code'         => 'A340100000',
            'name'         => 'Hefei City',
            'provinceName' => 'Anhui Province',
        ]);
    }

    function it_gets_subregions(Client $client, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn([
            'code'       => 'A000060000',
            'parentCode' => 'A000000000',
            'name'       => 'Malaysia',
        ]);

        $regions = $this->getSubregions('A000000000')->shouldReturn([
            'code'       => 'A000060000',
            'parentCode' => 'A000000000',
            'name'       => 'Malaysia',
        ]);
    }

    function it_gets_rates(Client $client, Response $response)
    {
        $response->json()->shouldBeCalled()->willReturn([
            'currencyName' => 'HKD',
            'deliverTime'  => null,
        ]);

        $regions = $this->getRates('A000812000', 'A440300000', 2, 200000)->shouldReturn([
            'currencyName' => 'HKD',
            'deliverTime'  => null,
        ]);
    }
}
