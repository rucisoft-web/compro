<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('social_posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->boolean('published_on_site')->default(false)->index()->after('status');
            $table->timestamp('published_at')->nullable()->after('published_on_site');
        });
    }

    public function down(): void
    {
        Schema::table('social_posts', function (Blueprint $table) {
            $table->dropColumn(['slug', 'published_on_site', 'published_at']);
        });
    }
};
