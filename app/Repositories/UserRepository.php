<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * Find user based on email
     *
     * @param string $email
     * @return User
     */
    public function findUser(string $email): User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Store user data
     *
     * @param Request $request
     * @return User
     */
    public function store(Request $request): User
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return $user;
    }
}