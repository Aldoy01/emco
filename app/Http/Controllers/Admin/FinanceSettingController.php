<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentSetting;
use Illuminate\Http\Request;

class FinanceSettingController extends Controller
{
    public function edit()
    {
        return view('admin.finance.edit', ['finance' => ContentSetting::getValue('finance', self::defaults())]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'invoice_company' => 'required|string|max:160',
            'payment_intro' => 'required|string|max:500',
            'bank_accounts' => 'required|string|max:2500',
            'transfer_note' => 'required|string|max:500',
            'finance_name' => 'nullable|string|max:120',
            'finance_email' => 'nullable|email|max:160',
            'finance_phone' => 'nullable|string|max:80',
            'finance_information' => 'nullable|string|max:1200',
        ]);

        ContentSetting::setValue('finance', [
            'invoice_company' => $data['invoice_company'],
            'payment_intro' => $data['payment_intro'],
            'bank_accounts' => $this->bankAccounts($data['bank_accounts']),
            'transfer_note' => $data['transfer_note'],
            'finance_name' => $data['finance_name'] ?? '',
            'finance_email' => $data['finance_email'] ?? '',
            'finance_phone' => $data['finance_phone'] ?? '',
            'finance_information' => $data['finance_information'] ?? '',
        ]);

        return redirect()->route('admin.finance.edit')->with('success', 'Informasi finance dan rekening invoice berhasil diperbarui.');
    }

    public static function defaults(): array
    {
        return [
            'invoice_company' => 'EMKO / Gencontrol Indonesia',
            'payment_intro' => 'Transfer ke salah satu rekening berikut, lalu lakukan konfirmasi pembayaran.',
            'bank_accounts' => [
                ['bank' => 'BCA', 'account_number' => '1234567890', 'account_name' => 'PT Gencontrol Indonesia'],
                ['bank' => 'Mandiri', 'account_number' => '9876543210', 'account_name' => 'PT Gencontrol Indonesia'],
            ],
            'transfer_note' => 'Gunakan nomor invoice sebagai berita transfer agar pembayaran mudah diverifikasi.',
            'finance_name' => 'Finance EMKO Indonesia',
            'finance_email' => 'finance@emkoindonesia.com',
            'finance_phone' => '081292718681',
            'finance_information' => 'Tim finance akan memverifikasi pembayaran setelah bukti transfer dikirim melalui halaman konfirmasi pembayaran.',
        ];
    }

    private function bankAccounts(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))->map(function ($line) {
            [$bank, $accountNumber, $accountName] = array_pad(explode('|', $line, 3), 3, '');

            return [
                'bank' => trim($bank),
                'account_number' => trim($accountNumber),
                'account_name' => trim($accountName),
            ];
        })->filter(fn($item) => $item['bank'] !== '' || $item['account_number'] !== '' || $item['account_name'] !== '')->values()->all();
    }
}
