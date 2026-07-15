<?php

declare(strict_types=1);

namespace Moe\Shipping\Contracts;

interface ShippableInterface
{
    public function getShippingOrigin(): string;
    public function getShippingWeight(): float;
    public function getShippingCost(string $courierCode, string $service): float;
}
