<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove foreign key from orders table if exists
        if (Schema::hasColumn('orders', 'payment_method_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_method_id');
            });
        }

        // Remove foreign key from payments table if exists
        if (Schema::hasColumn('payments', 'payment_method_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('payment_method_id');
            });
        }

        // Drop payment_methods table
        Schema::dropIfExists('payment_methods');
    }

    public function down(): void
    {
        // Recreate payment_methods table
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('config')->nullable();
            $table->timestamps();
        });

        // Restore foreign keys
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
        });
    }
};
