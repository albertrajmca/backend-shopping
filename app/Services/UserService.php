<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    /**
     * Constructor
     *
     * @param UserRepository $userRepository
     */
    public function __construct(public UserRepository $userRepository)
    {}

    /**
     * Store user data
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        $user = $this->userRepository->store($request);
        return [
            'token' => $user->createToken('my-app-token')->plainTextToken,
        ];
    }

    /**
     * Generate token
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function generateToken(string $email, string $password): array
    {
        $user = $this->userRepository->findUser($email);
        if (!$user || !Hash::check($password, $user->password)) {
            return ['user' => null, 'token' => null];
        }

        return [
            'user' => $user,
            'token' => $user->createToken('my-app-token')->plainTextToken,
        ];
    }
}
