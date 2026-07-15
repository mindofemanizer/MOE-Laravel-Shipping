<?php

namespace Moe\Shipping\Tests;

use Moe\Shipping\Models\Courier;
use Moe\Shipping\Models\CourierZoneRate;
use Moe\Shipping\Models\Zone;
use Moe\Shipping\Services\ShippingService;

class ShippingServiceTest extends TestCase
{
    private ShippingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ShippingService();
    }

    public function test_can_create_courier()
    {
        $courier = Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertEquals('JNE', $courier->name);
    }

    public function test_can_calculate_shipping_cost()
    {
        $courier = Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);
        $zone = Zone::create(['name' => 'Jakarta Pusat', 'region' => 'Jakarta', 'is_active' => true]);

        CourierZoneRate::create([
            'courier_id' => $courier->id,
            'zone_id' => $zone->id,
            'base_cost' => 10000,
            'cost_per_kg' => 5000,
            'estimated_days' => '1-2',
        ]);

        $cost = $this->service->calculateCost($courier->id, $zone->id, 2);

        $this->assertEquals(20000, $cost);
    }
}
