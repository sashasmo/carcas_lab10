<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::factory()->create(['name' => 'Programming']);
        \App\Models\Category::factory()->create(['name' => 'Cooking']);
        \App\Models\Category::factory()->create(['name' => 'Lifestyle']);
        
        \App\Models\User::factory(5)->has(\App\Models\Post::factory()->count(2))->create();

        \App\Models\Comment::factory(10)->create();
    }
}
