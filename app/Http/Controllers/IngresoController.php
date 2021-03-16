<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;

use Carbon\Carbon;

class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingresos = Ingreso::all();
        $ingresos->load(['persona', 'sala']);
        return $ingresos;
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
            'sala_id' => 'required'
        ];
        $mensajes = [
            'persona_id.required' => 'Se debe elegir un usuario',
            'sala_id.required' => 'Se debe elegir una sala'
        ];

        $validator = Validator::make($data, $reglas, $mensajes);
        if ($validator->passes()) {

            $pudoIngresar = (array_key_exists("pudo_ingresar", $data)) ? $data["pudo_ingresar"] : true;
            Ingreso::create([
                'persona_id' => $data["persona_id"],
                'sala_id' => $data["sala_id"],
                'pudo_ingresar' => $pudoIngresar
            ]);
            return ['mensaje' => 'Se han inscrito al usuario en la carrera.'];
        } else {
            return ['error' => $validator->errors()->all()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function show(Ingreso $ingreso)
    {
        $ingreso->load(['persona', 'sala']);
        return $ingreso;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function edit(Ingreso $ingreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ingreso $ingreso)
    {
        $data = $request->all();

        if ($data["pudo_ingresar"]) $ingreso->pudo_ingresar = $data["pudo_ingresar"];
        if ($data["invalido"]) $ingreso->invalido = $data["invalido"];

        if ($data["horaSalida"] && $data["horaSalida"] == true) $ingreso->horaSalida = Carbon::now()->timestamp;

        $ingreso->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ingreso  $ingreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingreso $ingreso)
    {
        $ingreso->delete();
        return ['mensaje' => 'Se ha eliminado al usuario.'];
    }
}
