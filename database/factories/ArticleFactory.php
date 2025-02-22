<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'source' => 'NewsAPI',
            'author' => $this->faker->name,
            'url' => $this->faker->url,
            'category' => $this->faker->word,
            'published_at' => now(),
        ];
    }
}