@extends('layouts.customer-static')

@section('title', 'Tentang Kami')
@section('meta_description', 'Cerita dan misi Toko Roti dalam menghadirkan roti berkualitas untuk Anda')
@section('meta_keywords', 'tentang kami, toko roti, cerita, misi')

@section('content')
<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-neutral-900 mb-4">Tentang {{ $settings['app_name'] ?? 'Toko Roti' }}</h1>
            <p class="text-lg text-neutral-600 max-w-3xl mx-auto">
                {{ $settings['about_hero_desc'] ?? 'Menyediakan roti segar dan lezat setiap hari sejak 2024. Dibuat dengan cinta dan bahan-bahan berkualitas terbaik untuk kebahagiaan Anda.' }}
            </p>
        </div>

        <!-- Story Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <div>
                <h2 class="text-2xl font-bold text-neutral-900 mb-4">Cerita Kami</h2>
                <p class="text-neutral-600 mb-4 leading-relaxed">
                    {{ $settings['about_story_p1'] ?? 'Toko Roti dimulai dari passion kami terhadap seni membuat roti. Berawal dari dapur kecil, kami bertekad untuk menghadirkan roti berkualitas premium yang dapat dinikmati oleh semua orang.' }}
                </p>
                <p class="text-neutral-600 mb-4 leading-relaxed">
                    {{ $settings['about_story_p2'] ?? 'Setiap pagi, tim baker kami yang berpengalaman memulai pekerjaan dengan memilih bahan-bahan terbaik. Kami percaya bahwa roti yang baik dimulai dari bahan yang baik.' }}
                </p>
                <p class="text-neutral-600 leading-relaxed">
                    {{ $settings['about_story_p3'] ?? 'Kini, Toko Roti telah melayani ribuan pelanggan bahagia setiap harinya. Kami terus berkomitmen untuk menjaga kualitas dan inovasi dalam setiap produk yang kami hasilkan.' }}
                </p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 flex items-center justify-center">
                <div class="text-center">
                    <span class="text-9xl">🥐</span>
                    <p class="text-neutral-600 mt-4">Fresh from the oven every day</p>
                </div>
            </div>
        </div>

        <!-- Values Section -->
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-neutral-900 text-center mb-8">Nilai-Nilai Kami</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 text-center">
                    <div class="w-16 h-16 bg-cream-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🌾</span>
                    </div>
                    <h3 class="font-semibold text-neutral-900 mb-2">Kualitas Premium</h3>
                    <p class="text-neutral-600 text-sm">
                        Hanya menggunakan bahan-bahan pilihan terbaik untuk menghasilkan roti dengan rasa yang sempurna
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 text-center">
                    <div class="w-16 h-16 bg-cream-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">👨‍🍳</span>
                    </div>
                    <h3 class="font-semibold text-neutral-900 mb-2">Dibuat Segar</h3>
                    <p class="text-neutral-600 text-sm">
                        Dipanggang setiap pagi untuk memastikan kesegaran maksimal saat sampai ke tangan Anda
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 text-center">
                    <div class="w-16 h-16 bg-cream-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">❤️</span>
                    </div>
                    <h3 class="font-semibold text-neutral-900 mb-2">Dibuat dengan Cinta</h3>
                    <p class="text-neutral-600 text-sm">
                        Setiap roti dibuat dengan perhatian dan dedikasi untuk kebahagiaan pelanggan kami
                    </p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 mb-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-4xl font-bold text-cream-600 mb-2">{{ number_format($stats['customers']) }}+</p>
                    <p class="text-neutral-600">Pelanggan Bahagia</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-cream-600 mb-2">{{ number_format($stats['products']) }}+</p>
                    <p class="text-neutral-600">Varian Produk</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-cream-600 mb-2">{{ number_format($stats['days']) }}</p>
                    <p class="text-neutral-600">Hari Operasional</p>
                </div>
                <div>
                    <p class="text-4xl font-bold text-cream-600 mb-2">100%</p>
                    <p class="text-neutral-600">Kepuasan</p>
                </div>
            </div>
        </div>

        <!-- Team Section -->
        <div class="text-center mb-16">
            <h2 class="text-2xl font-bold text-neutral-900 mb-4">Tim Kami</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto mb-8">
                Dibalik setiap roti yang lezat, ada tim baker berpengalaman yang berdedikasi tinggi
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 text-center">
                    <div class="w-24 h-24 bg-cream-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">👨‍🍳</span>
                    </div>
                    <h3 class="font-semibold text-neutral-900 mb-1">Head Baker</h3>
                    <p class="text-sm text-neutral-600">15+ tahun pengalaman</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 text-center">
                    <div class="w-24 h-24 bg-cream-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">👩‍🍳</span>
                    </div>
                    <h3 class="font-semibold text-neutral-900 mb-1">Pastry Chef</h3>
                    <p class="text-sm text-neutral-600">Spesialis kue & pastry</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-6 text-center">
                    <div class="w-24 h-24 bg-cream-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl">🧑‍🍳</span>
                    </div>
                    <h3 class="font-semibold text-neutral-900 mb-1">Baker Team</h3>
                    <p class="text-sm text-neutral-600">Tim baker berpengalaman</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-cream-400 to-cream-500 rounded-2xl p-8 text-center">
            <h2 class="text-2xl font-bold text-neutral-900 mb-4">
                Siap Mencoba Roti Segar Kami?
            </h2>
            <p class="text-neutral-800 mb-6">
                Pesan sekarang dan nikmati kelezatan roti yang dibuat dengan cinta
            </p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-neutral-900 text-white px-8 py-4 rounded-lg font-semibold hover:bg-neutral-800 transition-colors" wire:navigate>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Belanja Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
