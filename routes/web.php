<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TagController;

Route::redirect('nova', '/nova/login');

Route::feeds();

Route::get('/', HomeController::class)->name('home');
Route::get('blog', BlogController::class)->name('blog');

Route::view('about', 'about.index')->name('about');

Route::redirect('me', '/about');

Route::get('tags', [TagController::class, 'index'])->name('tags');
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tag');

Route::get('{postSlug}', PostController::class)->name('post');
