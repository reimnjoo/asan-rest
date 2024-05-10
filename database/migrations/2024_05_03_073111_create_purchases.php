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
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('purchase_id')->primary();
            $table->uuid('subscription_id');
            $table->uuid('purchased_by');
            $table->date('purchased_date');
            $table->timestamps();

            $table->foreign('subscription_id')->references('subscription_id')->on('subscriptions');
            $table->foreign('purchased_by')->references('id')->on('users')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
