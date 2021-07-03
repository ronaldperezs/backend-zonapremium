<?php

namespace App\Http\Controllers;

use App\Dto\AuthResponse;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OAuth2Controller extends Controller
{
    function authorizate(Request $request)
    {
        try {
            $headerAuthorization = explode(" ", $request->header('Authorization'));
            if ($headerAuthorization[0] != 'Basic') {
                return response()->json(['code' => 400, 'message' => 'tipo de autenticacion no soportada'], 400);
            }

            $auth_basic = base64_decode($headerAuthorization[1]);
            $credentials = explode(':', $auth_basic);
            $user = User::where('email', $credentials[0])->where('password', $credentials[1])->first();
            if (is_null($user)) {
                return response()->json(['code' => 400, 'message' => 'credenciales invalidas'], 400);
            }
            $user->api_token = Crypt::encrypt(rand());
            $user->save();
            $authResponse = new AuthResponse;
            $authResponse->access_token = $user->api_token;
            $authResponse->token_type = 'Bearer';
            $authResponse->expires_in = 3600;
            $authResponse->scope = $user->tipo_usuario;
            return response()->json($authResponse, 200);
        } catch (Exception $e) {
            return response()->json(['code' => 400, 'message' => 'Error en la peticion'], 400);
        }
    }
}
