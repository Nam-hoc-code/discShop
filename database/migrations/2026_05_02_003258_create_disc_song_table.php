<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disc_song', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disc_id')->constrained('discs')->onDelete('cascade');
            $table->foreignId('song_id')->constrained('songs')->onDelete('cascade');
            $table->timestamps();
        });

        // Di chuyển dữ liệu cũ từ discs.song_id sang disc_song
        $discs = DB::table('discs')->whereNotNull('song_id')->get();
        foreach ($discs as $disc) {
            DB::table('disc_song')->insert([
                'disc_id' => $disc->id,
                'song_id' => $disc->song_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Xóa cột song_id cũ trong bảng discs
        Schema::table('discs', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            try {
                $table->dropForeign(['song_id']);
            } catch (\Exception $e) {}
            $table->dropColumn('song_id');
        });
    }

    public function down(): void
    {
        Schema::table('discs', function (Blueprint $table) {
            $table->foreignId('song_id')->nullable()->constrained('songs')->onDelete('cascade');
        });

        // Khôi phục dữ liệu (chỉ lấy bài đầu tiên trong disc_song)
        $pivotData = DB::table('disc_song')->select('disc_id', 'song_id')->get()->unique('disc_id');
        foreach ($pivotData as $data) {
            DB::table('discs')->where('id', $data->disc_id)->update(['song_id' => $data->song_id]);
        }

        Schema::dropIfExists('disc_song');
    }
};
