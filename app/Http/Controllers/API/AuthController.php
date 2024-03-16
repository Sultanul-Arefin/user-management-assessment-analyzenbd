<?php

namespace App\Http\Controllers\API;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
        } catch(Exception $e){
            throw new CustomException('Oops! something wrong, please try again', 422);
        }
        return apiResponse(
            data: $user,
            message: 'Registered Successfully',
            statusCode: 201
        );
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return apiResponse(
                data: [],
                message: 'Email & Password does not match with our record.',
                status: 'error',
                statusCode: 401
            );
        }

        // GET USER INFO
        $user = User::where('email', $request->email)->first();
        // UPDATE LAST LOGIN TIME
        $user->update(['last_login' => now()]);

        return apiResponse(
            data: [
                'token' => $user->createToken($user->name)->plainTextToken,
                'user_info' => $user->only(['id', 'name', 'email', 'last_login'])
            ],
            message: 'User logged in successful'
        );
    }
}
