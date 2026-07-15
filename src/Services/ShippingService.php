<?php

namespace Moe\Shipping\Services;

use Moe\Core\Base\BaseService;
use Moe\Shipping\Contracts\ShippingRateProviderInterface;
use Moe\Shipping\Models\Courier;
use Moe\Shipping\Models\Zone;

class ShippingService extends BaseService
{
    /**
     * Get available couriers for a zone.
     */
    public function getCouriersForZone(int $zoneId): \Illuminate\Database\Eloquent\Collection
    {
        return Courier::where('is_active', true)
            ->whereHas('zoneRates', function ($q) use ($zoneId) {
                $q->where('zone_id', $zoneId)->where('is_active', true);
            })
            ->get();
    }

    /**
     * Get zone by village code.
     */
    public function getZoneByVillageCode(string $villageCode): ?Zone
    {
        return Zone::where('is_active', true)
            ->whereJsonContains('config.village_codes', $villageCode)
            ->first();
    }

    /**
     * Calculate shipping cost.
     */
    public function calculateShipping(int $zoneId, int $courierId, float $weight, float $orderTotal): float
    {
        $zone = Zone::findOrFail($zoneId);
        $baseRate = $zone->getBaseRate();

        $freeMinimum = $zone->getFreeShippingMinimum();
        if ($freeMinimum && $orderTotal >= $freeMinimum) {
            return 0;
        }

        return $baseRate;
    }

    /**
     * Get all active zones.
     */
    public function getActiveZones(): \Illuminate\Database\Eloquent\Collection
    {
        return Zone::where('is_active', true)->get();
    }

    /**
     * Get all active couriers.
     */
    public function getActiveCouriers(): \Illuminate\Database\Eloquent\Collection
    {
        return Courier::where('is_active', true)->get();
    }
}
