@extends('layouts.artist')

@section('title', 'Tải lên bài hát mới')

@section('content')
<div class="header-section">
    <a href="{{ route('artist.dashboard') }}" class="back-btn"><i class="fas fa-chevron-left"></i></a>
    <i class="fa-solid fa-circle-plus" style="font-size: 2.2rem; color: #fff; margin-left: 10px;"></i>
    <h1>Tải lên bài hát mới</h1>
</div>

<div style="max-width: 650px; background: #121212; padding: 40px; border-radius: 12px; border: 1px solid #282828;">
    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('artist.songs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="margin-bottom: 25px;">
            <label>Tên bài hát</label>
            <input type="text" name="title" required placeholder="Nhập tên bài hát" value="{{ old('title') }}">
        </div>

        <div style="margin-bottom: 25px;">
            <label>Thể loại nhạc</label>
            <select name="genre_id" id="genre_select" style="width: 100%; padding: 12px; background: #282828; border: 1px solid #333; border-radius: 4px; color: white; margin-bottom: 10px;">
                <option value="">-- Chọn thể loại có sẵn --</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>{{ $genre->name }}</option>
                @endforeach
            </select>
            
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                <span style="color: #777; font-size: 0.9rem;">Hoặc</span>
                <input type="text" name="new_genre" placeholder="Thêm thể loại mới (nếu chưa có)" value="{{ old('new_genre') }}" style="margin: 0;">
            </div>
            <small style="color: #777; display: block; margin-top: 8px; font-size: 0.8rem;">* Nếu bạn nhập thể loại mới, hệ thống sẽ ưu tiên dùng tên mới này.</small>
        </div>
        
        <div style="margin-bottom: 25px;">
            <label>Tập tin nhạc (MP3)</label>
            <input type="file" name="audio" accept=".mp3" required>
            <small style="color: #777; display: block; margin-top: 8px; font-size: 0.8rem;">* Hỗ trợ định dạng .mp3 (Tối đa 10MB)</small>
        </div>
        
        <div style="margin-bottom: 40px;">
            <label>Ảnh bìa (Cover Image)</label>
            <input type="file" name="cover" accept="image/*" required>
            <small style="color: #777; display: block; margin-top: 8px; font-size: 0.8rem;">* Hỗ trợ định dạng .jpg, .png (Tối đa 2MB)</small>
        </div>
        
        <button type="submit" class="btn-primary" style="width: 100%;">TẢI LÊN NGAY</button>
    </form>
</div>
@endsection
