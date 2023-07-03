<?php

namespace App\Http\Controllers;

use App\Models\Seguidor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SeguidorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $rules = array(

        'user_seguido_id' => 'required',
    );
    public $mensajes = array(
        'user_seguido_id.required' => 'El campo id de usuario a seguir es requerido.',
    );
    public function index()
    {
        $usuario = Auth::guard('sanctum')->user();
        try {
            $interacciones = Seguidor::with('UsuarioSeguido', 'UsuarioSeguidor')->where('user_seguido_id', '=', $usuario->id)->get();
            return response()->json(['interacciones' => $interacciones]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $usuario = Auth::guard('sanctum')->user();
        $validator = Validator::make($request->all(), $this->rules, $this->mensajes);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return response()->json([
                'message' => $messages
            ], 500);
        };
        if($request->user_seguido_id==$usuario->id){
            return response()->json([
                "message"=>"no puedes seguirte a ti mismo"
            ],500);
        }
        $favoritoExistente = Seguidor::where('user_seguido_id', $request->user_seguido_id)
        ->where('user_seguidor_id', $usuario->id)
        ->first();
        if ($favoritoExistente) {
            return response()->json(['error' => 'El usuario ya lo sigue'], 500);
        }
        try {
            Seguidor::create([
                'user_seguidor_id' => $usuario->id,
                'user_seguido_id' => $request->user_seguido_id,
            ]);
            return response()->json(['message' => 'Se creo  con exito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seguidor $seguidor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seguidor  $seguidor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Seguidor::findOrFail($id)->delete();
            return response()->json(['message' => 'Se eliminó con exito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
