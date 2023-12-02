<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'sometimes|required|min:6',
            // Add more validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all());

        return response()->json(['data' => $user], 200);
    }

    public function update_password(UpdateUserPasswordRequest $request, User $user)
    {
        $user->password = bcrypt($request->validated()['password']);
        $user->save();
        return new UserResource($user);
    }
    public function show_me(Request $request)
    {
        return new UserResource($request->user());
    }

   /* public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (AuthController::attempt($credentials)) {
            $user = AuthController::user();
            $tokenResult = $user->createToken('AuthToken');
            $accessToken = $tokenResult->accessToken;
            $expiresAt = $tokenResult->token->expires_at;

            return response()->json([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_at' => $expiresAt->toDateTimeString(),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo_url' => $user->photo_url,
                ],
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }*/
}