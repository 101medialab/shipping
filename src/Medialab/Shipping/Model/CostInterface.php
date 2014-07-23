<?php

namespace Medialab\Shipping\Model;

interface CostInterface
{
    public function getCurrency();
    public function setCurrency($currency);
    public function getAmount();
    public function setAmount($amount);
}
