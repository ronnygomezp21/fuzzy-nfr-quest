<?php

namespace App\Http\Controllers;

use App\GeneralResponse;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
  use GeneralResponse;

  public function login(LoginUserRequest $request)
  {

    try {
      $credentials = $request->only('username', 'password');
      $token = null;

      if (!$token = JWTAuth::attempt($credentials)) {
        return $this->generalResponseWithErrors('Usuario o contraseña incorrectos', 401);
      } else {
        $user = Auth::user();
        $user->role;
        $data = [
          'user' => $user,
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => Auth::factory()->getTTL() * 60
        ];
        return $this->generalResponse($data, 'Proceso exitoso');
      }
    } catch (JWTException $e) {
      return $this->generalResponseWithErrors($e->getMessage());
    }
  }

  public function logout()
  {
    try {
      JWTAuth::invalidate(JWTAuth::getToken());

      return $this->generalResponse(null, 'Se ha cerrado la sesión correctamente.');
    } catch (JWTException $e) {
      return $this->generalResponseWithErrors('Error al cerrar la sesión, por favor intenta nuevamente.');
    }
  }

  public function register(RegisterUserRequest $request)
  {
    try {
      User::create([
        'name' => $request->name,
        'last_name' => $request->last_name,
        'username' => $request->username,
        'email' => $request->email,
        'birth_date' => $request->birth_date,
        'password' => Hash::make($request->password),
        'role_id' => $request->role,
      ]);
      return $this->generalResponse(null, 'Usuario registrado con éxito', 201);
    } catch (\Throwable $th) {
      return $this->generalResponseWithErrors('Error al registrar el usuario');
    }
  }
}
