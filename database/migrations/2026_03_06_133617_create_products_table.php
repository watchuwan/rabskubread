<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('sku')->unique()->nullable();
            $table->json('ingredients')->nullable();
            $table->json('allergens')->nullable();
            $table->integer('preparation_time')->nullable()->comment('in minutes');
            $table->boolean('is_customizable')->default(false);
            $table->json('customization_options')->nullable();
            $table->string('size')->nullable();
            $table->integer('calories')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('view_count')->default(0);
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->integer('review_count')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('category_id');
            $table->index('is_active');
            $table->index('price');
            $table->index('stock');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
