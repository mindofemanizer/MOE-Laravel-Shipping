<?php

namespace Moe\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;

    protected $table;

    protected $fillable = [
        'code',
        'name',
        'description',
        'logo',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('shipping.tables.couriers', 'shipping_couriers');
    }

    public function zoneRates(): HasMany
    {
        return $this->hasMany(CourierZoneRate::class, 'courier_id');
    }

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'shipping_courier_zone_rates')
            ->withPivot(['estimated_delivery', 'is_active']);
    }
}
