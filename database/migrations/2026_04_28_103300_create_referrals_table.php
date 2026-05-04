<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('referral_code')->unique()->nullable()->after('gender');
            $table->foreignId('referred_by')->nullable()->after('referral_code')->constrained('customers')->nullOnDelete();
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('customers')->cascadeOnDelete();
            $table->integer('reward_points')->default(0);
            $table->boolean('is_rewarded')->default(false);
            $table->timestamp('rewarded_at')->nullable();
            $table->timestamps();

            $table->index(['referrer_id', 'is_rewarded']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
        
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by']);
        });
    }
};
