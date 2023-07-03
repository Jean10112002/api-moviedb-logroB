<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private  $rules = array(
        'nickname' => 'required|regex:"^[ a-zA-ZñÑáéíóúÁÉÍÓÚ]+$"|max:10',
        'email' => 'required|email|unique:users',
        'password' => 'required',
        'categoria' => 'required',
    );
    private $messages = array(
        'nickname.required' => 'Nickname es requerido.',
        'nickname.max' => 'Nickname máximo 10 caracteres.',
        'nickname.regex' => 'No debe ser un numero, solo letras.',
        'email.unique' => 'Ya existe ese email.',
        'email.required' => 'Email es requerido.',
        'email.email' => 'Debe ser un email correcto.',
        'password.required' => 'Debe ingresar una password',
        'categoria.required' => 'Debe ingresar la categoria para recomendar',
    );
    private  $rulesLogin = array(
        'email' => 'required|email',
        'password' => 'required'
    );
    private $messagesLogin = array(
        'email.unique' => 'ya existe ese email.',
        'email.required' => 'email es requerido.',
        'email.email' => 'debe ser un email correcto.',
        'password.required' => 'debe ingresar una password',
    );
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["message" => $messages], 500);
        }
        $email = User::where("email", "=", $request->email)->first();
        if ($email) {
            return response()->json([
                "message" => "Email ya existente"
            ], 500);
        }
        $nickname = User::where("nickname", "=", $request->nickname)->first();
        if ($nickname) {
            return response()->json([
                "message" => "Nickanme ya existente"
            ], 500);
        }
        $user = new User();
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->categoria = $request->categoria;
        $user->save();

        return response()->json([
            "status" => 1,
            "message" => "¡Registro de usuario exitoso!",
        ]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rulesLogin, $this->messagesLogin);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(["message" => $messages], 500);
        }

        $user = User::where("email", "=", $request->email)->first();
        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                //creamos el token
                $token = $user->createToken("auth_token")->plainTextToken;
                //si está todo ok
                return response()->json([

                    "message" => "¡Usuario logueado exitosamente!",
                    "access_token" => $token
                ], 200);
            } else {
                return response()->json([
                    "status" => 0,
                    "error" => "credenciales incorrectas",
                ], 500);
            }
        } else {
            return response()->json([
                "status" => 0,
                "error" => "Usuario no registrado",
            ], 404);
        }
    }

    public function userProfile()
    {
        $usuario = Auth::guard('sanctum')->user();

        return response()->json([
            "message" => "Acerca del perfil de usuario",
            "user" => $usuario,
            /* "usuario"=>$user, */
        ]);
    }

    public function logout()
    {
        /*  auth()->user()->tokens()->delete(); */
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "Cierre de Sesión",
        ]);
    }
    public function show($user_id)
    {
        try {
            $user = User::find($user_id);
            return response()->json([
                "user" => $user
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);

        }
    }
}
