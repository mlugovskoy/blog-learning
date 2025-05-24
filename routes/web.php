<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::resource('posts', PostController::class)->only(['store', 'create']);

    Route::resource('posts.comments', CommentController::class)
        ->shallow()
        ->only(['store', 'update', 'destroy']);
});

Route::get('test', function () {
    return [
        UserResource::make(User::query()->find(11)),
        PostResource::make(Post::query()->find(1)),
        CommentResource::make(Comment::query()->find(1)),
    ];
});

Route::get('posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::resource('posts', PostController::class)->only(['index']);
