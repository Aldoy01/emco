<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(){ Schema::create('products', function(Blueprint $table){ $table->id(); $table->foreignId('category_id')->constrained()->cascadeOnDelete(); $table->string('product_code'); $table->string('slug')->unique(); $table->string('product_name'); $table->text('short_description')->nullable(); $table->json('features')->nullable(); $table->json('specifications')->nullable(); $table->decimal('price_usd',10,2)->default(0); $table->decimal('discount_percent',5,2)->default(0); $table->decimal('final_price_usd',10,2)->default(0); $table->string('price_note')->nullable(); $table->string('datasheet_file')->nullable(); $table->string('image')->nullable(); $table->enum('status',['active','inactive','by_request','discontinued'])->default('active'); $table->boolean('is_featured')->default(false); $table->timestamps(); }); }
    public function down(){ Schema::dropIfExists('products'); }
};
