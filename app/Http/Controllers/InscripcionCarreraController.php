<?php

namespace App\Http\Controllers;

use App\Models\InscripcionCarrera;
use Illuminate\Http\Request;

use Validator;

class InscripcionCarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inscripciones = InscripcionCarrera::all();

        $inscripciones->load(['persona','carrera', 'rol']);
        return $inscripciones;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $data = $request->all();
        $reglas = [
            'persona_id' => 'required',
            'carrera_id' => 'required',
            'rol_id' => 'required'
        ];
        $mensajes = [
            'persona_id.required' => 'Se debe elegir un usuario',
            'carrera_id.required' => 'Se debe elegir una carrera',
            'rol_id.required' => 'Se debe elegir un rol'
        ];

        $validator = Validator::make($data, $reglas, $mensajes);
        if ($validator->passes()) {
            InscripcionCarrera::create([
                'persona_id' => $data["persona_id"],
                'carrera_id' => $data["carrera_id"],
                'rol_id' => $data["rol_id"]
            ]);
            return ['mensaje' => 'Se han inscrito al usuario en la carrera.'];
        } else {
            return ['error' => $validator->errors()->all()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InscripcionCarrera  $inscripcionCarrera
     * @return \Illuminate\Http\Response
     */
    public function show($inscripcionCarrera)
    {
        $inscripcion = InscripcionCarrera::find($inscripcionCarrera);
        $inscripcion->load(['persona','carrera', 'rol']);
        return $inscripcion;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InscripcionCarrera  $inscripcionCarrera
     * @return \Illuminate\Http\Response
     */
    public function edit(InscripcionCarrera $inscripcionCarrera)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InscripcionCarrera  $inscripcionCarrera
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InscripcionCarrera $inscripcionCarrera)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InscripcionCarrera  $inscripcionCarrera
     * @return \Illuminate\Http\Response
     */
    public function destroy(InscripcionCarrera $inscripcionCarrera)
    {
        $inscripcionCarrera->delete();
        return ['mensaje' => 'Se ha eliminado la inscripción.'];
    }
}
