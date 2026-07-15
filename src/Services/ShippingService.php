<?php

namespace Moe\Shipping\Services;

use Moe\Core\Base\BaseService;
use Moe\Shipping\Models\Courier;
use Moe\Shipping\Models\CourierZoneRate;
use Moe\Shipping\Models\Zone;

class ShippingService extends BaseService
{
    public function getCouriersForZone(int $zoneId): \Illuminate\Database\Eloquent\Collection
    {
        return Courier::where('is_active', true)
            ->whereHas('zoneRates', function ($q) use ($zoneId) {
                $q->where('zone_id', $zoneId)->where('is_active', true);
            })
            ->get();
    }

    public function getZoneByVillageCode(string $villageCode): ?Zone
    {
        return Zone::where('is_active', true)
            ->whereJsonContains('config.village_codes', $villageCode)
            ->first();
    }

    public function calculateShipping(int $zoneId, int $courierId, float $weight, float $orderTotal): float
    {
        $zone = Zone::findOrFail($zoneId);

        $freeMinimum = $zone->getFreeShippingMinimum();
        if ($freeMinimum && $orderTotal >= $freeMinimum) {
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

    public function getActiveZones(): \Illuminate\Database\Eloquent\Collection
    {
        return Zone::where('is_active', true)->get();
    }

    public function getActiveCouriers(): \Illuminate\Database\Eloquent\Collection
    {
        return Courier::where('is_active', true)->get();
    }
}
