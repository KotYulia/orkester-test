<?php

namespace Tests\Feature\Rest;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_user()
    {
        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
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
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'password' => 'password',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('create_user'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);
    }

    public function test_register_user_validate_unique_email_error()
    {
        $user = [
            'name' => 'Test',
            'email' => 'test@gmail.com',
            'password' => 'password',
        ];

        $this->post(route('create_user'), $user);
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

    public function test_update_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('update_user_info'), [
            'name' => 'Test',
            'email' => 'test@gmail.com',
        ]);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name' => 'Test',
                'email' => 'test@gmail.com',
            ]);
    }

    public function test_update_user_validate_type_and_length_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('update_user_info'), [
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus placerat, mauris et placerat tempus, lacus dolor imperdiet risus, a venenatis mauris urna eu lorem. In fermentum aliquam iaculis. Maecenas varius euismod lectus, et fermentum tellus consectetur non. Praesent condimentum nibh ante, et sagittis mauris tincidunt rutrum. Nam dapibus tristique tortor sit amet sollicitudin. Nunc ac porttitor nunc. Fusce posuere urna quis turpis gravida, sit amet blandit est vestibulum. Donec non varius mi. Mauris egestas id sem vel molestie. Etiam a enim tortor. Pellentesque blandit tincidunt imperdiet. Integer at velit vitae enim dapibus sagittis non ut enim. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nulla facilisi. Phasellus tincidunt, turpis elementum tristique consectetur, eros orci faucibus elit, id aliquet augue sapien nec diam. Sed sed ante tempor, ullamcorper enim quis, molestie nisi',
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('update_user_info'), [
            'name' => 'Test',
            'email' => 'test',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('update_user_info'), [
            'name' => 123123,
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('update_user_info'), [
            'name' => 'Test',
            'email' => 123123,
        ]);
        $response->assertStatus(302);
    }
    public function test_update_user_validate_required_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('create_user'), []);
        $response->assertStatus(302);

        $response = $this->post(route('update_user_info'), [
            'email' => 'test@gmail.com',
        ]);
        $response->assertStatus(302);

        $response = $this->post(route('update_user_info'), [
            'name' => 'Test',
        ]);
    }

    public function test_update_user_not_post()
    {
        $response = $this->get(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('update_user_info'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_change_password()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('change_password'), [
            'old_password' => 'password',
            'new_password' => 'password1',
            'password_confirmation' => 'password1',
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_validation_change_password()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('change_password'), []);
        $response->assertStatus(302);

        $response = $this->post(route('change_password'), [
            'old_password' => 'password',
            'new_password' => 'password1',
            'password_confirmation' => 'test',
        ]);
        $response->assertStatus(302);
    }

    public function test_change_password_not_post()
    {
        $response = $this->get(route('change_password'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->put(route('change_password'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);

        $response = $this->delete(route('change_password'));
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    public function test_user_login()
    {
        $loginData = ['email' => 'test@gmail.com', 'password' => 'password'];
        $user = User::factory()->create($loginData);
        $this->actingAs($user);

        $this->post(route('user_login'), $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function test_user_login_wrong_credentials()
    {
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => 'password']);

        $loginData = ['email' => 'test@gmail.com', 'password' => '111'];
        $this->post(route('user_login'), $loginData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "Wrong credentials",
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->post(route('user_logout'))
            ->assertStatus(200)
            ->assertJson([
            "message" => "You are successfully logged out",
        ]);;
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
