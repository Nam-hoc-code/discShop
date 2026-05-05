<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            if (!Schema::hasColumn('songs', 'genre_id')) {
                $table->foreignId('genre_id')->nullable()->constrained('genres')->onDelete('set null')->after('artist_id');
            } else {
                // If column exists but FK might be missing, try to add FK specifically
                try {
                    $table->foreign('genre_id')->references('id')->on('genres')->onDelete('set null');
                } catch (\Exception $e) {}
            }
        });
    }

    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign(['genre_id']);
            $table->dropColumn('genre_id');
        });
    }
};
