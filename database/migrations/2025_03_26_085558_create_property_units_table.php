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
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('house_id');
            $table->string('name');
            $table->string('roomNumber');
            $table->string('rentTypes');
            $table->string('type')->nullable();
            $table->text('notes')->nullable();
            $table->integer('floor')->nullable();
            $table->decimal('rent', 10, 2);
            $table->string('currency', 10)->nullable();
            $table->enum('unit_status', ['occupied', 'vacant', 'maintenance'])->default('vacant');
            $table->json('amenities')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_units');
    }
};
