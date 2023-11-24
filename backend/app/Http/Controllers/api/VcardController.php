<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VcardResource;
use Illuminate\Http\Request;
use App\Models\User;

class VcardController extends Controller
{
    public function getVCardOfUser(User $user){
        return new VcardResource($user);
    }
}
