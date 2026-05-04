@extends('layouts.customer-static')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan')
@section('meta_description', 'Pertanyaan yang sering diajukan tentang produk dan layanan Toko Roti')
@section('meta_keywords', 'faq, pertanyaan, bantuan, informasi')

@section('content')
<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-neutral-900 mb-4">FAQ</h1>
            <p class="text-lg text-neutral-600">Pertanyaan yang sering diajukan tentang produk dan layanan kami</p>
        </div>

        <livewire:customer.faq-list />

        <div class="mt-12 card p-8 text-center">
            <h3 class="text-xl font-bold text-neutral-900 mb-2">Masih Punya Pertanyaan?</h3>
            <p class="text-neutral-600 mb-6">Tim customer service kami siap membantu Anda</p>
            <a href="{{ route('contact') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Hubungi Kami
            </a>
        </div>
    </div>
</div>
@endsection
