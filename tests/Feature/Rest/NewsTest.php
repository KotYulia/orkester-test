<?php

namespace Tests\Feature\Rest;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_news()
    {
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);
        News::create([
            'source_id' => 'test',
            'source_name' => 'Test',
            'author' => 'Name',
            'title' => 'Test title',
            'description' => 'Test description',
            'url' => 'https://www.test.com',
            'urlToImage' => 'https://www.test.com/image.jpeg',
            'content' => 'Test content',
            'published_at' => now(),
        ]);
        $response =  $this->actingAs($user, 'sanctum')
            ->get(route('all_news'));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'source_id' => 'test',
                'source_name' => 'Test',
                'author' => 'Name',
                'title' => 'Test title',
                'description' => 'Test description',
                'url' => 'https://www.test.com',
                'urlToImage' => 'https://www.test.com/image.jpeg',
                'content' => 'Test content'
            ]);
    }

    public function test_all_news_for_not_authenticated_user()
    {
        $response = $this->get(route('all_news'));
        $response->assertStatus(500);
    }

    public function test_all_news_not_get()
    {
        $response = $this->post(route('all_news'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('all_news'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('all_news'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
