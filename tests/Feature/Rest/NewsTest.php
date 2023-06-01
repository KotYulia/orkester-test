<?php

namespace Tests\Feature\Rest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_news()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('all_news'));
        $response->assertStatus(Response::HTTP_OK);
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
