<?php

use App\Livewire\PostDetail;
use Illuminate\Support\Facades\Cache;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

use App\Livewire\PostList;

Route::get('/', PostList::class);

Route::get('/posts/{slug}', PostDetail::class)->name('posts.detail');

Route::get('/posts-cache', function () {
    $inicio = microtime(true);

    $hasBanco = Cache::has('posts_home');

    $posts = Cache::remember('posts_home', 60, function () {
        return Post::with(['category', 'tags'])->where('status', 'published')->get()->toArray();
    });

    $tempo = round((microtime(true) - $inicio) * 1000, 2);

    return response()->json([
        'tempo_resposta_ms' => $tempo . ' ms',
        'fonte' => $hasBanco ? 'RAM (Redis)' : 'Banco (PostgreSQL)',
        'total_posts' => count($posts),
        'posts' => $posts 
    ]);
});
