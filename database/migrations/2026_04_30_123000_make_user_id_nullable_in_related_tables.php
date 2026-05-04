<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make user_id nullable in stock_movements
        DB::statement('ALTER TABLE stock_movements ALTER COLUMN user_id DROP NOT NULL');
        
        // Make user_id nullable in order_status_histories
        DB::statement('ALTER TABLE order_status_histories ALTER COLUMN user_id DROP NOT NULL');
    }

    public function down(): void
    {
        // Revert: make user_id NOT NULL (only if no null values exist)
        DB::statement('ALTER TABLE stock_movements ALTER COLUMN user_id SET NOT NULL');
        DB::statement('ALTER TABLE order_status_histories ALTER COLUMN user_id SET NOT NULL');
    }
};
