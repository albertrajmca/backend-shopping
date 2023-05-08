<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TokenAPITest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * invalid email - token test
     *
     */
    public function test_invalid_email_validation()
    {
        $response = $this->postJson(route('token.store'),
            [
                'email' => 'random@gmail.com', // invalid email
                'password' => 'password',
            ]
        );
        $response->assertStatus(422);
    }

    /**
     * invalid password - token test
     *
     */
    public function test_invalid_password_validation()
    {
        User::factory()->count(4)->create();

        $response = $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'passwords',
            ]
        );
        $response->assertStatus(401);
    }

    /**
     * Create token API Test
     *
     */
    public function test_create_token_api()
    {
        User::factory()->count(4)->create();

        $randomUserEmail = User::pluck('email')->random();

        $response = $this->postJson(route('token.store'),
            [
                'email' => $randomUserEmail,
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );

        // few orm assertion
        $user = User::whereEmail($randomUserEmail)->first();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => $randomUserEmail]);
        $this->assertDatabaseMissing('users', ['name' => 'NonExistingUser']);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => [
                "id",
                "name",
            ],
            'token',
        ]);

        $response->assertJsonStructure(['token']);
        $token = $response->json('token');
        $this->assertIsString($token);
        $userData = $response->json('user');
        $this->assertIsInt($userData['id']);
        $this->assertIsString($userData['name']);
    }
}
