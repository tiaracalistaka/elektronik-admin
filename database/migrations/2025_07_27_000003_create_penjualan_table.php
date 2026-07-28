<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->nullable()->constrained('produk')->nullOnDelete();
            $table->integer('jumlah')->default(1);
            $table->decimal('total_harga', 15, 0);
            $table->string('customer_name', 200)->default('Guest');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
