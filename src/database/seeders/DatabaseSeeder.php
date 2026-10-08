<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar 5 categorias e 10 tags
        $categories = \App\Models\Category::factory(5)->create();
        $tags = \App\Models\Tag::factory(10)->create();

        // Criar 3 usuários/autores
        $users = User::factory(3)->create();

        // Criar 20 posts e associar aleatoriamente categorias, tags e autores
        \App\Models\Post::factory(20)->make()->each(function ($post) use ($users, $categories, $tags) {
            $post->user_id = $users->random()->id;
            $post->category_id = $categories->random()->id;
            $post->save();

            // Associar aleatoriamente de 1 a 3 tags ao post
            $postTags = $tags->random(rand(1, 3))->pluck('id');
            $post->tags()->attach($postTags);
        });
    }
}
