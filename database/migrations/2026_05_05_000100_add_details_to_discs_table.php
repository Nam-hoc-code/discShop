<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->foreignId('artist_id')->nullable()->after('title')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->dropForeign(['artist_id']);
            $table->dropColumn(['title', 'artist_id', 'description']);
        });
    }
};
