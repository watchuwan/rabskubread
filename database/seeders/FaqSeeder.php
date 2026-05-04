<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Produk
            ['category' => 'product', 'sort_order' => 1, 'question' => 'Berapa lama roti dapat bertahan?', 'answer' => 'Roti kami dapat bertahan 2-3 hari pada suhu ruang. Untuk kesegaran maksimal, simpan dalam wadah kedap udara. Roti juga dapat dibekukan hingga 1 bulan.'],
            ['category' => 'product', 'sort_order' => 2, 'question' => 'Apakah produk Anda halal?', 'answer' => 'Ya, semua produk kami 100% halal dan menggunakan bahan-bahan yang bersertifikat halal.'],
            ['category' => 'product', 'sort_order' => 3, 'question' => 'Apakah Anda menerima pesanan khusus?', 'answer' => 'Ya, kami menerima pesanan khusus untuk acara spesial seperti ulang tahun, pernikahan, dan corporate events. Hubungi kami minimal 3 hari sebelum acara.'],

            // Pengiriman
            ['category' => 'delivery', 'sort_order' => 1, 'question' => 'Berapa biaya pengiriman?', 'answer' => 'Biaya pengiriman flat rate Rp 15.000 untuk area Jakarta. Gratis ongkir untuk pembelian di atas Rp 200.000.'],
            ['category' => 'delivery', 'sort_order' => 2, 'question' => 'Berapa lama waktu pengiriman?', 'answer' => 'Pengiriman dalam Jakarta biasanya sampai dalam 1-2 hari kerja. Untuk area Jabodetabek 2-3 hari kerja.'],
            ['category' => 'delivery', 'sort_order' => 3, 'question' => 'Apakah Anda mengirim ke luar kota?', 'answer' => 'Saat ini kami hanya melayani pengiriman untuk area Jabodetabek. Untuk pengiriman luar kota, silakan hubungi customer service kami.'],

            // Pembayaran
            ['category' => 'payment', 'sort_order' => 1, 'question' => 'Metode pembayaran apa yang tersedia?', 'answer' => 'Kami menerima transfer bank (BCA, Mandiri, BNI), e-wallet (GoPay, OVO, Dana), dan pembayaran di tempat (COD) untuk area tertentu.'],
            ['category' => 'payment', 'sort_order' => 2, 'question' => 'Apakah ada garansi kepuasan?', 'answer' => 'Ya! Jika Anda tidak puas dengan produk kami, kami akan mengganti produk atau mengembalikan uang Anda 100%.'],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                $data
            );
        }
    }
}
