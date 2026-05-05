<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disc_orders', function (Blueprint $table) {
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('address');
            $table->string('coupon_code')->nullable()->after('shipping_fee');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('coupon_code');
            $table->decimal('total_amount', 10, 2)->default(0)->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('disc_orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_fee', 'coupon_code', 'discount_amount', 'total_amount']);
        });
    }
};
