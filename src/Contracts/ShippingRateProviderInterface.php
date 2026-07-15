<?php

declare(strict_types=1);

namespace Moe\Shipping\Contracts;

interface ShippingRateProviderInterface
{
    public function getRates(string $origin, string $destination, float $weight): array;

    public function isConfigured(): bool;

    public function getProviderName(): string;
}
