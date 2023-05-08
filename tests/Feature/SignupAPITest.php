<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignupAPITest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * sign up invalid inputs test
     *
     */
    public function test_invalid_inputs_validation()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $inputs = [
            "name_invalid" => $faker->name,
            "email" => $faker->safeEmail,
            "password" => $password,
            "password_confirmation" => $password,
        ];

        $response = $this->postJson(route('users.signup'),
            $inputs
        );
        $response->assertStatus(422);
    }

    /**
     * sign up valid inputs test
     *
     */
    public function test_valid_inputs_validation()
    {
        $faker = Faker::create();
        $password = $faker->password;

        $inputs = [
            "name" => $faker->name,
            "email" => $faker->safeEmail,
            "password" => $password,
            "password_confirmation" => $password,
        ];

        $response = $this->postJson(route('users.signup'),
            $inputs
        );
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
        $token = $response->json('token');
        $this->assertIsString($token);
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

        $response = $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );
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
