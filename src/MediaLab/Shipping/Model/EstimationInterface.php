<?php

namespace MediaLab\Shipping\Model;

use DateTime;

interface EstimationInterface
{
    public function getCarrier();
    public function setCarrier($carrier);
    public function getServiceName();
    public function setServiceName($serviceName);
    public function getServiceCode();
    public function setServiceCode($serviceCode);
    public function getDeliveryDate();
    public function setDeliveryDate(DateTime $deliveryDate = null);
    public function getCost();
    public function setCost(CostInterface $cost);
}
