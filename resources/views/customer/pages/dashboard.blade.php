@extends('layouts.customer-static')

@section('title', 'Dashboard Saya')
@section('meta_description', 'Dashboard customer Toko Roti')
@section('meta_keywords', 'dashboard, akun saya, customer')

@section('content')
<div class="min-h-screen bg-cream-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 mb-2">
                Halo, {{ auth()->guard('customer')->user()->name }}! 👋
            </h1>
            <p class="text-neutral-600">Selamat datang di dashboard Anda</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900">0</p>
                        <p class="text-sm text-neutral-600">Total Pesanan</p>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900">0</p>
                        <p class="text-sm text-neutral-600">Dalam Pengiriman</p>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900">0</p>
                        <p class="text-sm text-neutral-600">Wishlist</p>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-cream-400 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-neutral-900">Rp 0</p>
                        <p class="text-sm text-neutral-600">Total Belanja</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Orders -->
            <div class="lg:col-span-2">
                <div class="card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-neutral-900">Pesanan Terakhir</h2>
                        <a href="{{ route('orders.index') }}" class="text-sm text-cream-600 hover:text-cream-700 font-medium" wire:navigate>
                            Lihat Semua →
                        </a>
                    </div>

                    <div class="space-y-4">
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-neutral-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-neutral-600">Belum ada pesanan</p>
                            <a href="{{ route('products.index') }}" class="btn-primary inline-flex items-center gap-2 mt-4" wire:navigate>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="card p-6 mb-6">
                    <h2 class="text-xl font-bold text-neutral-900 mb-6">Aksi Cepat</h2>
                    <div class="space-y-3">
                        <a href="{{ route('products.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors" wire:navigate>
                            <div class="w-10 h-10 bg-cream-400 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <span class="font-medium text-neutral-700">Belanja Sekarang</span>
                        </a>

                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors" wire:navigate>
                            <div class="w-10 h-10 bg-cream-400 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <span class="font-medium text-neutral-700">Wishlist</span>
                        </a>

                        <a href="{{ route('addresses.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors" wire:navigate>
                            <div class="w-10 h-10 bg-cream-400 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="font-medium text-neutral-700">Alamat</span>
                        </a>

                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-neutral-50 transition-colors" wire:navigate>
                            <div class="w-10 h-10 bg-cream-400 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-neutral-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="font-medium text-neutral-700">Profil</span>
                        </a>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="card p-6">
                    <h2 class="text-xl font-bold text-neutral-900 mb-6">Informasi Akun</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-neutral-500 mb-1">Email</p>
                            <p class="font-medium text-neutral-900">{{ auth()->guard('customer')->user()->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-neutral-500 mb-1">Bergabung Sejak</p>
                            <p class="font-medium text-neutral-900">{{ auth()->guard('customer')->user()->created_at->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('profile.index') }}" class="block text-center btn-secondary text-sm" wire:navigate>
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
