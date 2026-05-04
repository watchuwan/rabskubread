<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            [
                "key" => "app_name",
                "value" => "Rabskubread",
                "type" => "text",
                "group" => "general",
                "label" => "Nama Aplikasi",
            ],
            [
                "key" => "app_tagline",
                "value" => "Roti Artisan Segar & Lezat",
                "type" => "text",
                "group" => "general",
                "label" => "Tagline",
            ],
            [
                "key" => "app_description",
                "value" =>
                    "Rabskubread — toko roti artisan terbaik sejak 2015.",
                "type" => "textarea",
                "group" => "general",
                "label" => "Deskripsi Singkat",
            ],
            [
                "key" => "app_logo",
                "value" => null,
                "type" => "image",
                "group" => "general",
                "label" => "Logo",
            ],
            [
                "key" => "app_favicon",
                "value" => null,
                "type" => "image",
                "group" => "general",
                "label" => "Favicon",
            ],
            [
                "key" => "app_currency",
                "value" => "IDR",
                "type" => "text",
                "group" => "general",
                "label" => "Mata Uang",
            ],
            [
                "key" => "free_shipping_min",
                "value" => "200000",
                "type" => "text",
                "group" => "general",
                "label" => "Min. Gratis Ongkir (Rp)",
            ],
            [
                "key" => "business_start_date",
                "value" => "2015-01-01",
                "type" => "date",
                "group" => "general",
                "label" => "Tanggal Berdiri",
            ],

            // Hero Section
            [
                "key" => "hero_badge_text",
                "value" => "Fresh from the Oven Every Day",
                "type" => "text",
                "group" => "hero",
                "label" => "Badge Text",
            ],
            [
                "key" => "hero_subtitle",
                "value" =>
                    "Dibuat dengan tangan oleh baker berpengalaman menggunakan bahan-bahan premium pilihan. Dari croissant renyah hingga sourdough klasik — semua ada di sini.",
                "type" => "textarea",
                "group" => "hero",
                "label" => "Subtitle",
            ],
            [
                "key" => "hero_image",
                "value" => null,
                "type" => "image",
                "group" => "hero",
                "label" => "Hero Image",
            ],

            // Features
            [
                "key" => "feature_1_icon",
                "value" => "🌾",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 1 Icon",
            ],
            [
                "key" => "feature_1_title",
                "value" => "Bahan Premium",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 1 Title",
            ],
            [
                "key" => "feature_1_desc",
                "value" => "Dipilih dengan cermat",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 1 Desc",
            ],
            [
                "key" => "feature_2_icon",
                "value" => "🔥",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 2 Icon",
            ],
            [
                "key" => "feature_2_title",
                "value" => "Dipanggang Segar",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 2 Title",
            ],
            [
                "key" => "feature_2_desc",
                "value" => "Setiap pagi hari",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 2 Desc",
            ],
            [
                "key" => "feature_3_icon",
                "value" => "🚚",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 3 Icon",
            ],
            [
                "key" => "feature_3_title",
                "value" => "Pengiriman Cepat",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 3 Title",
            ],
            [
                "key" => "feature_3_desc",
                "value" => "Sampai ke rumah Anda",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 3 Desc",
            ],
            [
                "key" => "feature_4_icon",
                "value" => "💯",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 4 Icon",
            ],
            [
                "key" => "feature_4_title",
                "value" => "Garansi Kualitas",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 4 Title",
            ],
            [
                "key" => "feature_4_desc",
                "value" => "Atau uang kembali",
                "type" => "text",
                "group" => "features",
                "label" => "Feature 4 Desc",
            ],

            // Contact
            [
                "key" => "contact_address",
                "value" => "Jl. Roti Manis No. 123, Jakarta",
                "type" => "textarea",
                "group" => "contact",
                "label" => "Alamat",
            ],
            [
                "key" => "contact_phone",
                "value" => "+62 812-3456-7890",
                "type" => "text",
                "group" => "contact",
                "label" => "Nomor Telepon",
            ],
            [
                "key" => "contact_email",
                "value" => "hello@rabskubread.com",
                "type" => "text",
                "group" => "contact",
                "label" => "Email",
            ],
            [
                "key" => "contact_whatsapp",
                "value" => "6281234567890",
                "type" => "text",
                "group" => "contact",
                "label" => "WhatsApp",
            ],
            [
                "key" => "business_hours",
                "value" => "Sen-Jum 07.00-20.00, Sab 08.00-18.00",
                "type" => "text",
                "group" => "contact",
                "label" => "Jam Operasional",
            ],
            [
                "key" => "contact_maps_embed",
                "value" =>
                    '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666!2d106.845!3d-6.208!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMjguOCJTIDEwNsKwNTAnNDIuMCJF!5e0!3m2!1sen!2sid!4v1234567890" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                "type" => "textarea",
                "group" => "contact",
                "label" => "Google Maps Embed",
            ],

            // Social
            [
                "key" => "social_instagram",
                "value" => "https://instagram.com/rabskubread",
                "type" => "url",
                "group" => "social",
                "label" => "Instagram",
            ],
            [
                "key" => "social_facebook",
                "value" => "https://facebook.com/rabskubread",
                "type" => "url",
                "group" => "social",
                "label" => "Facebook",
            ],
            [
                "key" => "social_tiktok",
                "value" => null,
                "type" => "url",
                "group" => "social",
                "label" => "TikTok",
            ],

            // // Theme
            // ['key' => 'theme_primary_color', 'value' => '#F59E0B', 'type' => 'text', 'group' => 'theme', 'label' => 'Primary Color (hex)'],
            // ['key' => 'theme_primary_hover',  'value' => '#D97706', 'type' => 'text', 'group' => 'theme', 'label' => 'Primary Hover Color (hex)'],
            // ['key' => 'theme_accent_color',   'value' => '#FFE8A3', 'type' => 'text', 'group' => 'theme', 'label' => 'Accent Color (hex)'],
            // ['key' => 'theme_font_sans',      'value' => 'Inter',            'type' => 'text', 'group' => 'theme', 'label' => 'Font Body (Google Fonts name)'],
            // ['key' => 'theme_font_display',   'value' => 'Playfair Display', 'type' => 'text', 'group' => 'theme', 'label' => 'Font Display/Heading (Google Fonts name)'],

            // About
            [
                "key" => "about_hero_desc",
                "value" =>
                    "Menyediakan roti segar dan lezat setiap hari sejak 2015. Dibuat dengan cinta dan bahan-bahan berkualitas terbaik untuk kebahagiaan Anda.",
                "type" => "textarea",
                "group" => "about",
                "label" => "Hero Description",
            ],
            [
                "key" => "about_story_p1",
                "value" =>
                    "Toko Roti dimulai dari passion kami terhadap seni membuat roti. Berawal dari dapur kecil, kami bertekad untuk menghadirkan roti berkualitas premium yang dapat dinikmati oleh semua orang.",
                "type" => "textarea",
                "group" => "about",
                "label" => "Story Paragraph 1",
            ],
            [
                "key" => "about_story_p2",
                "value" =>
                    "Setiap pagi, tim baker kami yang berpengalaman memulai pekerjaan dengan memilih bahan-bahan terbaik. Kami percaya bahwa roti yang baik dimulai dari bahan yang baik.",
                "type" => "textarea",
                "group" => "about",
                "label" => "Story Paragraph 2",
            ],
            [
                "key" => "about_story_p3",
                "value" =>
                    "Kini, Toko Roti telah melayani ribuan pelanggan bahagia setiap harinya. Kami terus berkomitmen untuk menjaga kualitas dan inovasi dalam setiap produk yang kami hasilkan.",
                "type" => "textarea",
                "group" => "about",
                "label" => "Story Paragraph 3",
            ],

            // SEO
            [
                "key" => "seo_title",
                "value" => "Rabskubread — Roti Artisan Segar",
                "type" => "text",
                "group" => "seo",
                "label" => "Meta Title",
            ],
            [
                "key" => "seo_description",
                "value" =>
                    "Rabskubread, toko roti artisan terbaik dengan bahan premium sejak 2015.",
                "type" => "textarea",
                "group" => "seo",
                "label" => "Meta Description",
            ],
            [
                "key" => "seo_keywords",
                "value" =>
                    "rabskubread, toko roti, roti artisan, croissant, sourdough",
                "type" => "text",
                "group" => "seo",
                "label" => "Meta Keywords",
            ],
        ];

        foreach ($settings as $data) {
            Setting::updateOrCreate(["key" => $data["key"]], $data);
        }
    }
}
