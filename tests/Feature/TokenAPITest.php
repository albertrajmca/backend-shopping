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
                'password' => 'password' 
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
                'password' => 'passwords' 
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

        $response = $this->postJson(route('token.store'),
                [
                    'email' => User::pluck('email')->random(),
                    'password' => 'password' // intentionally kept it in Users factory
                ]
            );
        $response->assertStatus(200);
        $response->assertJsonStructure([
                'user' => [
                        "id",
                        "name"
                    ],
                'token'
            ]);
    }
}

