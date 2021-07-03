<?php

namespace App\Http\Controllers;

use App\Plataforma;
use Illuminate\Http\Request;

class PlataformaController extends Controller
{
    function index() {
        $plataformas = Plataforma::all();
        return response()->json($plataformas, 200);
    }

    function show($id)
    {
        return Plataforma::findOrFail($id);
    }

    function planes($id)
    {
        $plataforma = Plataforma::findOrFail($id);
        return response()->json($plataforma->planes,200);
    }

    function create(Request $request)
    {
        $plataforma = new Plataforma();
        $plataforma->nombre = $request->nombre;
        $plataforma->save();
        return response()->json($plataforma, 201);
    }

    function update(Request $request, $id)
    {
        $plataforma = Plataforma::findOrFail($id);
        $plataforma->nombre = $request->nombre;
        $plataforma->save();
        return response()->json($plataforma, 200);
    }

    public function delete($id)
    {
        Plataforma::destroy($id);
        return response()->json($id, 200);
    }
}
