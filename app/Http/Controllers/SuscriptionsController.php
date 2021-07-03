<?php

namespace App\Http\Controllers;

use App\Suscription;
use Illuminate\Http\Request;

class SuscriptionsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $suscripciones = [];
        if ($user->tipo_usuario == "admin") {
            $suscripciones = Suscription::all();
            return response()->json($suscripciones, 200);
        }
        $suscripciones = $user->suscripciones;
        return response()->json($suscripciones, 200);
    }

    public function show($id)
    {
        return Suscription::findOrFail($id);
    }

    public function create(Request $request)
    {
        $suscripcion = new Suscription;
        $suscripcion->email = $request->email;
        $suscripcion->password = $request->password;
        $suscripcion->plan_id = $request->plan_id;
        $suscripcion->save();
        return response()->json($suscripcion, 201);
    }

    public function update(Request $request, $id)
    {
        $suscripcion = Suscription::findOrFail($id);
        $suscripcion->email = $request->email;
        $suscripcion->password = $request->password;
        $suscripcion->plan_id = $request->plan_id;
        $suscripcion->save();
        return response()->json($suscripcion, 200);
    }

    public function delete($id)
    {
        $suscripcion = Suscription::findOrFail($id);
        $suscripcion->estado = "Eliminar";
        $suscripcion->save();
        return response()->json($id, 200);
    }
}
