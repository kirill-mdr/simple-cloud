<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
class UserService
{
    public function getCurrentUserInfo(Request $request): UserResource
    {
        $user = $request->user();
        return new UserResource($user);
    }
}
