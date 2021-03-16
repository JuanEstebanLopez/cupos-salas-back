<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['propietarioSala'];

    public function tipoSala(){
        return $this->belongsTo(TipoSala::class);
    }

    public function ingresos()
    {
        return $this->hasMany(Ingresos::class);
    }

    public function getPropietarioSalaAttribute(){
        $propietario = NULL;
        if($this->tipoSala->nombre =="Rol"){
            $rol = Rol::where('id', $this->propietario_sala_id)->first();
            if($rol) $propietario = $rol;
        }else{
            $area = Area::where('id', $this->propietario_sala_id)->first();
            if($area) $propietario = $area;
        }
        return $propietario;
    }
}
