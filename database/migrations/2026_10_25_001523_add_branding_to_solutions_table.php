<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('icon');
            $table->string('logo_url')->nullable()->after('logo');
            $table->string('image_url')->nullable()->after('image');
            $table->string('website_url')->nullable()->after('image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropColumn(['logo', 'logo_url', 'image_url', 'website_url']);
        });
    }
};
