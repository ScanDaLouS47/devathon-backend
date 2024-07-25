<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            return BaseResponse::response(true, UserResource::collection(User::all()->where('status', 'active')), '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $validatedData['role_id'] = $validatedData['role_id'] ?? $request->getDefaultRoleId();
            $validatedData['status'] = $validatedData['status'] ?? 'active';

            $user = User::create($validatedData);
            return BaseResponse::response(true, new UserResource($user), 'Create user successfull', 201);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = User::where('sup_id', $id)->where('status', 'active')->first();
            if (is_null($user)) {
                return BaseResponse::response(false, $user, 'User not found', 200);
            }
            return BaseResponse::response(true, new UserResource($user), '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            if (is_null($user)) {
                return BaseResponse::response(false, $user, 'User not found', 200);
            }
            $user->update($request->validated());
            return BaseResponse::response(true, new UserResource($user), 'Update successfull', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (is_null($user)) {
                return BaseResponse::response(false, $user, 'User not found', 200);
            }

            if ($user->status == 'inactive') {
                return BaseResponse::response(false, null, 'User not found', 200);
            }

            $user->status = 'inactive';
            $user->save();
            return BaseResponse::response(true, new UserResource($user), 'Account deleted', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

}