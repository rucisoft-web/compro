<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('solution_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('catalog_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('funnel_stage', 20)->default('edukasi')->index();
            $table->string('format', 20)->default('post');
            $table->json('platforms')->nullable();
            $table->text('body');
            $table->string('hashtags', 500)->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('link_url', 500)->nullable();
            $table->string('status', 20)->default('draf')->index();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('posted_at')->nullable();
            $table->string('posted_url', 500)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
