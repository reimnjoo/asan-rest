<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('superadmin', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username');
            $table->string('password');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });

        // Add default superadmin
        $username = 'admin';
        $password = Hash::make('YourSecurePasswordHere');

        DB::table('superadmin')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'username' => $username,
            'password' => $password,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('superadmin');
    }
};
