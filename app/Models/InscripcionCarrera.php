<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscripcionCarrera extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    
    public function persona(){
        return $this->belongsTo(Persona::class);
    }
    public function carrera(){
        return $this->belongsTo(Carrera::class);
    }
    public function rol(){
        return $this->belongsTo(Rol::class);
    }
    public function area(){
        return $this->carrera->area;
    }
}
