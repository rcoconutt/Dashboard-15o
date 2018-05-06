<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'CAT_MUNICIPIO';
    protected $primaryKey = 'ID_MUNICIPIO';
    public $timestamps = false;

    protected $fillable = [
        "ID_MUNICIPIO","ID_ESTADO","ID_ZONA","MUNICIPIO","ABREVIATURA","FECHA_ALTA","FECHA_BAJA","ACTIVO"
    ];

    public function estado() {
        return $this->hasOne(Estado::class, 'ID_ESTADO', 'ID_ESTADO');
    }
}
