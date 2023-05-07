<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;
use Faker\Factory as Faker;

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
        $this->mock(UserServiceInterface::class, function(MockInterface $mock){
            $mock
            ->shouldReceive('generateToken')
            ->once()
            ->andReturn("somedata");
        });

       User::factory()->count(4)->create();

        $this->postJson(route('token.store'),
               [
                   'email' => User::pluck('email')->random(),
                   'password' => 'password' // intentionally kept it in Users factory
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
        $this->mock(UserServiceInterface::class, function(MockInterface $mock){
            $mock
            ->shouldReceive('store')
            ->once()
            ->andReturn("somedata");
        });

        $faker = Faker::create();
        $password = $faker->password;

        $inputs = [
            "name" => $faker->name,
            "email" => $faker->safeEmail,
            "password" =>  $password,
            "password_confirmation" => $password
        ];
        $this->postJson(route('users.signup'),$inputs);
    }

}
