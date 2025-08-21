<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }

    public function login(string $email, string $password): ?UserToken
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        return UserToken::create([
            'user_id'   => $user->id,
            'token'     => hash('sha256', Str::random(60)),
            'expires_at'=> Carbon::now()->addHours(2),
        ]);
    }

    public function validateToken(string $token): ?User
    {
        $record = UserToken::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $record?->user;
    }

    public function logout(User $user, string $token): void
    {
        UserToken::where('user_id', $user->id)
            ->where('token', $token)
            ->delete();
    }
}