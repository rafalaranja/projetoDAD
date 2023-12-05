<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\userTable;
use App\Models\VCard;
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

    public function update(UpdateUserRequest $request, UserTable $user)
    {
        $dataToSave = $request->validated();

        $base64ImagePhoto = array_key_exists("base64ImagePhoto", $dataToSave) ?
            $dataToSave["base64ImagePhoto"] : ($dataToSave["base64ImagePhoto"] ?? null);
        $deletePhotoOnServer = array_key_exists("deletePhotoOnServer", $dataToSave) && $dataToSave["deletePhotoOnServer"];
        unset($dataToSave["base64ImagePhoto"]);
        unset($dataToSave["deletePhotoOnServer"]);

        $user->fill($dataToSave);

        // Delete previous photo file if a new file is uploaded or the photo is to be deleted
        if ($user->photo_url && ($deletePhotoOnServer || $base64ImagePhoto)) {
            if (Storage::exists('public/fotos/' . $user->photo_url)) {
                Storage::delete('public/fotos/' . $user->photo_url);
            }
            $user->photo_url = null;
        }

        // Create a new photo file from base64 content
        if ($base64ImagePhoto) {
            $user->photo_url = $this->storeBase64AsFile($user, $base64ImagePhoto);
        }
        $user->save();
        return new UserResource($user);
    }

    public function update_password(UpdateUserPasswordRequest $request, $id)
    {
        if (strlen($id) == 1) {
            $user = userTable::find($id);
        } else if (strlen($id) == 9) {
            $user = VCard::find($id);
        }
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
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