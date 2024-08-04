<?php

namespace App\Http\Controllers;

use App\BaseResponse;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\NotFoundResponse;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


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

            if ($request->hasFile('image')) {
                $img = $request->file('image')->storeOnCloudinary('users');
                $url = $img->getSecurePath();
                $public_id = $img->getPublicId();

                $user->userImage()->create([
                    'public_id' => $public_id,
                    'url' => $url,
                    'user_id' => $user->id
                ]);
            }
            return BaseResponse::response(true, new UserResource($user), 'Create user successfull', 201);
        } catch (Exception $e) {
            var_dump($e);
            return BaseResponse::response(false, $e, $e->getMessage(), 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = User::where('sup_id', $id)->where('status', 'active')->first();

            if (!$user)
                return NotFoundResponse::response();
            return BaseResponse::response(true, new UserResource($user), '', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $user = User::where('sup_id', $id)->first();

            if (!$user)
                return NotFoundResponse::response();

            var_dump($user->userImage->public_id);
            if ($request->hasFile('image')) {
                Cloudinary::destroy($user->userImage->public_id);

                $img = $request->file('image')->storeOnCloudinary('users');

                $user->userImage->public_id = $img->getSecurePath();
                $user->userImage->url = $img->getPublicId();
                $user->save();
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
            $user = User::where('sup_id', $id)->first();
            if (!$user || $user->status === 'inactive')
                return NotFoundResponse::response();

            // $user->status = 'inactive';
            // $user->save();

            $user->delete();
            return BaseResponse::response(true, new UserResource($user), 'Account deleted', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }

    public function verify(Request $request): JsonResponse
    {
        try {
            $request = json_decode($request->getContent());
            $user = User::where('email', $request->email)->first();
            if (!$user)
                return NotFoundResponse::response();

            $user->status = 'active';
            $user->sup_id = $request->sup_id;
            $user->save();
            return BaseResponse::response(true, new UserResource($user), 'User verified', 200);
        } catch (Exception $e) {
            return BaseResponse::response(false, null, $e->getMessage(), 500);
        }
    }
}