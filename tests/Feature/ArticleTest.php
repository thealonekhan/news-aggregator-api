<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Article;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_articles_successfully()
    {
        Article::factory()->count(3)->create();

        // Act: API endpoint
        $response = $this->postJson('/api/articles', [
            'search' => '',
            'category' => '',
        ]);

        // Assert: Check if response is successful and returns correct data
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => [
                             'id', 'title', 'content', 'source', 'author', 'url', 'category', 'published_at'
                         ]
                     ],
                     'pagination' => [
                         'total', 'per_page', 'current_page', 'last_page', 'next_page_url', 'prev_page_url'
                     ]
                 ]);
    }

    /** @test */
    public function it_filters_articles_by_category()
    {
        // Arrange
        Article::factory()->create(['category' => 'Technology']);
        Article::factory()->create(['category' => 'Sports']);

        // Act
        $response = $this->postJson('/api/articles', [
            'category' => 'Technology',
        ]);

        // Assert
        $response->assertStatus(200)
                 ->assertJsonFragment(['category' => 'Technology'])
                 ->assertJsonMissing(['category' => 'Sports']);
    }
}