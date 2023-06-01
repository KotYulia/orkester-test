<?php

namespace App\Http\Controllers\Rest;

use App\Models\User;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request): User
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    public function update(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::query()
            ->where('id', '=', intval(Auth::id()))
            ->firstOrFail();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return $user;
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'password_confirmation' => 'required|same:new_password',
        ]);

        $user = User::where('id', intval(Auth::id()))->firstOrFail();

        if(!Hash::check($request->old_password, $user->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }

        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return $user;
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Auth\Authenticatable|null
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            $user->api_token = Str::random(60);
            $user->save();

            return $user;
        }

        return response()->json(['message' => 'Wrong credentials'], 401);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('api')->user();
        $user->api_token = null;
        $user->save();

        return response()->json(['message' => 'You are successfully logged out'], 200);
    }
}
