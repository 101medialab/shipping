<?php

namespace MediaLab\Shipping\FedEx\Model;

use FedEx\RateService\ComplexType\RequestedShipment;
use FedEx\RateService\ComplexType\Weight;
use FedEx\RateService\ComplexType\Dimensions;
use FedEx\RateService\ComplexType\Address;
use FedEx\RateService\ComplexType\Party;
use FedEx\RateService\ComplexType\Payor;
use FedEx\RateService\ComplexType\Payment;
use FedEx\RateService\ComplexType\RequestedPackageLineItem;
use FedEx\RateService\ComplexType\RateRequest;
use FedEx\RateService\ComplexType\WebAuthenticationDetail;
use FedEx\RateService\ComplexType\WebAuthenticationCredential;
use FedEx\RateService\ComplexType\ClientDetail;
use FedEx\RateService\ComplexType\TransactionDetail;
use FedEx\RateService\ComplexType\VersionId;
use FedEx\RateService\SimpleType\WeightUnits;
use FedEx\RateService\SimpleType\LinearUnits;
use FedEx\RateService\SimpleType\PaymentType;
use FedEx\RateService\SimpleType\DropoffType;
use FedEx\RateService\SimpleType\RateRequestType;
use Sylius\Component\Shipping\Model\ShippableInterface;
use Sylius\Component\Addressing\Model\AddressInterface;

class Factory
{
    public static function createRateRequest(
        AddressInterface $origin,
        AddressInterface $destination,
        ShippableInterface $shippable,
        $key,
        $password,
        $accountNumber,
        $meterNumber
    )
    {
        return (new RateRequest())
            ->setWebAuthenticationDetail((new WebAuthenticationDetail())
                ->setUserCredential((new WebAuthenticationCredential())
                    ->setKey($key)
                    ->setPassword($password)
                )
            )
            ->setClientDetail((new ClientDetail())
                ->setAccountNumber($accountNumber)
                ->setMeterNumber($meterNumber)
            )
            // ->setTransactionDetail((new TransactionDetail())
            //     ->setCustomerTransactionId(' *** Rate Available Services Request v10 using PHP ***')
            // )
            ->setVersion((new VersionId())
                ->setServiceId('crs')
                ->setMajor(14)
                ->setIntermediate(0)
                ->setMinor(0)
            )
            ->setReturnTransitAndCommit(true)
            ->setRequestedShipment(
                self::createRequestedShipment($origin, $destination, $shippable, $accountNumber)
            )
        ;
    }

    public static function createRequestedShipment(
        AddressInterface $origin,
        AddressInterface $destination,
        ShippableInterface $shippable,
        $accountNumber
    )
    {
        return (new RequestedShipment())
            ->setDropoffType(DropoffType::_REGULAR_PICKUP)
            ->setShipTimestamp(date('c'))
            ->setShipper(self::createParty($origin))
            ->setRecipient($recipient = self::createParty($destination))
            ->setShippingChargesPayment(self::createPayment($recipient))
            // ->setRateRequestTypes([
            //     new RateRequestType(RateRequestType::_ACCOUNT),
            //     new RateRequestType(RateRequestType::_LIST)
            // ])
            ->setRequestedPackageLineItems([
                self::createRequestedPackageLineItem($shippable)
            ])
            ->setPackageCount(1)
        ;
    }

    public static function createAddress(AddressInterface $address)
    {
        $result = (new Address())
            ->setStreetLines([$address->getStreet()])
            ->setCity($address->getCity())
            ->setPostalCode($address->getPostcode())
            ->setCountryCode($address->getCountry()->getIsoName())
        ;

        if (null === $province = $address->getProvince()) {
            return $result;
        }

        return $result->setStateOrProvinceCode(
            $province->getIsoName()
        );
    }

    public static function createParty(AddressInterface $address)
    {
        return (new Party())->setAddress(
            self::createAddress($address)
        );
    }

    public static function createPayor(Party $party)
    {
        return (new Payor())
            ->setResponsibleParty($party)
        ;
    }

    public static function createPayment(Party $party)
    {
        return (new Payment())
            ->setPaymentType(new PaymentType(PaymentType::_SENDER))
            ->setPayor(self::createPayor($party))
        ;
    }

    public static function createRequestedPackageLineItem(ShippableInterface $shippable)
    {
        return (new RequestedPackageLineItem())
            ->setWeight(self::createWeight($shippable))
            ->setDimensions(self::createDimensions($shippable))
            ->setGroupPackageCount(1)
        ;
    }

    public static function createWeight(ShippableInterface $shippable)
    {
        return (new Weight())
            ->setUnits(new WeightUnits(WeightUnits::_KG))
            ->setValue($shippable->getShippingWeight())
        ;
    }

    public static function createDimensions(ShippableInterface $shippable)
    {
        return (new Dimensions())
            ->setWidth($shippable->getShippingWidth())
            ->setHeight($shippable->getShippingHeight())
            ->setLength($shippable->getShippingDepth())
            ->setUnits(new LinearUnits(LinearUnits::_CM))
        ;
    }
}
