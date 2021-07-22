<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    function index()
    {
        $planes = Plan::all();
        return response()->json($planes, 200);
    }

    function show($id)
    {
        return Plan::findOrFail($id);
    }

    function create(Request $request)
    {
        $plan = new Plan();
        $plan->nombre = $request->nombre;
        $plan->duracion = $request->duracion;
        $plan->calidad = $request->calidad;
        $plan->tipo_cuenta = $request->tipo_cuenta;
        $plan->precio = $request->precio;
        $plan->precio_revendedor = $request->precio_revendedor;
        $plan->precio_distribuidor = $request->precio_distribuidor;     
        $plan->plataforma_id = $request->plataforma_id;
        $plan->descripcion = $request->descripcion;
        $plan->save();
        return response()->json($plan, 201);
    }

    function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->nombre = $request->nombre;
        $plan->duracion = $request->duracion;
        $plan->calidad = $request->calidad;
        $plan->tipo_cuenta = $request->tipo_cuenta;
        $plan->precio = $request->precio;
        $plan->precio_revendedor = $request->precio_revendedor;
        $plan->precio_distribuidor = $request->precio_distribuidor;   
        $plan->plataforma_id = $request->plataforma_id;
        $plan->descripcion = $request->descripcion;
        $plan->save();
        return response()->json($plan, 200);
    }

    public function delete($id)
    {
        Plan::destroy($id);
        return response()->json($id, 200);
    }
}
