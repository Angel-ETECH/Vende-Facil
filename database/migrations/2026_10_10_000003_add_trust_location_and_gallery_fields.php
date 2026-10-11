<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('location_url', 500)->nullable()->after('address');
            $table->string('opening_hours')->nullable()->after('delivery_available');
            $table->string('facebook_url', 500)->nullable()->after('opening_hours');
            $table->string('instagram_url', 500)->nullable()->after('facebook_url');
            $table->string('tiktok_url', 500)->nullable()->after('instagram_url');
            $table->string('website_url', 500)->nullable()->after('tiktok_url');
            $table->json('gallery_images')->nullable()->after('logo');
            $table->boolean('verified')->default(false)->after('featured_until');
            $table->timestamp('verified_at')->nullable()->after('verified');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('gallery_images')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('gallery_images');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn([
                'location_url',
                'opening_hours',
                'facebook_url',
                'instagram_url',
                'tiktok_url',
                'website_url',
                'gallery_images',
                'verified',
                'verified_at',
            ]);
        });
    }
};
