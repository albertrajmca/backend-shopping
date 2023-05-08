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

    /**
     *
     * @dataProvider token_data_that_should_fail
     */
    public function test_token_data_that_should_fail(array $requestData)
    {
        $response = $this->postJson(route('token.store'),
            $requestData
        );
        $response->assertStatus(422);
    }

    /**
     * token data test cases
     *
     * @return array
     */
    public function token_data_that_should_fail(): array
    {
        $email = 'albertrajmca@gmail.com';
        $password = 'abc#ci124JK';

        return [
            'no_input_data' => [
                [],
            ],
            'no_email_input' => [
                [
                    'password' => $password,
                ],
            ],
            'no_password_input' => [
                [
                    'email' => $email,
                ],
            ],
            'invalid_email_input' => [
                [
                    'email' => 'albert',
                    'password' => $password
                ],
            ]
        ];
    }
}
