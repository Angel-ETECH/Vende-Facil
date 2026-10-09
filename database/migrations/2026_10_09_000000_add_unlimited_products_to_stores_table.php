<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('unlimited_products')
                ->default(false)
                ->after('delivery_available');

            $table->timestamp('unlimited_products_activated_at')
                ->nullable()
                ->after('unlimited_products');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'unlimited_products',
                'unlimited_products_activated_at',
            ]);
        });
    }
};
