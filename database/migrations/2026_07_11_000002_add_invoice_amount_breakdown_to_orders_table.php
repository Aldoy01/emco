<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('original_subtotal_idr')->default(0)->after('subtotal_idr');
            $table->unsignedBigInteger('discount_idr')->default(0)->after('original_subtotal_idr');
            $table->decimal('tax_percent', 5, 2)->default(0)->after('discount_idr');
            $table->unsignedBigInteger('tax_idr')->default(0)->after('tax_percent');
        });

        DB::table('orders')->update([
            'original_subtotal_idr' => DB::raw('subtotal_idr'),
            'discount_idr' => 0,
            'tax_percent' => 0,
            'tax_idr' => 0,
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['original_subtotal_idr', 'discount_idr', 'tax_percent', 'tax_idr']);
        });
    }
};