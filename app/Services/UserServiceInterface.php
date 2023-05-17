<?php

namespace App\Services;

use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function createUser(Request $request): array;
    public function generateToken(string $email, string $password): array;
}
