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

    public function show(): JsonResponse
    {
        try {
            $user = auth()->user();
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
}