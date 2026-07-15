<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_couriers', function (Blueprint $table) {
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

        Schema::create('shipping_zones', function (Blueprint $table) {
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

        Schema::create('shipping_courier_zone_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('courier_id')->constrained('shipping_couriers')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('shipping_zones')->cascadeOnDelete();
            $table->string('estimated_delivery')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['courier_id', 'zone_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_courier_zone_rates');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_couriers');
    }
};
