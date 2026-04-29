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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'user_id')->nullable();
            $table->string('landlord_id');
            $table->string('tenant_id')->unique();
            $table->string('tenant_name');
            $table->string('company_tin')->nullable();
            $table->string('company_name')->nullable();
            $table->text('notes')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->enum('status', ['tenant', 'client'])->default('client');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
