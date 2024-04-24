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
        Schema::create('owners', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('scrapyard_name');
            $table->string('location');
            $table->string('owners_email');
            $table->string('password');
            $table->string('subscription_plan');
            $table->string('profile_image')->nullable();
            $table->string('id_type')->nullable();
            $table->string('id_image')->nullable();
            $table->date('id_submitted_date')->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
