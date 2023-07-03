<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $rules = array(

        'pelicula_id' => 'required',
        'calificacion' => 'required',
    );
    public $mensajes = array(

        'pelicula_id.required' => 'El campo pelicula es requerido.',
        'calificacion.required' => 'El campo calificacion es requerido.',
    );
    public $rulesgetCalificacion = array(
        'pelicula_id' => 'required',
    );
    public $mensajesgetCalificacion = array(
        'pelicula_id.required' => 'El campo pelicula es requerido.',
    );
    public function index()
    {
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
        $calificacionExistente = Calificacion::where('user_id', $usuario->id)
        ->where('pelicula_id', $request->pelicula_id)
        ->first();
        if ($calificacionExistente) {
            return response()->json(['error' => 'El usuario ya le ha dado calificacion a esta película'], 500);
        }
        try {
            Calificacion::create([
                'user_id' => $usuario->id,
                'pelicula_id' => $request->pelicula_id,
                'calificacion' => $request->calificacion,
            ]);
            return response()->json(['message' => 'Se creo  con exito.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Calificacion  $calificacion
     * @return \Illuminate\Http\Response
     */
    public function show($pelicula_id)
    {
        try {
            $calificaciones = Calificacion::with('Usuario')->where('pelicula_id', '=', $pelicula_id)->get();
            $promedio = Calificacion::where('pelicula_id', '=', $pelicula_id)->avg('calificacion');
            return response()->json(['calificaciones' => $calificaciones, "promedio" => $promedio]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Calificacion  $calificacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calificacion $calificacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Calificacion  $calificacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calificacion $calificacion)
    {
        //
    }
}
