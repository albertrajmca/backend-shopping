<?php

namespace App\Services;

interface UserServiceInterface
{
    public function store($request);
    public function generateToken(string $email, string $password);
}