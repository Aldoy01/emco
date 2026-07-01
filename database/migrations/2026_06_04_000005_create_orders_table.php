<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('customer_name');
            $table->string('company')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('shipping_address');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_price_idr');
            $table->unsignedBigInteger('subtotal_idr');
            $table->unsignedBigInteger('shipping_cost_idr')->default(0);
            $table->unsignedBigInteger('total_idr');
            $table->string('payment_method')->default('bank_transfer');
            $table->string('status')->default('pending_payment')->index();
            $table->text('notes')->nullable();
            $table->string('payment_proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};