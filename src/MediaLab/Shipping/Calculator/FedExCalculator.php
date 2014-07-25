<?php

namespace MediaLab\Shipping\Calculator;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;
use FedEx\RateService\Request;
use MediaLab\Shipping\FedEx\Model\Factory;
use MediaLab\Shipping\Model\Cost;

class FedExCalculator implements CalculatorInterface
{
    private $key;
    private $password;
    private $accountNumber;
    private $meterNumber;

    public function __construct($key, $password, $accountNumber, $meterNumber)
    {
        $this->key = $key;
        $this->password = $password;
        $this->accountNumber = $accountNumber;
        $this->meterNumber = $meterNumber;
    }

    public function calculate(AddressInterface $origin, AddressInterface $destination, ShippableInterface $shippable)
    {
        $result = (new Request())->getGetRatesReply(
            Factory::createRateRequest(
                $origin,
                $destination,
                $shippable,
                $this->key,
                $this->password,
                $this->accountNumber,
                $this->meterNumber
            )
        );

        if ('SUCCESS' !== $result->HighestSeverity) {
            $message = '';
            if (is_array($result->Notifications)) {
                foreach ($result->Notifications as $notification) {
                    $message .= $notification->Message;
                }
            } else {
                $message = $result->Notifications->Message;
            }

            throw new CalculatorException($message);
        }

        foreach ($result->RateReplyDetails as $rateReplyDetails) {
            if ('FEDEX_GROUND' !== $rateReplyDetails->ServiceType) {
                continue;
            }
        }

        $cost = $rateReplyDetails->RatedShipmentDetails->ShipmentRateDetail->TotalBaseCharge;

        return (new Cost())
            ->setCurrency($cost->Currency)
            ->setAmount($cost->Amount)
        ;
    }
}
