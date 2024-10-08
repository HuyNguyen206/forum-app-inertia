<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::patch('likes/{type}/{model}', [\App\Http\Controllers\LikeController::class, 'toggleLike'])->name('likes.toggle');
    Route::resource('posts.comments', \App\Http\Controllers\CommentController::class)->shallow()->only(['store', 'destroy', 'update']);
    Route::resource('posts', \App\Http\Controllers\PostController::class)->only(['store', 'destroy', 'update', 'create']);

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('posts/{topicSlug?}', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('posts/{post}/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.show');

