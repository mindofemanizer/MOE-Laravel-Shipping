<?php

namespace Moe\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class CourierZoneRate extends Model
{
    protected $table;

    protected $fillable = [
        'courier_id',
        'zone_id',
        'rate',
        'estimated_delivery',
        'is_active',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('shipping.tables.courier_zone_rates', 'shipping_courier_zone_rates');
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
