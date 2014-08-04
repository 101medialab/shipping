<?php

namespace MediaLab\Shipping\Calculator;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;
use DateTime;
use MediaLab\Shipping\Model\Estimation;
use MediaLab\Shipping\Model\Cost;
use MediaLab\Shipping\SFExpress\Client;

class SFExpressCalculator implements CalculatorInterface
{
    private $client;

    public function __construct(Client $client = null)
    {
        if (null === $client) {
            $client = new Client();
        }

        $this->client = $client;
    }

    public function calculate(AddressInterface $origin, AddressInterface $destination, ShippableInterface $shippable)
    {
        $estimations = [];

        foreach($this->client->getRates(
            $this->getRegionCode($origin),
            $this->getRegionCode($destination),
            $shippable->getShippingWeight(),
            $shippable->getShippingWidth() * $shippable->getShippingHeight() * $shippable->getShippingDepth()
        ) as $rate) {
            $estimations[] = (new Estimation())
                ->setCarrier('S.F. Express')
                ->setServiceName($this->getServiceName($rate))
                ->setServiceCode($rate['limitTypeName'].' '.$rate['cargoTypeCode'])
                ->setDeliveryDate(null === $rate['deliverTime'] ? null : new DateTime($rate['deliverTime']))
                ->setCost((new Cost())
                    ->setCurrency($rate['currencyName'])
                    ->setAmount($rate['freight'])
                )
            ;
        }

        return $estimations;
    }
    
    private function getServiceName($rate)
    {
        $limitType = ucwords($rate['limitTypeName']);
        $cargoType = '';
        
        switch ($rate['cargoTypeCode']) {
            case 'C201': 
                $cargoType = ' (Package)';
            break;
            case 'C1':
                $cargoType = ' (File)';
            break;
        }
        
        return "$limitType$cargoType";
    }

    private function getRegionCode(AddressInterface $address)
    {
        $provinceName = null === $address->getProvince() ? null : $address->getProvince()->getName();

        foreach ($this->getRegions() as $region) {
            if ($provinceName === $region['name']) {
                return $region['code'];
            }
        }

        throw new CalculatorException(sprintf(
            'Region %s, %s is not supported.',
            $provinceName,
            $address->getCountry()->getName()
        ));
    }

    private function getRegions()
    {
        return unserialize(file_get_contents(__DIR__.'../../../../../data/regions.data'));
    }
}
