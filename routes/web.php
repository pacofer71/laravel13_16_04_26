<?php

use App\Http\Controllers\InicioController;
use Illuminate\Support\Facades\Route;

//Route::view('/', 'welcome')->name('home');
Route::get('/', [InicioController::class, 'inicio'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('posts', 'pages::posts.show-user-posts')->name('posts.show');
    Route::livewire('postsmodal', 'pages::posts.show-user-posts1')->name('posts.show1');
    Route::livewire('posts/create', 'pages::posts.create-post')->name('posts.create');
    Route::livewire('posts/{post}/edit', 'pages::posts.edit-post')->name('posts.edit');

});

require __DIR__.'/settings.php';
