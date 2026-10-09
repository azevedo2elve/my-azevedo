<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getPublishedPosts(int $ttlInSeconds = 3600): array
    {
        return Cache::remember('posts_published_home', $ttlInSeconds, function () {
            return Post::with(['category', 'tags'])
                ->where('status', 'published')
                ->get()
                ->toArray();
        });
    }

    public function clearPostCache(): void
    {
        Cache::forget('posts_published_home');
    }
}
