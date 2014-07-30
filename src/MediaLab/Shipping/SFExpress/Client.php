<?php

namespace MediaLab\Shipping\SFExpress;

use Guzzle\Http\Client as HttpClient;

class Client
{
    private $client;

    public function __construct(HttpClient $client = null)
    {
        if (null === $client) {
            $client = new HttpClient('http://www.sf-express.com');
        }

        $this->client = $client;
    }

    public function getRegions()
    {
        return $this->request('/sf-service-web/service/region/popularRegions?lang=en');
    }

    public function getSubregions($region)
    {
        return $this->request(sprintf(
            '/sf-service-web/service/region/%s/subRegions/origins?lang=en',
            $region
        ));
    }

    public function getRates($origin, $destination, $weight, $volume)
    {
        return $this->request(sprintf(
            '/sf-service-web/service/rate?origin=%s&dest=%s&weight=%s&volume=%s&lang=en&region=cn',
            $origin,
            $destination,
            $weight,
            $volume
        ));
    }

    protected function request($url)
    {
        return $this->client->get($url)->send()->json();
    }
}
