<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'name' => 'Test',
                'email' => 'test@gmail.com',
            ]);
    }

    public function test_register_user_validate_required_error()
    {
        $response = $this->post(route('create_user'), []);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);
        $response->assertStatus(302);
    }

    public function test_register_user_validate_unique_email_error()
    {
        $user = [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->post(route('create_user'), $user);
        $response = $this->post(route('create_user'), $user);
        $response->assertStatus(302);
    }

    public function test_register_user_validate_password_confirmation_error()
    {
        $user = [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post(route('create_user'), $user);
        $response->assertStatus(302);
    }

    public function test_register_user_validate_type_and_length_error()
    {
        $response = $this->post(route('create_user'), [
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus placerat, mauris et placerat tempus, lacus dolor imperdiet risus, a venenatis mauris urna eu lorem. In fermentum aliquam iaculis. Maecenas varius euismod lectus, et fermentum tellus consectetur non. Praesent condimentum nibh ante, et sagittis mauris tincidunt rutrum. Nam dapibus tristique tortor sit amet sollicitudin. Nunc ac porttitor nunc. Fusce posuere urna quis turpis gravida, sit amet blandit est vestibulum. Donec non varius mi. Mauris egestas id sem vel molestie. Etiam a enim tortor. Pellentesque blandit tincidunt imperdiet. Integer at velit vitae enim dapibus sagittis non ut enim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla facilisi. Phasellus tincidunt, turpis elementum tristique consectetur, eros orci faucibus elit, id aliquet augue sapien nec diam. Sed sed ante tempor, ullamcorper enim quis, molestie nisi',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test',
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 123123,
            'email' => 'test@gmail.com',
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 123123,
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'pass',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 123123,
        ]);
        $response->assertStatus(302);
    }

    public function test_register_user_not_post()
    {
        $response = $this->get(route('create_user'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('create_user'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('create_user'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_user_login()
    {
        $loginData = ['email' => 'test@gmail.com', 'password' => 'password'];
        User::factory()->create($loginData);

        $this->post(route('user_login'), $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_user_login_wrong_credentials()
    {
        User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);

        $loginData = ['email' => 'test@gmail.com', 'password' => 'password111'];
        $this->post(route('user_login'), $loginData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "Credentials do not match",
            ]);
    }

    public function test_user_login_not_post()
    {
        $response = $this->get(route('user_login'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('user_login'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('user_login'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_user_logout()
    {
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);
        $token = $user->createToken('API token of ' . $user->name)->plainTextToken;

        $response =  $this->withHeader('Authorization', "Bearer $token")
            ->postJson(route('user_logout'));
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'You are successfully logged out.'
            ]);
    }

    public function test_user_logout_not_post()
    {
        $response = $this->get(route('user_logout'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('user_logout'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('user_logout'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
