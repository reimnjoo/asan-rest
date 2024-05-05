<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scrapdatas', function (Blueprint $table) {
            $table->uuid('scrap_id')->primary();
            $table->uuid('warehouse_id');
            $table->string('scrap_category');
            $table->string('scrap_name');
            $table->string('scrap_volume');
            $table->decimal('scrap_price_per_kg', 10, 2);
            $table->bigInteger('scrap_stock_count');
            $table->string('scrap_image');
            $table->date('scrap_created_date');
            $table->date('scrap_updated_date');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
            
            $table->foreign('warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrapdatas');
    }
};
