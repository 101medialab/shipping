<?php

namespace MediaLab\Shipping\Model;

use DateTime;

class Estimation implements EstimationInterface
{
    private $carrier;
    private $serviceName;
    private $serviceCode;
    private $deliveryDate;
    private $cost;

    public function getCarrier()
    {
        return $this->carrier;
    }

    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    public function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;

        return $this;
    }

    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(DateTime $deliveryDate = null)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost(CostInterface $cost)
    {
        $this->cost = $cost;

        return $this;
    }
}
