<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Category;

class PostList extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $selectedCategory = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::with(['category', 'tags'])
            ->where('status', 'published')
            ->when($this->search, function ($query) {
                $query->where('title', 'ilike', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->latest()
            ->paginate(6);

        $categories = Category::all();

        return view('livewire.post-list', compact('posts', 'categories'));
    }
}
