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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('landlord_id')->nullable();
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('rent_record_id');
            $table->string('invoice_no')->unique();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('amount', 10, 2);
            $table->decimal('vat', 10, 2)->default(0);
            $table->string('duration_units')->nullable();
            $table->enum('invoice_status', ['Pending', 'Canceled','Partial', 'Paid'])->default('Pending');
            $table->boolean('status')->default(true);
            $table->boolean('email_sent')->default(false);
            $table->integer('email_sent_count')->default(0);
            $table->timestamp('last_email_sent_at')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
