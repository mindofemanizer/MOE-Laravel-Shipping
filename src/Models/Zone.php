<?php

declare(strict_types=1);

namespace Moe\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use SoftDeletes;

    protected $table;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('shipping.tables.zones', 'shipping_zones');
    }

    public function courierRates(): HasMany
    {
        return $this->hasMany(CourierZoneRate::class, 'zone_id');
    }

    public function couriers(): BelongsToMany
    {
        $table = config('shipping.tables.courier_zone_rates', 'shipping_courier_zone_rates');

        return $this->belongsToMany(Courier::class, $table)
            ->withPivot(['rate', 'estimated_delivery', 'is_active']);
    }

    public function getBaseRate(): float
    {
        return (float) ($this->config['base_rate'] ?? 0);
    }

    public function getFreeShippingMinimum(): ?float
    {
        return isset($this->config['free_shipping_minimum'])
            ? (float) $this->config['free_shipping_minimum']
            : null;
    }
}
