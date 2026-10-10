<?php

use App\Livewire\PostDetail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;

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

Route::get('/teste-publicacao', function () {
    // 1. Cria um novo post
    $post = Post::create([
        'user_id' => User::first()->id,
        'category_id' => Category::first()->id,
        'title' => 'Post de Teste Invalidação ' . rand(100, 999),
        'slug' => 'post-teste-invalidation-' . rand(100, 999),
        'body' => 'Conteúdo de teste para validar o expurgo automático do Redis.',
        'status' => 'published',
    ]);
    return response()->json([
        'mensagem' => 'Post criado com sucesso!',
        'post' => $post->title,
        'status_cache_redis_apos_criacao' => Cache::has('posts_published_home') ? 'Ainda em Cache (FALHA)' : 'Cache Expurgado com Sucesso! (SUCESSO)'
    ]);
});
