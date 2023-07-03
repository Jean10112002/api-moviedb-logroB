<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    public $rules = array(

        'pelicula_id' => 'required',
        'comentario' => 'required',
    );
    public $mensajes = array(

        'pelicula_id.required' => 'El campo pelicula es requerido.',
        'comentario.required' => 'El campo comentario es requerido.',
    );
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try {
            Comentario::create([
                'user_id' => $usuario->id,
                'pelicula_id' => $request->pelicula_id,
                'comentario' => $request->comentario,
            ]);
            return response()->json(['message' => 'Se creo  con exito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function show($pelicula_id)
    {
        try {
            $comentarios = Comentario::with('Usuario')->where('pelicula_id', '=', $pelicula_id)->get();
            return response()->json(['comentarios' => $comentarios]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comentario $comentario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comentario $comentario)
    {
        //
    }
}
