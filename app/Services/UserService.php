<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct(public UserRepository $userRepository){}


    public function store($request)
    {
        $user = $this->userRepository->store($request);
        return [
            'token' => $user->createToken('my-app-token')->plainTextToken
        ];
    }


    public function generateToken(string $email, string $password)
    {
        $user = $this->userRepository->findUser($email);
        if (!$user || !Hash::check($password, $user->password)) {
            return ['user' => null, 'token' => null];
        }
    
        return [
            'user' => $user,
            'token' => $user->createToken('my-app-token')->plainTextToken
        ];
    }
}