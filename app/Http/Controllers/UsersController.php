<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function misaldo(Request $request)
    {
        $user = $request->user();
        return response()->json($user->saldo, 200);
    }

    public function create(Request $request)
    {
        $usuario = new User;
        $usuario->email = $request->email;
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->celular = $request->celular;
        $usuario->tipo_usuario = 'usuario';
        $usuario->password = $request->password;
        $usuario->save();
        return response()->json($usuario, 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $usuario->email = $request->email;
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->celular = $request->celular;
        $usuario->saldo = $request->saldo;
        $usuario->tipo_usuario = $request->tipo_usuario;
        if ($request->password != null) {
            $usuario->password = $request->password;
        }
        $usuario->save();
        return response()->json($usuario, 200);
    }

    public function delete($id)
    {
        User::destroy($id);
        return response()->json($id, 200);
    }
}
