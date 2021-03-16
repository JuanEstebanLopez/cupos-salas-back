<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;

use Validator;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personas = Persona::all();
        $personas->load(['inscripciones', 'inscripciones.rol', 'inscripciones.carrera']);
        return $personas;
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
            'cedula' => 'required',
            'nombre' => 'required',
            'email' => 'required',
            'profesor' => 'required'
        ];
        $mensajes = [
            'cedula.required' => 'Se requiere la cédula del usuario',
            'nombre.required' => 'Se requiere el nombre del usuario',
            'email.required' => 'Se requiere un email',
            'profesor.required' => 'Se debe indicar si el usuario es profesor o estudiante'
        ];

        $validator = Validator::make($data, $reglas, $mensajes);
        if ($validator->passes()) {

            $personaCedulaRepetida = Persona::where('cedula', $data['cedula'])->first();
            if($personaCedulaRepetida) return ['mensaje' => 'Ya existe una persona con cédula "'.$data['cedula'].'", su nombre es: '.$data["nombre"]];


            Persona::create([
                'cedula' => $data["cedula"],
                'nombre' => $data["nombre"],
                'email' => $data["email"],
                'profesor' => $data["profesor"]
            ]);
            return ['mensaje' => 'Se han creado el usuario.'];
        } else {
            return ['error' => $validator->errors()->all()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        $persona->load(['inscripciones', 'inscripciones.rol', 'inscripciones.carrera']);
        return $persona;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Persona $persona)
    {
        $data = $request->all();

        if ($data['cedula']) $persona->cedula = $data['cedula'];
        if ($data['nombre']) $persona->nombre = $data['nombre'];
        if ($data['email']) $persona->email = $data['email'];
        if ($data['profesor']) $persona->profesor = $data['profesor'];

        $persona->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        $persona->delete();
        return ['mensaje' => 'Se ha eliminado al usuario.'];
    }
}
