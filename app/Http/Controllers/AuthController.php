<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      try {
        return response()->json([
            'message' => 'Login Success',
            ///'token' => JWTAuth::fromUser($request->user())
        ]);
      } catch (\Throwable $th) {
        return response()->json([
            'message' => 'Login Failed',
            'error' => $th->getMessage()
        ]);
      }	
    }

    public function user(Request $request)
    {
       return response()->json([
           'message' => 'usser Success',
           //'token' => JWTAuth::fromUser($request->user())
       ]);	
    }
}
