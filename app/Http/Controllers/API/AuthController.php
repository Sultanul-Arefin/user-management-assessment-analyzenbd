<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(RegisterUserRequest $request)
    {
        return response([
            'name' => 'sultan',
            'age' => 28
        ]);
        return apiResponse(
            data: null
        );
    }
}
