<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('salla_customer_id')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 32)->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('locale', 8)->default('ar');
            $table->json('preferences')->nullable();
            $table->rememberToken();
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            // Fortify 2FA Columns
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
