<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupDataValidation;
use App\Services\UserServiceInterface;

class UserController extends Controller
{
    /**
     *
     * @param UserServiceInterface $userService
     */
    public function __construct(public UserServiceInterface $userService)
    {}

    /**
     * Method used to sign up a user
     *
     * @param SignupDataValidation $request
     * @return array
     */
    public function createUser(SignupDataValidation $request): array
    {
        return $this->userService->createUser($request);
    }
}
