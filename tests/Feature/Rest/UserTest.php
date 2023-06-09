<?php

namespace Tests\Feature\Rest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_user()
    {
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);

        $response =  $this->actingAs($user, 'sanctum')
            ->patch(route('update_user_info'), [
            'name' => 'Test',
            'email' => 'testtest@gmail.com',
            'current_password' => 'password',
        ]);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name' => 'Test',
                'email' => 'testtest@gmail.com',
            ]);
    }

    public function test_update_user_validate_type_and_length_error()
    {
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);

        $response =  $this->actingAs($user, 'sanctum')
            ->patch(route('update_user_info'), [
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus placerat, mauris et placerat tempus, lacus dolor imperdiet risus, a venenatis mauris urna eu lorem. In fermentum aliquam iaculis. Maecenas varius euismod lectus, et fermentum tellus consectetur non. Praesent condimentum nibh ante, et sagittis mauris tincidunt rutrum. Nam dapibus tristique tortor sit amet sollicitudin. Nunc ac porttitor nunc. Fusce posuere urna quis turpis gravida, sit amet blandit est vestibulum. Donec non varius mi. Mauris egestas id sem vel molestie. Etiam a enim tortor. Pellentesque blandit tincidunt imperdiet. Integer at velit vitae enim dapibus sagittis non ut enim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla facilisi. Phasellus tincidunt, turpis elementum tristique consectetur, eros orci faucibus elit, id aliquet augue sapien nec diam. Sed sed ante tempor, ullamcorper enim quis, molestie nisi',
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);

        $response = $this->patch(route('update_user_info'), [
            'name' => 'Test',
            'email' => 'test',
        ]);
        $response->assertStatus(302);

        $response = $this->patch(route('update_user_info'), [
            'name' => 123123,
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);

        $response = $this->patch(route('update_user_info'), [
            'name' => 'Test',
            'email' => 123123,
        ]);
        $response->assertStatus(302);
    }
    public function test_update_user_validate_required_error()
    {
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);

        $response =  $this->actingAs($user, 'sanctum')
            ->patch(route('update_user_info'), []);
        $response->assertStatus(302);

        $response = $this->patch(route('update_user_info'), [
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);

        $response = $this->patch(route('update_user_info'), [
            'name' => 'Test',
        ]);
        $response->assertStatus(302);
    }

    public function test_update_user_not_patch()
    {
        $response = $this->get(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->post(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
