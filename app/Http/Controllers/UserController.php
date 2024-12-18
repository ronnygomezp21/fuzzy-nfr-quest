<?php

namespace App\Http\Controllers;

use App\GeneralResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use GeneralResponse;

    public function UserProfile()
    {
        try {
            $userData = Auth::user();
            $userData->role;
            return $this->generalResponse($userData, 'Proceso exitoso.', 200);
        } catch (\Throwable $th) {
            return $this->generalResponseWithErrors($th);
        }
    }
}
