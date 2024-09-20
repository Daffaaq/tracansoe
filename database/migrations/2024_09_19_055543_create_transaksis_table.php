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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama_customer');
            $table->string('email_customer');
            $table->string('notelp_customer');
            $table->string('alamat_customer');
            $table->string('status')->comment('downpayment, paid');
            $table->foreignId('promosi_id')->nullable()->constrained('promosis')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->double('total_harga');
            $table->decimal('downpayment_amount', 15, 2)->nullable(); // Jumlah DP
            $table->decimal('remaining_payment', 15, 2)->nullable(); // Sisa pembayaran
            $table->string('tracking_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
