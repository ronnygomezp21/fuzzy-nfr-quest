<?php

namespace App\Http\Controllers;

use App\GeneralResponse;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    use GeneralResponse;

    public function index()
    {
        try {
            $roles = Role::all();
            return $this->generalResponse($roles, 'Proceso exitoso.', 200);
        } catch (\Exception $e) {
            return $this->generalResponse(null, 'Ha ocurrido un error en el servidor, intentelo mas tarde.', 500);
        }
    }
}
