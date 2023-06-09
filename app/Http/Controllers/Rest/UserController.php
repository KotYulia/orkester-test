<?php

namespace App\Http\Controllers\Rest;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request): UserResource
    {
        $request->validated($request->all());

        $user = User::where('id', intval(Auth::id()))->firstOrFail();
        $user->update($request->all());

        return new UserResource($user);
    }
}
