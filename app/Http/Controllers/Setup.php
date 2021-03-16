<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\TipoSala;
use App\Models\Area;
use App\Models\Carrera;

use function PHPUnit\Framework\isEmpty;

class Setup extends Controller
{
    /**
     * Crea los registros iniciales para la base de datos
     */
    public function setup(Request $request)
    {

        $data = $request->all();
        $reglas = [
            'cedula' => 'required',
            'nombre' => 'required',
            'email' => 'required'
        ];
        $mensajes = [
            'cedula.required' => 'Se requiere la cédula del usuario',
            'nombre.required' => 'Se requiere el nombre del usuario',
            'email.required' => 'Se requiere un email'
        ];

        $validator = Validator::make($data, $reglas, $mensajes);
        if ($validator->passes()) {
            //TODO Handle your data

            Persona::create([
                'cedula' => $data["cedula"],
                'nombre' => $data["nombre"],
                'email' => $data["email"],
                'admin' => true
            ]);

            Rol::firstOrCreate(['nombre' => 'Profesor']);
            Rol::firstOrCreate(['nombre' => 'Estudiante']);

            TipoSala::firstOrCreate(['nombre' => 'Área']);
            TipoSala::firstOrCreate(['nombre' => 'Rol']);

            
            $area_ingenieria = Area::firstOrCreate(['nombre' => 'Ingeniería']);
            $area_disenio = Area::firstOrCreate(['nombre' => 'Diseño']);

            Carrera::firstOrCreate(['nombre' => 'Ingeniería de sistemas', 'area_id'=>$area_ingenieria->id]);
            Carrera::firstOrCreate(['nombre' => 'Ingeniería de industrial', 'area_id'=>$area_ingenieria->id]);
            Carrera::firstOrCreate(['nombre' => 'Ingeniería de telemática', 'area_id'=>$area_ingenieria->id]);

            Carrera::firstOrCreate(['nombre' => 'Diseño Industrial', 'area_id'=>$area_disenio->id]);
            Carrera::firstOrCreate(['nombre' => 'Diseño de medios Interactivos', 'area_id'=>$area_disenio->id]);

            return ['mensaje' => 'Se han iniciado los valores con éxito.'];
        } else {
            return ['error' => $validator->errors()->all()];
        }
    }
    /**
     * Indica si existen o no todos los campos iniciales para la aplicación.
     */
    public function issetup()
    {
        $res = ['setup' => false];

        $personas = Persona::where('admin', 1)->get();        
        if ($personas->count()==0) return $res;
        
        $roles = Rol::all();
        if ($roles->count()==0) return $res;
        
        $tipos = TipoSala::all();
        if ($tipos->count()==0) return $res;
        
        $areas = Area::all();
        if ($areas->count()==0) return $res;
        
        $carrera = Carrera::all();
        if ($carrera->count()==0) return $res;
        
        
        $res['setup'] = true;

        return $res;
    }
}
