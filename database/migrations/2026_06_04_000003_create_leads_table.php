<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(){ Schema::create('leads', function(Blueprint $table){ $table->id(); $table->string('name'); $table->string('company'); $table->string('email')->index(); $table->string('phone'); $table->string('source')->nullable(); $table->timestamps(); }); }
    public function down(){ Schema::dropIfExists('leads'); }
};
