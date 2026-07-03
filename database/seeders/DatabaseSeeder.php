<?php
namespace Database\Seeders;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder {
    public function run(){
        User::updateOrCreate(['email'=>'admin@emko.local'], ['name'=>'Admin EMKO','password'=>Hash::make('password'),'email_verified_at'=>now(),'is_admin'=>true]);
        $cats = ['Synchronizing & Load Sharing'=>'Controller untuk synchronizing, AMF, load sharing, dan integrasi generator dengan jaringan.','Automatic Gen-Set Controller'=>'Controller automatic genset untuk start/stop, proteksi, dan transfer switching.','Automatic Transfer Switching'=>'Controller ATS untuk perpindahan sumber daya otomatis.','Mini/Midi Controller'=>'Controller compact untuk kebutuhan panel genset, pump, dan engine protection.','Communication & Monitoring'=>'Modul komunikasi dan remote monitoring untuk visualisasi genset.','Battery Charger'=>'Gencharger untuk pengisian baterai genset.'];
        $catModels = collect($cats)->mapWithKeys(fn($desc,$name)=>[$name=>Category::updateOrCreate(['slug'=>Str::slug($name)], ['name'=>$name,'description'=>$desc])]);
        $products = [
            ['Synchronizing & Load Sharing','Trans AMF Synchro',945,898,'Synchronizing & AMF untuk generator dengan J1939 ECU communication'],
            ['Synchronizing & Load Sharing','Trans Mains',945,898,'Automatic transfer & load share unit with mains'],
            ['Synchronizing & Load Sharing','Trans Synchro',934,887,'Auto start load share unit'],
            ['Automatic Gen-Set Controller','Trans AMF',301,286,'Automatic gen-set controller with transfer switching'],
            ['Automatic Transfer Switching','Trans ATS.D',301,286,'Automatic transfer switching controller'],
            ['Automatic Gen-Set Controller','Trans Auto',283,269,'Automatic gen-set controller'],
            ['Automatic Gen-Set Controller','Trans Midi AMF',203,193,'Midi AMF controller'],
            ['Automatic Gen-Set Controller','Trans Midi Auto',203,193,'Midi automatic gen-set controller'],
            ['Communication & Monitoring','Trans Web Ethernet',171,162,'Remote monitoring / communication module'],
            ['Automatic Transfer Switching','Trans Mini ATS',160,152,'Mini ATS controller'],
            ['Mini/Midi Controller','Trans Mini AMF',160,152,'Mini AMF controller'],
            ['Mini/Midi Controller','Trans Mini Auto++',146,139,'Mini auto controller'],
            ['Mini/Midi Controller','Trans Mini Pump',146,139,'Pump controller'],
            ['Mini/Midi Controller','Trans Crank',90,86,'Manual key start / engine protection'],
            ['Battery Charger','Battery Charger 524S',68,65,'Battery charger'],
            ['Battery Charger','Battery Charger 512S',68,65,'Battery charger'],
        ];
        foreach($products as $i=>$p){ [$cat,$name,$price,$final,$desc]=$p; Product::updateOrCreate(['slug'=>Str::slug($name)], ['category_id'=>$catModels[$cat]->id,'product_code'=>$name,'product_name'=>$name,'short_description'=>$desc,'features'=>['Harga estimasi Rupiah setelah diskon 5%','Cocok untuk kebutuhan proyek B2B','Dapat dikonsultasikan dengan sales sebelum pembelian'],'specifications'=>['Kategori: '.$cat,'Status: Active','Delivery dihitung berdasarkan alamat customer'],'price_usd'=>$price * 16000,'discount_percent'=>5,'final_price_usd'=>$final * 16000,'price_note'=>'Belum termasuk pajak, shipping, instalasi, konfigurasi, dan biaya lain berdasarkan proyek.','status'=>'active','is_featured'=>$i<6]); }
    }
}
