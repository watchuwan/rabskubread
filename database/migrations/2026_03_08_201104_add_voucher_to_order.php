// database/migrations/2026_03_11_000004_add_voucher_foreign_key_to_orders_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Verify vouchers table exists before adding constraint
            if (Schema::hasTable('vouchers')) {
                $table->foreign('voucher_id')
                    ->references('id')
                    ->on('vouchers')
                    ->nullOnDelete()
                    ->name('orders_voucher_id_foreign');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_voucher_id_foreign');
        });
    }
};