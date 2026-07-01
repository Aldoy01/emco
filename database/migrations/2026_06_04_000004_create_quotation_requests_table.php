<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(){ Schema::create('quotation_requests', function(Blueprint $table){ $table->id(); $table->foreignId('lead_id')->constrained()->cascadeOnDelete(); $table->foreignId('product_id')->constrained()->restrictOnDelete(); $table->unsignedInteger('quantity'); $table->string('project_location'); $table->text('technical_needs')->nullable(); $table->date('project_deadline')->nullable(); $table->string('status')->default('new')->index(); $table->text('follow_up_notes')->nullable(); $table->string('utm_source')->nullable(); $table->string('utm_campaign')->nullable(); $table->string('ip_address')->nullable(); $table->timestamps(); }); }
    public function down(){ Schema::dropIfExists('quotation_requests'); }
};
