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

    protected $chinaSubdivisions = [
        [
            'name'=>'Beijing',
            'sf_region_code'=>'A111000000',
            'iso_name'=>'CN-11'
        ],
        [
            'name'=>'Shaanxi',
            'sf_region_code'=>'A610700000',
            'iso_name'=>'CN-61'
        ],
        [
            'name'=>'Ningxia',
            'sf_region_code'=>'A640300000',
            'iso_name'=>'CN-64'
        ],
        [
            'name'=>'Shanghai',
            'sf_region_code'=>'A310107000',
            'iso_name'=>'CN-31'
        ],
        [
            'name'=>'Hebei',
            'sf_region_code'=>'A130100000',
            'iso_name'=>'CN-13'
        ],
        [
            'name'=>'Shanxi',
            'sf_region_code'=>'A141100000',
            'iso_name'=>'CN-14'
        ],
        [
            'name'=>'Tianjin',
            'sf_region_code'=>'A121000000',
            'iso_name'=>'CN-12'
        ],
        [
            'name'=>'Henan',
            'sf_region_code'=>'A410100000',
            'iso_name'=>'CN-41'
        ],
        [
            'name'=>'Jilin',
            'sf_region_code'=>'A220100000',
            'iso_name'=>'CN-22'
        ],
        [
            'name'=>'Heilongjiang',
            'sf_region_code'=>'A230100000',
            'iso_name'=>'CN-23'
        ],
        [
            'name'=>'Nei Mongol',
            'sf_region_code'=>'A150800000',
            'iso_name'=>'CN-15'
        ],
        [
            'name'=>'Chongqing',
            'sf_region_code'=>'A591100000',
            'iso_name'=>'CN-50'
        ],
        [
            'name'=>'Anhui',
            'sf_region_code'=>'A340100000',
            'iso_name'=>'CN-34'
        ],
        [
            'name'=>'Hunan',
            'sf_region_code'=>'A430100000',
            'iso_name'=>'CN-43'
        ],
        [
            'name'=>'Guangxi',
            'sf_region_code'=>'A451000000',
            'iso_name'=>'CN-45'
        ],
        [
            'name'=>'Jiangxi',
            'sf_region_code'=>'A360100000',
            'iso_name'=>'CN-36'
        ],
        [
            'name'=>'Guizhou',
            'sf_region_code'=>'A520100000',
            'iso_name'=>'CN-52'
        ],
        [
            'name'=>'Yunnan',
            'sf_region_code'=>'A530100000',
            'iso_name'=>'CN-53'
        ],
        [
            'name'=>'Hainan',
            'sf_region_code'=>'A460100000',
            'iso_name'=>'CN-46'
        ],
        [
            'name'=>'Gansu',
            'sf_region_code'=>'A620500000',
            'iso_name'=>'CN-62'
        ],
        [
            'name'=>'Qinghai',
            'sf_region_code'=>'A630100000',
            'iso_name'=>'CN-63'
        ],
        [
            'name'=>'Xinjiang',
            'sf_region_code'=>'A659001000',
            'iso_name'=>'CN-65'
        ],
        [
            'name'=>'Liaoning',
            'sf_region_code'=>'A210200000',
            'iso_name'=>'CN-21'
        ],
        [
            'name'=>'Jiangsu',
            'sf_region_code'=>'A320100000',
            'iso_name'=>'CN-32'
        ],
        [
            'name'=>'Zhejiang',
            'sf_region_code'=>'A330100000',
            'iso_name'=>'CN-33'
        ],
        [
            'name'=>'Fujian',
            'sf_region_code'=>'A350200000',
            'iso_name'=>'CN-35'
        ],
        [
            'name'=>'Shandong',
            'sf_region_code'=>'A370200000',
            'iso_name'=>'CN-37'
        ],
        [
            'name'=>'Guangdong',
            'sf_region_code'=>'A440100000',
            'iso_name'=>'CN-44'
        ],
        [
            'name'=>'Hubei',
            'sf_region_code'=>'A420100000',
            'iso_name'=>'CN-42'
        ],
        [
            'name'=>'Sichuan',
            'sf_region_code'=>'A510100000',
            'iso_name'=>'CN-51'
        ],
        [
            'name'=>'Tibet',
            'sf_region_code'=>'A542300000',
            'iso_name'=>'CN-54'
        ]
    ];

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
        switch ($address->getCountry()->getIsoName()) {
            case 'CN':
                return $this->getChinaSubdivisionRegionCodeByAddress($address);
            case 'TW':
                return 'A000710900';
            case 'HK':
                return 'A000813000';
            case 'MO':
                return 'A000822000';
        }

        throw new CalculatorException(sprintf(
            'Region %s, %s is not supported.',
            $address->getProvince()->getName(),
            $address->getCountry()->getName()
        ));
    }

    protected function getChinaSubdivisionRegionCodeByAddress(AddressInterface $address)
    {
        $isoName = $address->getProvince()->getIsoName();

        foreach ($this->chinaSubdivisions as $subdivision) {
            if ($subdivision['iso_name'] === $isoName) {
                return $subdivision['sf_region_code'];
            }
        }

        throw new CalculatorException(sprintf(
            'Region %s, %s is not supported.',
            $address->getProvince()->getName(),
            $address->getCountry()->getName()
        ));
    }
}
