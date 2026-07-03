<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('products')
            ->where('price_usd', '>', 0)
            ->where('price_usd', '<', 10000)
            ->update([
                'price_usd' => DB::raw('price_usd * 16000'),
                'final_price_usd' => DB::raw('final_price_usd * 16000'),
            ]);
    }

    public function down(): void
    {
        //
    }
};
