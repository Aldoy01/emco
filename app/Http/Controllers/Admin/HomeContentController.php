<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeContentController extends Controller
{
    public function edit()
    {
        return view('admin.content.home', ['content' => ContentSetting::getValue('home', self::defaults())]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_eyebrow' => 'required|string|max:120',
            'hero_title' => 'required|string|max:180',
            'hero_subtitle' => 'required|string|max:800',
            'hero_primary_label' => 'required|string|max:80',
            'hero_secondary_label' => 'required|string|max:80',
            'quick_title' => 'required|string|max:120',
            'quick_items' => 'required|string|max:1200',
            'benefit_heading' => 'required|string|max:180',
            'benefits' => 'required|string|max:2500',
            'flow_heading' => 'required|string|max:180',
            'flows' => 'required|string|max:2500',
            'cta_eyebrow' => 'required|string|max:120',
            'cta_title' => 'required|string|max:180',
            'cta_text' => 'required|string|max:800',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $current = ContentSetting::getValue('home', self::defaults());
        $heroImage = $current['hero_image'] ?? null;

        if ($request->hasFile('hero_image')) {
            if ($heroImage && File::exists(base_path($heroImage))) {
                File::delete(base_path($heroImage));
            }
            $directory = base_path('uploads/home');
            File::ensureDirectoryExists($directory);
            $file = $request->file('hero_image');
            $filename = 'home-hero-' . time() . '-' . Str::random(4) . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);
            $heroImage = 'uploads/home/' . $filename;
        }

        ContentSetting::setValue('home', [
            'hero_eyebrow' => $data['hero_eyebrow'],
            'hero_title' => $data['hero_title'],
            'hero_subtitle' => $data['hero_subtitle'],
            'hero_primary_label' => $data['hero_primary_label'],
            'hero_secondary_label' => $data['hero_secondary_label'],
            'hero_image' => $heroImage,
            'quick_title' => $data['quick_title'],
            'quick_items' => $this->lines($data['quick_items']),
            'benefit_heading' => $data['benefit_heading'],
            'benefits' => $this->pairs($data['benefits']),
            'flow_heading' => $data['flow_heading'],
            'flows' => $this->pairs($data['flows']),
            'cta_eyebrow' => $data['cta_eyebrow'],
            'cta_title' => $data['cta_title'],
            'cta_text' => $data['cta_text'],
        ]);

        return redirect()->route('admin.content.home.edit')->with('success', 'Konten Home berhasil diperbarui.');
    }

    public static function defaults(): array
    {
        return [
            'hero_eyebrow' => 'Official Product Catalogue',
            'hero_title' => 'EMKO Gencontrol Indonesia',
            'hero_subtitle' => 'Solusi generator controller, ATS, synchronizing, load sharing, remote monitoring, dan battery charger untuk panel genset, integrator, gedung, industri, rumah sakit, dan data center.',
            'hero_primary_label' => 'Lihat Produk',
            'hero_secondary_label' => 'Minta Penawaran',
            'hero_image' => null,
            'quick_title' => 'Quick Access',
            'quick_items' => ['Pilih controller sesuai aplikasi genset.', 'Bandingkan spesifikasi dan harga estimasi.', 'Checkout atau hubungi sales untuk validasi proyek.'],
            'benefit_heading' => 'Dibangun untuk kebutuhan proyek teknikal',
            'benefits' => [
                ['title' => 'Produk lengkap', 'body' => 'AMF, ATS, synchronizing, load sharing, monitoring, mini controller, dan battery charger dalam satu katalog.'],
                ['title' => 'Harga Rupiah', 'body' => 'Harga dasar dan harga diskon tampil dalam Rupiah sehingga mudah dibandingkan di katalog dan pricelist.'],
                ['title' => 'Comparison table', 'body' => 'Bandingkan dimensi, input/output, komunikasi, remote monitoring, dan fitur setiap controller.'],
                ['title' => 'Invoice checkout', 'body' => 'Customer dapat membeli langsung, membuat akun, menerima invoice, dan mengirim konfirmasi pembayaran.'],
            ],
            'flow_heading' => 'Alur Pembelian',
            'flows' => [
                ['title' => 'Pilih produk', 'body' => 'Cari berdasarkan kategori, nama produk, atau pricelist.'],
                ['title' => 'Tinjau checkout', 'body' => 'Masukkan qty, data pembeli, alamat, dan buat akun member.'],
                ['title' => 'Terbit invoice', 'body' => 'Invoice dan instruksi pembayaran muncul setelah checkout selesai.'],
                ['title' => 'Konfirmasi pembayaran', 'body' => 'Upload bukti transfer agar admin dapat memverifikasi order.'],
            ],
            'cta_eyebrow' => 'Need Assistance',
            'cta_title' => 'Butuh rekomendasi controller untuk proyek?',
            'cta_text' => 'Tim sales dapat membantu memilih produk berdasarkan aplikasi genset, jumlah unit, kebutuhan ATS/AMF/synchronizing, komunikasi, dan remote monitoring.',
        ];
    }

    private function lines(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))->map(fn($line) => trim($line))->filter()->values()->all();
    }

    private function pairs(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))->map(function ($line) {
            [$title, $body] = array_pad(explode('|', $line, 2), 2, '');
            return ['title' => trim($title), 'body' => trim($body)];
        })->filter(fn($item) => $item['title'] !== '' || $item['body'] !== '')->values()->all();
    }
}
