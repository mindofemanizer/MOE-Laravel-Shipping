<?php

declare(strict_types=1);

namespace Moe\Shipping\Services;

use Illuminate\Database\Eloquent\Collection;
use Moe\Core\Base\BaseService;
use Moe\Shipping\Models\Courier;
use Moe\Shipping\Models\CourierZoneRate;
use Moe\Shipping\Models\Zone;

class ShippingService extends BaseService
{
    /**
     * Get couriers available for a zone.
     */
    public function getCouriersForZone(int $zoneId): Collection
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
     * Calculate shipping cost for a zone, courier, weight, and order total.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function calculateShipping(int $zoneId, int $courierId, float $weight, float $orderTotal): float
    {
        $zone = Zone::findOrFail($zoneId);

        $freeMinimum = $zone->getFreeShippingMinimum();
        if ($freeMinimum !== null && $orderTotal >= $freeMinimum) {
            return 0;
        }

        $rate = CourierZoneRate::where('courier_id', $courierId)
            ->where('zone_id', $zoneId)
            ->where('is_active', true)
            ->first();

        if (! $rate) {
            return $zone->getBaseRate();
        }

        return (float) $rate->rate;
    }

    /**
     * Get all active zones.
     */
    public function getActiveZones(): Collection
    {
        return Zone::where('is_active', true)->get();
    }

    /**
     * Get all active couriers.
     */
    public function getActiveCouriers(): Collection
    {
        return Courier::where('is_active', true)->get();
    }
}
