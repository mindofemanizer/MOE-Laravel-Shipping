<?php

namespace Moe\Shipping\Contracts;

interface ShippableInterface
{
    public function getShippingOrigin(): string;
    public function getShippingWeight(): float;
    public function getShippingCost(string $courierCode, string $service): float;
}
