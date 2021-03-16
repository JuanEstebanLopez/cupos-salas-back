<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\TipoSala;
use App\Models\Area;
use App\Models\Persona;
use Illuminate\Http\Request;

use Validator;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salas = Sala::all();
        $salas->load('tipoSala');
        return $salas;
    }

    public function puedeIngresar(Sala $sala, $cedula)
    {
        $mensaje = 'No se encuentra la cédula registrada';
        $sala->load('tipoSala');
        $persona =  Persona::where('cedula', $cedula)->first();
        $persona->load(['ingresosActivos', 'inscripciones']);
        if (!$persona) return ['error' => $mensaje];
        $puedeIngresar = false;
        if ($sala->tipoSala == "Rol") {
            $puedeIngresar = $persona->inscripciones()->where('rol_id', $sala->propietarioSala->id)->exists ();
            $mensaje = $persona->nombre . (($puedeIngresar) ? " es " : " no es ") . $sala->propietarioSala->nombre;
        } else {            
            $puedeIngresar = $persona->inscripciones()->where('carrera_id', $sala->propietarioSala->id)->exists ();
            $mensaje = $persona->nombre . (($puedeIngresar) ? " pertenece a " : " no pertenece a ") . $sala->propietarioSala->nombre;
        }

        return ['persona' => $persona, 'sala' => $sala, 'puede_ingresar' => $puedeIngresar, 'mensaje' => $mensaje];
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
            'nombre' => 'required',
            'capacidad' => 'required',
            'tipo_sala_id' => 'required',
            'propietario_sala_id' => 'required'
        ];
        $mensajes = [
            'nombre.required' => 'Se requiere un nombre para la sala',
            'capacidad.required' => 'Se requiere la capacidad de personas de la sala',
            'tipo_sala_id.required' => 'Se debe especificar el tipo de sala que es',
            'propietario_sala_id.required' => 'Se debe indicar quién tiene acceso a la sala'
        ];

        $validator = Validator::make($data, $reglas, $mensajes);
        if ($validator->passes()) {
            $salaNombreRepetido = Sala::where('nombre', $data['nombre'])->first();
            if ($salaNombreRepetido) return ['mensaje' => 'Ya existe una sala con el nombre "' . $data['nombre'] . '"'];
            $equipos = (array_key_exists("equipos", $data)) ? $data["equipos"] : $data["capacidad"];
            Sala::create([
                'nombre' => $data["nombre"],
                'capacidad' => $data["capacidad"],
                'tipo_sala_id' => $data["tipo_sala_id"],
                'propietario_sala_id' => $data["propietario_sala_id"],
                'equipos' => $equipos
            ]);
            return ['mensaje' => 'Se ha creado  la sala.'];
        } else {
            return ['error' => $validator->errors()->all()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function show(Sala $sala)
    {
        $sala->load('tipoSala');
        return $sala;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function edit(Sala $sala)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sala $sala)
    {
        $data = $request->all();

        if ($data["nombre"]) $sala->nombre = $data["nombre"];
        if ($data["capacidad"]) $sala->capacidad = $data["capacidad"];
        if ($data["tipo_sala_id"]) $sala->tipo_sala_id = $data["tipo_sala_id"];
        if ($data["propietario_sala_id"]) $sala->propietario_sala_id = $data["propietario_sala_id"];
        if ($data["equipos"]) $sala->equipos = $data["equipos"];

        $sala->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sala  $sala
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sala $sala)
    {
        $sala->delete();
        return ['mensaje' => 'Se ha eliminado  la sala.'];
    }
}
