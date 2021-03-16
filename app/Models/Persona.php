<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function inscripciones()
    {
        return $this->hasMany(InscripcionCarrera::class);
    }

    public function ingresosActivos()
    {
        return $this->ingresos()->whereNull('horaSalida');
    }

    public function ingresos()
    {
        return $this->ingresosTotales()->where('pudo_ingresar', true)->where('invalido', false);
    }

    public function ingresosTotales()
    {
        return $this->hasMany(Ingreso::class);
    }
}
