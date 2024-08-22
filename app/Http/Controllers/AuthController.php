<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\RefreshToken;
use App\Models\User;
use App\NotFoundResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Str;


class AuthController extends Controller
{
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['role_id'] = isset($validatedData['role_id']) ? $validatedData['role_id'] : $request->getDefaultRoleId();
            $validatedData['status'] = 'inactive';
            $user = User::create($validatedData);

            return BaseResponse::response(true, new UserResource($user), 'Create user successfull', 201);
        } catch (Exception $e) {
            var_dump($e);
            return BaseResponse::response(false, $e, $e->getMessage(), 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return BaseResponse::response(false, null, 'Credentials are wrong', 404);
            }

            $user = User::where('email', $request->email)->first();

            if ($user->status == 'inactive') {
                $user->status = 'active';
                $user->save();
            }

            $token = $user->createToken('token', ['*'], now()->addMonth())->plainTextToken;

            return BaseResponse::response(true, [
                'user' => new UserResource($user),
                'token' => $token,
            ], 'Login user successfull', 201);
        } catch (Exception $e) {
            return BaseResponse::response(false, $e, $e->getMessage(), 500);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
            return BaseResponse::response(true, null, 'User logout', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, $e, $e->getMessage(), 500);
        }
    }
}
