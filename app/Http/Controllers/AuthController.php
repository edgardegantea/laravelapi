<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;



class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
        $user = New User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'status'    => 'ok',
            'data'      => $user
        ], 200);

    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        $jwt_token = null;

        if (! $jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'status'    => 'Inicio de sesión fallido',
                'message'   => 'Su email y/o contraseña es incorrecto (a)'
            ], 401);
        }

        return response()->json([
            'message'   => 'Sesión iniciada',
            'status'    => 'ok',
            'token'     => $jwt_token
        ]);

    }

    public function logout(Request $request)
    {
        $this->validate($request, [
           'token'  => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'status'    => 'ok',
                'message'   => 'La sesión finalizó con éxito'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status'    => 'ok',
                'message'   => 'Oops! la sesión no puedo cerrarse, intente de nuevo'
            ], 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token'  => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        return response()->json(['user' => $user]);
    }

    protected function jsonResponse($data, $code = 200)
    {
        return response()->json(
            $data,
            $code,
            [
                'Content-Type'  => 'application/json;charset=UTF-8',
                'Charset'       => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE
        );
    }

}
