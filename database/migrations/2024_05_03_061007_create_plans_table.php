<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('plan_id')->primary();
            $table->string('plan_name');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // Add default plans
        $plans = [
            ['plan_name' => 'Basic'],
            ['plan_name' => 'Premium'],
            ['plan_name' => 'Pro']
        ];

        DB::table('plans')->insert($plans);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('plans');
    }
};

