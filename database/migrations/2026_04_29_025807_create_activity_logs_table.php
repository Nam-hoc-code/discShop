<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Người thực hiện
            $table->string('action'); // Hành động: THÊM, SỬA, KHÓA, XÉT QUYỀN...
            $table->string('target_type'); // Loại đối tượng: USER, SONG, EVENT...
            $table->unsignedBigInteger('target_id')->nullable(); // ID của đối tượng bị tác động
            $table->text('description')->nullable(); // Chi tiết hành động
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
