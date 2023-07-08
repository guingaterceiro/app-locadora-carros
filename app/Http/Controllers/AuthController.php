<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        $credenciais = $request->all(['email', 'password']);

        $token = auth('api')->attempt($credenciais);
        if($token){
            return response()->json(['token'=>$token]);
        }else{
            return response()->json(['erro' => 'Usuário ou senha inválido não encontrado!'], 403) ;

        }
        // dd($token);
        return 'login';
    }

    // invalida o token
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout realizado!']);
    }

    //Atualiza o token
    public function refresh()
    {
        $token = auth('api')->refresh();
        return response()->json(['token' => $token]);
    }

    // tras os dados do usuário
    public function me()
    {
        return response()->json(auth()->user());
    }
}
