<?php

use Moe\Shipping\Models\Courier;
use Moe\Shipping\Models\CourierZoneRate;
use Moe\Shipping\Models\Zone;
use Moe\Shipping\Services\ShippingService;

beforeEach(function () {
    $this->service = new ShippingService();
});

it('can create courier', function () {
    $courier = Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);

    expect($courier)->toBeInstanceOf(Courier::class);
    expect($courier->name)->toEqual('JNE');
});

it('can create zone', function () {
    $zone = Zone::create([
        'name' => 'Jakarta Pusat',
        'slug' => 'jakpus',
        'type' => 'local',
        'config' => ['base_rate' => 10000, 'free_shipping_minimum' => 50000],
        'is_active' => true,
    ]);

    expect($zone)->toBeInstanceOf(Zone::class);
    expect($zone->getBaseRate())->toEqual(10000);
});

it('can calculate shipping', function () {
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

    expect($cost)->toEqual(15000);
});

it('applies free shipping', function () {
    $zone = Zone::create([
        'name' => 'Jakarta Pusat',
        'slug' => 'jakpus',
        'type' => 'local',
        'config' => ['base_rate' => 10000, 'free_shipping_minimum' => 50000],
        'is_active' => true,
    ]);

    $courier = Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);

    $cost = $this->service->calculateShipping($zone->id, $courier->id, 2, 100000);

    expect($cost)->toEqual(0);
});

it('gets active couriers', function () {
    Courier::create(['name' => 'JNE', 'code' => 'jne', 'is_active' => true]);
    Courier::create(['name' => 'TIKI', 'code' => 'tiki', 'is_active' => false]);

    $active = $this->service->getActiveCouriers();
    expect($active)->toHaveCount(1);
});
