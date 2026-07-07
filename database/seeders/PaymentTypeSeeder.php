<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'SPP Bulanan', 'code' => 'spp_bulanan', 'description' => 'Iuran bulanan program bimbel', 'default_amount' => 500000, 'sort_order' => 1],
            ['name' => 'Uang Pendaftaran', 'code' => 'pendaftaran', 'description' => 'Biaya pendaftaran siswa baru', 'default_amount' => 300000, 'sort_order' => 2],
            ['name' => 'Uang Gedung', 'code' => 'uang_gedung', 'description' => 'Iuran fasilitas / gedung', 'default_amount' => 1000000, 'sort_order' => 3],
            ['name' => 'Les Tambahan', 'code' => 'les_tambahan', 'description' => 'Program les privat atau intensif', 'default_amount' => 750000, 'sort_order' => 4],
            ['name' => 'Seragam & Buku', 'code' => 'seragam_buku', 'description' => 'Pembelian seragam dan modul belajar', 'default_amount' => 250000, 'sort_order' => 5],
            ['name' => 'Lainnya', 'code' => 'lainnya', 'description' => 'Pembayaran lain-lain', 'default_amount' => null, 'sort_order' => 99],
        ];

        foreach ($types as $type) {
            PaymentType::query()->updateOrCreate(['code' => $type['code']], $type + ['is_active' => true]);
        }

        $defaultType = PaymentType::query()->where('code', 'spp_bulanan')->first();

        Payment::query()->whereNull('payment_type_id')->each(function (Payment $payment) use ($defaultType) {
            $payment->update([
                'payment_type_id' => $defaultType?->id,
                'invoice_number' => $payment->invoice_number ?? Payment::generateInvoiceNumber(),
                'payment_channel' => $payment->payment_channel ?? 'manual',
            ]);
        });
    }
}
