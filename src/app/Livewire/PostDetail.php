<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostDetail extends Component
{
    public Post $post;

    public function mount(string $slug): void
    {
        $this->post = Post::with(['category', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.post-detail');
    }
}
