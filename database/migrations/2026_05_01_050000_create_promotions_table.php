<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            // bundle = beli N produk tertentu dapat harga X
            // package = paket berisi beberapa produk berbeda dengan harga paket
            $table->enum('type', ['bundle', 'package']);
            $table->decimal('price', 12, 2);              // harga promo/paket
            $table->integer('min_quantity')->default(1);  // untuk bundle: min qty yang harus dibeli
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'type']);
        });

        Schema::create('promotion_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1); // qty produk dalam paket/bundle
            $table->timestamps();

            $table->unique(['promotion_id', 'product_id']);
        });

        // Tambah kolom promo ke cart_items dan order_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('promotion_id')->nullable()->constrained()->nullOnDelete()->after('product_id');
            $table->decimal('promotion_price', 12, 2)->nullable()->after('quantity');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('promotion_id')->nullable()->constrained()->nullOnDelete()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Promotion::class);
            $table->dropColumn('promotion_id');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Promotion::class);
            $table->dropColumn(['promotion_id', 'promotion_price']);
        });

        Schema::dropIfExists('promotion_items');
        Schema::dropIfExists('promotions');
    }
};
