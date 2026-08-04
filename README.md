# MOE-Laravel-Shipping

Shipping module for MOE ecosystem â€” Courier, Zone, Rate.

## Installation

```bash
composer require moe/laravel-shipping:dev-main
php artisan vendor:publish --provider="Moe\Shipping\ShippingServiceProvider" --tag="shipping-config"
php artisan vendor:publish --provider="Moe\Shipping\ShippingServiceProvider" --tag="shipping-migrations"
php artisan migrate
```

## What's Included

### Models

| Model | Table | Description |
|-------|-------|-------------|
| `Courier` | `shipping_couriers` | Shipping courier |
| `Zone` | `shipping_zones` | Shipping zone with config |
| `CourierZoneRate` | `shipping_courier_zone_rates` | Courier-Zone mapping |

### Services

| Service | Description |
|---------|-------------|
| `ShippingService` | Zone lookup, cost calculation |

### Contracts

| Contract | Description |
|----------|-------------|
| `ShippingRateProviderInterface` | Interface for rate providers |
| `ShippableInterface` | Interface for shippable models |

## Usage

### Get Couriers for Zone

```php
use Moe\Shipping\Services\ShippingService;

$shippingService = app(ShippingService::class);
$couriers = $shippingService->getCouriersForZone($zoneId);
```

### Calculate Shipping Cost

```php
$cost = $shippingService->calculateShipping($zoneId, $courierId, $weight, $orderTotal);
```

### Get Zone by Village Code

```php
$zone = $shippingService->getZoneByVillageCode('3201010001');
```

## Config

```php
// config/shipping.php
return [
    'tables' => [
        'couriers' => 'shipping_couriers',
        'zones' => 'shipping_zones',
        'courier_zone_rates' => 'shipping_courier_zone_rates',
    ],
];
```

## Requirements

- PHP ^8.2
- Laravel ^12.0|^13.0
- `moe/laravel-core`

## License

MIT
