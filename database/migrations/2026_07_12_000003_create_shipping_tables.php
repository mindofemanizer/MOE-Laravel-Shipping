<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $couriersTable = config('shipping.tables.couriers', 'shipping_couriers');
        $zonesTable = config('shipping.tables.zones', 'shipping_zones');
        $ratesTable = config('shipping.tables.courier_zone_rates', 'shipping_courier_zone_rates');

        Schema::create($couriersTable, function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($zonesTable, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type', 50);
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($ratesTable, function (Blueprint $table) use ($couriersTable, $zonesTable) {
            $table->id();
            $table->foreignId('courier_id')->constrained($couriersTable)->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained($zonesTable)->cascadeOnDelete();
            $table->decimal('rate', 15, 2)->default(0);
            $table->string('estimated_delivery')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['courier_id', 'zone_id']);
        });
    }

    public function down(): void
    {
        $ratesTable = config('shipping.tables.courier_zone_rates', 'shipping_courier_zone_rates');
        $zonesTable = config('shipping.tables.zones', 'shipping_zones');
        $couriersTable = config('shipping.tables.couriers', 'shipping_couriers');

        Schema::dropIfExists($ratesTable);
        Schema::dropIfExists($zonesTable);
        Schema::dropIfExists($couriersTable);
    }
};
