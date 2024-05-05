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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->uuid('warehouse_id')->primary();
            $table->uuid('warehouse_owner_id');
            $table->string('warehouse_name');
            $table->string('warehouse_owner');
            $table->string('warehouse_location');
            $table->timestamps();
            $table->foreign('warehouse_owner_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
