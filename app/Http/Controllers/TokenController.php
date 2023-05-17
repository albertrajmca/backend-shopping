<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenDataValidation;
use App\Http\Resources\UserResource;
use App\Services\UserServiceInterface;
use Illuminate\Http\Response;

class TokenController extends Controller
{
    /**
     *
     * @param UserServiceInterface $userService
     */
    public function __construct(public UserServiceInterface $userService)
    {}

    /**
     * Method used to create a token
     *
     * @param TokenDataValidation $request
     * @return Response
     */
    public function createToken(TokenDataValidation $request): Response
    {
        $response = $this->userService->generateToken($request->email, $request->password);
        if (null === $response['user'] || null === $response['token']) {
            return response("Invalid credentials passed", 401);
        }

        return response([
            'token' => $response['token'],
            'user' => UserResource::make($response['user']),
        ]);
    }
}
