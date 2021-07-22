<?php

namespace App\Http\Controllers;

use App\Suscription;
use App\User;
use App\Plan;
use App\Dto\SolicitudPago;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;

class ComprasController extends Controller
{
    private $ApiKey;
    private $merchantId;
    private $accountId;

    // listar mis compras
    public function index(Request $request)
    {
        $usuario = $request->user();
        return $usuario->suscripciones();
    }

    // ver compra especifica
    public function show(Request $request, $id)
    {
        $usuario = $request->user();
        $suscripcion = Suscription::findOrFail($id);
        if ($suscripcion->usuario()->id != $usuario->id) {
            return response()->json("Unauthorized", 401);
        }
        return $suscripcion;
    }

    public function comprarConSaldo(Request $request, $id)
    {
        $usuario = $request->user();
        $plan = Plan::findOrFail($id);
        
        $precio_plan;
        if ($usuario->tipo_usuario == 'revendedor' && $plan->precio_revendedor!=null) {
            $precio_plan = $plan->precio_revendedor;
        } elseif ($usuario->tipo_usuario == 'distribuidor' && $plan->precio_distribuidor!=null) {
            $precio_plan = $plan->precio_distribuidor;
        } else {
            $precio_plan = $plan->precio;
        }
        if ($usuario->saldo < $precio_plan) {
            $saldo_insuficiente = "saldo insuficiente";
            return response()->json($saldo_insuficiente, 400);
        }

        $suscripciones = $plan->suscripciones;
        $idSuscripcion = "";
        foreach ($suscripciones as $suscripcion) {
            if ($suscripcion->usuario == null) {
                $idSuscripcion = $suscripcion->id;
                break;
            }
        }
        if ($idSuscripcion == "") {
            return response()->json("suscripción no disponible", 400);
        }

        $suscripcion = Suscription::findOrFail($idSuscripcion);
        if ($suscripcion->usuario == null) {
            $suscripcion->usuario_id = $usuario->id;
            $dtz = new DateTimeZone("America/Bogota");
            $dt = new DateTime("now", $dtz);
            $suscripcion->fecha_compra = $dt;
            $usuario->saldo = $usuario->saldo -  $precio_plan;
            $usuario->save();
            $suscripcion->save();
            return response()->json("OK", 200);
        }
    }

    // solicitud pago payu
    public function create(Request $request)
    {
        $ApiKey = env('ApiKey');
        $merchantId = env('merchantId');
        $accountId = env('accountId');

        $usuario = $request->user();
        $plan = Plan::findOrFail($request->plan_id);
        $suscripciones = $plan->suscripciones;
        $idSuscripcion = "";
        foreach ($suscripciones as $suscripcion) {
            if ($suscripcion->usuario == null) {
                $idSuscripcion = $suscripcion->id;
                break;
            }
        }
        $error = "plan no disponible";
        if ($idSuscripcion == "") {
            return response()->json($error, 400);
        }

        $solicitudPago = new SolicitudPago();
        $solicitudPago->merchantId = $merchantId;
        $solicitudPago->accountId = $accountId;
        $solicitudPago->description = "Test PAYU";
        $solicitudPago->referenceCode = date('Y-m-d H:i:s')."U".$usuario->id."P".$plan->id."S".$idSuscripcion;
        if ($usuario->tipo_usuario == 'revendedor' && $plan->precio_revendedor!=null) {
            $solicitudPago->amount = $plan->precio_revendedor;
        } else if ($usuario->tipo_usuario == 'distribuidor' && $plan->precio_distribuidor!=null) {
            $solicitudPago->amount = $plan->precio_distribuidor;
        } else {
            $solicitudPago->amount = $plan->precio;
        }
        $solicitudPago->tax = 0;
        $solicitudPago->taxReturnBase = 0;
        $solicitudPago->currency = "COP";
        $signature = $ApiKey."~".$solicitudPago->merchantId."~".$solicitudPago->referenceCode."~".$solicitudPago->amount."~".$solicitudPago->currency;
        $solicitudPago->signature = md5($signature);
        $solicitudPago->buyerEmail = $usuario->email;
        $solicitudPago->responseUrl = $request->getSchemeAndHttpHost()."/response";
        $solicitudPago->confirmationUrl = "";

        return response()->json($solicitudPago, 200);
    }

    // response payu
    public function response(Request $request)
    {
        $ApiKey = env('ApiKey');
        $merchantId = env('merchantId');
        $accountId = env('accountId');

        $url = env('urlConfirmation');
        $New_value = number_format($request->TX_VALUE, 1, '.', '');
        $firma_cadena = $ApiKey."~".$request->merchantId."~".$request->referenceCode."~".$New_value."~".$request->currency."~".$request->transactionState;
        $firmacreada = md5($firma_cadena);

        if (strtoupper($request->signature) != strtoupper($firmacreada)) {
            return redirect()->to($url."ERROR_FIRMA");
        }

        if ($request->transactionState == 6) {
            return redirect()->to($url."RECHAZADA");
        }

        if ($request->transactionState == 7) {
            return redirect()->to($url."PENDIENTE");
        }

        if ($request->transactionState != 4) {
            return redirect()->to($url."ERROR");
        }

        $idUsuario = substr($request->referenceCode, strpos($request->referenceCode, "U")+1, strpos($request->referenceCode, "P")-1);
        $usuario = User::findOrFail($idUsuario);
        $idPlan = substr($request->referenceCode, strpos($request->referenceCode, "P")+1, strpos($request->referenceCode, "S")-1);
        $idSuscripcion = substr($request->referenceCode, strpos($request->referenceCode, "S")+1, strlen($request->referenceCode)-1);
        $suscripcion = Suscription::findOrFail($idSuscripcion);
        if ($suscripcion->usuario == null) {
            $suscripcion->usuario_id = $usuario->id;
            $dtz = new DateTimeZone("America/Bogota");
            $dt = new DateTime("now", $dtz);
            $suscripcion->fecha_compra = $dt;
            $suscripcion->save();
            return redirect()->to($url."SUCCESS");
        }
        if ($suscripcion->usuario == $usuario) {
            return redirect()->to($url."SUCCESS");
        }

        $plan = Plan::findOrFail($idPlan);
        $suscripciones = $plan->suscripciones;
        $suscripcion2;
        foreach ($suscripciones as $s) {
            if ($s->usuario == null) {
                $suscripcion2 = $s;
                break;
            }
        }
        if ($suscripcion2 != null) {
            $suscripcion2->usuario_id = $usuario->id;
            $dtz = new DateTimeZone("America/Bogota");
            $dt = new DateTime("now", $dtz);
            $suscripcion2->fecha_compra = $dt;
            $suscripcion2->save();
            return redirect()->to($url."SUCCESS");
        }

        return redirect()->to($url."ERROR_ADMIN");
    }

    // confirmation payu
    public function confirmation(Request $request)
    {
        return response()->json("OK", 200);
    }
}
