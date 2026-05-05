@extends('layouts.admin')

@section('title', 'Chỉnh sửa sự kiện')

@section('styles')
    <style>
        .form-container {
            max-width: 700px;
            background-color: var(--bg-card);
            padding: 32px;
            border-radius: 8px;
            border: 1px solid #222;
        }

        .form-group { margin-bottom: 24px; }
        label { display: block; margin-bottom: 8px; color: var(--text-muted); text-transform: uppercase; font-size: 12px; font-weight: bold; }
        
        input[type="text"], input[type="date"], input[type="number"], input[type="url"], input[type="file"] {
            width: 100%;
            padding: 12px;
            background-color: #282828;
            border: 1px solid transparent;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            box-sizing: border-box;
        }

        input:focus { outline: none; border-color: #555; background-color: #333; }

        .btn-submit {
            background-color: var(--accent-green);
            color: black;
            border: none;
            padding: 14px 32px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            text-transform: uppercase;
            transition: transform 0.2s;
        }

        .btn-submit:hover { transform: scale(1.04); background-color: #1ed760; }
        
        .btn-cancel {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
            font-size: 14px;
        }

        .current-banner {
            margin-top: 10px;
            width: 200px;
            border-radius: 4px;
            border: 1px solid #444;
        }
    </style>
@endsection

@section('content')
    <div class="header">
        <h1>Chỉnh sửa sự kiện</h1>
    </div>

    @if($errors->any())
        <div class="alert" style="background: rgba(255, 85, 85, 0.2); color: #ff5555; border-color: rgba(255, 85, 85, 0.3);">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>Tên sự kiện</label>
                <input type="text" name="name" value="{{ old('name', $event->name) }}" required>
            </div>

            <div class="form-group">
                <label>Ngày diễn ra</label>
                <input type="date" name="event_date" value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="form-group">
                <label>Giá vé (VNĐ)</label>
                <input type="number" name="price" value="{{ old('price', $event->price) }}" min="0">
            </div>

            <div class="form-group">
                <label>Link mua vé (URL)</label>
                <input type="url" name="buy_url" value="{{ old('buy_url', $event->buy_url) }}" required>
            </div>

            <div class="form-group">
                <label>Ảnh Banner (Để trống nếu không muốn thay đổi)</label>
                @if($event->banner_image)
                    <div>
                        <p style="font-size: 11px; color: var(--text-muted);">Ảnh hiện tại:</p>
                        <img src="{{ $event->banner_image }}" class="current-banner">
                    </div>
                @endif
                <input type="file" name="banner" accept="image/*" style="margin-top: 15px;">
            </div>

            <div style="margin-top: 40px;">
                <button type="submit" class="btn-submit">Cập nhật sự kiện</button>
                <a href="{{ route('events.index') }}" class="btn-cancel">Hủy bỏ</a>
            </div>
        </form>
    </div>
@endsection
