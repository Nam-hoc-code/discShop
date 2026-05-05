<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class EventController extends Controller
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    }

    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->get();
        
        if (auth()->check() && auth()->user()->role === 'ADMIN') {
            return view('admin.events.index', compact('events'));
        }
        
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'buy_url' => 'required|url',
            'banner' => 'required|image|max:5120', // 5MB limit
        ]);

        $bannerUrl = null;
        if ($request->hasFile('banner')) {
            $upload = (new UploadApi())->upload($request->file('banner')->getRealPath(), [
                'folder' => 'events'
            ]);
            $bannerUrl = $upload['secure_url'];
        }

        Event::create([
            'name' => $request->name,
            'event_date' => $request->event_date,
            'price' => $request->price,
            'buy_url' => $request->buy_url,
            'banner_image' => $bannerUrl,
        ]);

        return redirect()->route('events.index')->with('success', 'Sự kiện đã được thêm thành công!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'buy_url' => 'required|url',
            'banner' => 'nullable|image|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'event_date' => $request->event_date,
            'price' => $request->price,
            'buy_url' => $request->buy_url,
        ];

        if ($request->hasFile('banner')) {
            $upload = (new UploadApi())->upload($request->file('banner')->getRealPath(), [
                'folder' => 'events'
            ]);
            $data['banner_image'] = $upload['secure_url'];
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Sự kiện đã được cập nhật!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Sự kiện đã được xóa!');
    }
}
