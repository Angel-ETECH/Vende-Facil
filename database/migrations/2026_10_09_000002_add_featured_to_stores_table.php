<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->boolean('featured')
                ->default(false)
                ->after('unlimited_products_activated_at');

            $table->timestamp('featured_until')
                ->nullable()
                ->after('featured');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'featured',
                'featured_until',
            ]);
        });
    }
};
