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

    public function test_can_create_zone()
    {
        $zone = Zone::create([
            'name' => 'Jakarta Pusat',
            'slug' => 'jakpus',
            'type' => 'local',
            'config' => ['base_rate' => 10000, 'free_shipping_minimum' => 50000],
            'is_active' => true,
        ]);

        $this->assertInstanceOf(Zone::class, $zone);
        $this->assertEquals(10000, $zone->getBaseRate());
    }

    public function test_can_calculate_shipping()
    {
        $zone = Zone::create([
            'name' => 'Jakarta Pusat',
            'slug' => 'jakpus',
            'type' => 'local',
            'config' => ['base_rate' => 10000, 'free_shipping_minimum' => 50000],
            'is_active' => true,
        ]);

        $courier = Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);

        CourierZoneRate::create([
            'courier_id' => $courier->id,
            'zone_id' => $zone->id,
            'rate' => 15000,
            'is_active' => true,
        ]);

        $cost = $this->service->calculateShipping($zone->id, $courier->id, 2, 25000);

        $this->assertEquals(15000, $cost);
    }

    public function test_free_shipping_applied()
    {
        $zone = Zone::create([
            'name' => 'Jakarta Pusat',
            'slug' => 'jakpus',
            'type' => 'local',
            'config' => ['base_rate' => 10000, 'free_shipping_minimum' => 50000],
            'is_active' => true,
        ]);

        $courier = Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);

        $cost = $this->service->calculateShipping($zone->id, $courier->id, 2, 100000);

        $this->assertEquals(0, $cost);
    }

    public function test_get_active_couriers()
    {
        Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);
        Courier::create(['name' => 'TIKI', 'code' => 'tiki', 'is_active' => false]);

        $active = $this->service->getActiveCouriers();
        $this->assertCount(1, $active);
    }
}
