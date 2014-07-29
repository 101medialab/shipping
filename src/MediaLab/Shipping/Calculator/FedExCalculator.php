<?php

namespace MediaLab\Shipping\Calculator;

use Sylius\Component\Addressing\Model\AddressInterface;
use Sylius\Component\Shipping\Model\ShippableInterface;
use FedEx\RateService\Request;
use ICanBoogie\Inflector;
use DateTime;
use MediaLab\Shipping\FedEx\Model\Factory;
use MediaLab\Shipping\Model\Estimation;
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

        $estimations = [];
        foreach ($result->RateReplyDetails as $rateReplyDetails) {
            foreach ($rateReplyDetails->RatedShipmentDetails as $rateShipmentDetails) {
                if (isset($rateShipmentDetails->TotalBaseCharge)) {
                    $charge = $rateShipmentDetails->TotalBaseCharge;
                } elseif (isset($rateShipmentDetails->ShipmentRateDetail)) {
                    $charge = $rateShipmentDetails->ShipmentRateDetail->TotalBaseCharge;
                } elseif (isset($rateShipmentDetails->PackageRateDetail)) {
                    $charge = $rateShipmentDetails->PackageRateDetail->BaseCharge;
                } else {
                    throw new CalculatorException('Failed to extract shipping cost.');
                }

                $estimations[] = (new Estimation())
                    ->setCarrier('FedEx')
                    ->setServiceName(Inflector::get()->humanize($rateReplyDetails->ServiceType))
                    ->setServiceCode($rateReplyDetails->ServiceType)
                    ->setDeliveryDate(isset($rateReplyDetails->DeliveryTimestamp) ? new DateTime($rateReplyDetails->DeliveryTimestamp) : null)
                    ->setCost((new Cost())
                        ->setCurrency($charge->Currency)
                        ->setAmount($charge->Amount)
                    )
                ;

            }
        }

        return $estimations;
    }
}
