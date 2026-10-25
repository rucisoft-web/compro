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
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->string('audience')->nullable()->after('location');
            $table->string('duration')->nullable()->after('audience');
            $table->string('price_label')->nullable()->after('duration');
            $table->json('outcomes')->nullable()->after('body');
            $table->json('agenda')->nullable()->after('outcomes');
            $table->boolean('registration_open')->default(true)->after('agenda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catalog_items', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'audience', 'duration', 'price_label', 'outcomes', 'agenda', 'registration_open']);
        });
    }
};
