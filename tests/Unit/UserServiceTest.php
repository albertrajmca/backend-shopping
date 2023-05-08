<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserServiceInterface;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Mock user service - generate token
     *
     * @return void
     */
    public function test_mock_user_service_token_method()
    {
        $testData = [
            "token" => "5VZvGNn5SaEzvHiZibPpxsf9LJjEIGte1E2wJcJ9",
            "user" => [
                "id" => 2,
                "name" => "Albert",
            ],
        ];
        $this->mock(UserServiceInterface::class, function (MockInterface $mock) use ($testData) {
            $mock
                ->shouldReceive('generateToken')
                ->once()
                ->andReturn($testData);
        });

        User::factory()->count(4)->create();

        $this->postJson(route('token.store'),
            [
                'email' => User::pluck('email')->random(),
                'password' => 'password', // intentionally kept it in Users factory
            ]
        );
    }

    /**
     * Mock user service - sign up
     *
     * @return void
     */
    public function test_mock_user_service_signup_method()
    {
        $testData = "ijGXkhJVAlBDUflUOSG8LXSJ3LVtHjPz3b3kbnyB";
        $this->mock(UserServiceInterface::class, function (MockInterface $mock) use($testData) {
            $mock
                ->shouldReceive('store')
                ->once()
                ->andReturn($testData);
        });

        $faker = Faker::create();
        $password = $faker->password;

        $inputs = [
            "name" => $faker->name,
            "email" => $faker->safeEmail,
            "password" => $password,
            "password_confirmation" => $password,
        ];
        $this->postJson(route('users.signup'), $inputs);
    }

}
