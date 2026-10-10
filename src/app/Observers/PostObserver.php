<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\PostService;

class PostObserver
{
    public function __construct(protected PostService $postService)
    {
        //
    }

    public function saved(Post $post): void
    {
        $this->postService->clearPostCache();
    }

    public function deleted(Post $post): void
    {
        $this->postService->clearPostCache();
    }
}
