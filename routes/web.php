<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');

use App\Http\Controllers\DiscController;

Route::middleware(['auth'])->group(function () {
    Route::get('/discs', [DiscController::class, 'index'])->name('discs.index');
    Route::get('/discs/cart', [DiscController::class, 'cart'])->name('discs.cart');
    Route::post('/discs/cart/add', [DiscController::class, 'addToCart'])->name('discs.cart.add');
    Route::delete('/discs/cart/{index}', [DiscController::class, 'removeFromCart'])->name('discs.cart.remove');
    Route::get('/discs/checkout', [DiscController::class, 'checkout'])->name('discs.checkout');
    Route::post('/discs/coupon', [DiscController::class, 'applyCoupon'])->name('discs.coupon.apply');
    Route::post('/discs/order', [DiscController::class, 'processOrder'])->name('discs.order.process');
    Route::get('/discs/{id}', [DiscController::class, 'show'])->name('discs.show');
});

use App\Http\Controllers\EventController;

Route::middleware(['auth'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    });
});

use App\Http\Controllers\FavoriteController;

Route::middleware(['auth'])->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/favorites/toggle-disc', [FavoriteController::class, 'toggleDisc'])->name('favorites.toggle_disc');
    Route::delete('/favorites/{id}', [FavoriteController::class, 'remove'])->name('favorites.remove');
    Route::delete('/favorites/disc/{id}', [FavoriteController::class, 'removeDisc'])->name('favorites.remove_disc');
});

use App\Http\Controllers\PlaylistController;

use App\Http\Controllers\SearchController;
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
    Route::get('/playlists/{id}', [PlaylistController::class, 'show'])->name('playlists.show');
    Route::put('/playlists/{id}', [PlaylistController::class, 'update'])->name('playlists.update');
    Route::delete('/playlists/{id}', [PlaylistController::class, 'destroy'])->name('playlists.destroy');
    Route::post('/playlists/add-song', [PlaylistController::class, 'addSong'])->name('playlists.add_song');
    Route::post('/playlists/{id}/remove-song', [PlaylistController::class, 'removeSong'])->name('playlists.remove_song');
});

use App\Http\Controllers\Admin\AdminController;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/songs/requests', [AdminController::class, 'songRequests'])->name('admin.songs.requests');
    Route::post('/songs/{id}/approve', [AdminController::class, 'approveSong'])->name('admin.songs.approve');
    Route::post('/songs/{id}/reject', [AdminController::class, 'rejectSong'])->name('admin.songs.reject');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    Route::post('/users/{id}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.status');

    // Activity Logs
    Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');
    Route::post('/logs/{id}/undo', [AdminController::class, 'undoAction'])->name('admin.logs.undo');
});

// Artist Public Profile
Route::get('/artist-profile/{id}', [HomeController::class, 'artistProfile'])->name('artist.profile');

// Follow Artist
Route::post('/artist/{id}/follow', [HomeController::class, 'toggleFollow'])->middleware('auth')->name('artist.follow');

use App\Http\Controllers\User\ProfileController;

// Notifications
Route::post('/notifications/{id}/read', [HomeController::class, 'markAsRead'])->middleware('auth')->name('notifications.read');
Route::get('/notifications', [HomeController::class, 'allNotifications'])->middleware('auth')->name('notifications.index');

// Profile & Orders
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile.index');
Route::post('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::post('/profile/order/{id}/confirm', [ProfileController::class, 'confirmReceived'])->middleware('auth')->name('profile.order.confirm');

use App\Http\Controllers\Artist\ArtistController;

Route::middleware(['auth', 'artist'])->prefix('artist')->group(function () {
    Route::get('/dashboard', [ArtistController::class, 'dashboard'])->name('artist.dashboard');
    Route::get('/songs', [ArtistController::class, 'songs'])->name('artist.songs');
    Route::get('/songs/create', [ArtistController::class, 'createSong'])->name('artist.songs.create');
    Route::post('/songs', [ArtistController::class, 'storeSong'])->name('artist.songs.store');
    Route::delete('/songs/{id}', [ArtistController::class, 'deleteSong'])->name('artist.songs.delete');

    // Artist Orders
    Route::get('/orders', [ArtistController::class, 'orders'])->name('artist.orders');
    Route::post('/orders/{id}/status', [ArtistController::class, 'updateOrderStatus'])->name('artist.orders.status');

    // Artist Discs
    Route::get('/discs', [ArtistController::class, 'discs'])->name('artist.discs');
    Route::post('/discs', [ArtistController::class, 'storeDisc'])->name('artist.discs.store');
    Route::delete('/discs/{id}', [ArtistController::class, 'deleteDisc'])->name('artist.discs.delete');
});
