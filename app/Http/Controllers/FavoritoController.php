<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoritoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $rules = array(

        'pelicula_id'=>'required'
    );
    public $mensajes = array(

        'pelicula_id.required'=>'pelicula es requerida'
    );
    public function index()
    {
        $usuario = Auth::guard('sanctum')->user();
        try {
           $favoritos=Favorito::where('user_id',$usuario->id)->get();
           return response()->json([
            "favoritos"=>$favoritos
           ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
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
        $favoritoExistente = Favorito::where('user_id', $usuario->id)
        ->where('pelicula_id', $request->pelicula_id)
        ->first();
        if ($favoritoExistente) {
            return response()->json(['error' => 'El usuario ya le ha dado like a esta película'], 500);
        }
        try {
            Favorito::create([
                "pelicula_id"=>$request->pelicula_id,
                "user_id"=>$usuario->id
            ]);
            return response()->json([
                "message"=>"creado exitosamente"
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' =>$th->getMessage ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favorito  $favorito
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favorito  $favorito
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favorito $favorito)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorito  $favorito
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = Auth::guard('sanctum')->user();
        try {
           Favorito::findOrFail($id)->where('user_id','=',$usuario->id)->delete();
            return response()->json([
                'message' => 'Se Elimino con exito'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
