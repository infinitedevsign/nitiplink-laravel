<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{        
    /**
     * login
     *
     * @param  mixed $request
     * @return array
     */
    public function login(Request $request): array
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        $user = User::query()->whereEmail($request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return [
                    'user' => $user,
                    'token' => $user->createToken(Carbon::now())
                ];
            }

            throw ValidationException::withMessages([
                'password' => 'Wrong password.'
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'User not found.'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required','string'],
            'email' => ['required', 'email', 'unique:users'],

            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return $user;
    }
}
