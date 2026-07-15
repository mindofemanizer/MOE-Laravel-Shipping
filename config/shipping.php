<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Model Bindings
    |--------------------------------------------------------------------------
    */
    'models' => [

        'courier' => Moe\Shipping\Models\Courier::class,

        'zone' => Moe\Shipping\Models\Zone::class,

        'courier_zone_rate' => Moe\Shipping\Models\CourierZoneRate::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    */
    'tables' => [

        'couriers' => 'shipping_couriers',

        'zones' => 'shipping_zones',

        'courier_zone_rates' => 'shipping_courier_zone_rates',

    ],

    /*
    |--------------------------------------------------------------------------
    | Zone Types
    |--------------------------------------------------------------------------
    */
    'zone_types' => [

        'local' => 'Lokal',
        'internal' => 'Dalam Kota',
        'intercity' => ' Antar Kota',
        'interisland' => 'Antar Pulau',
        'national' => 'Nasional',

    ],

];
