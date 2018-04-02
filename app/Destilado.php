<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destilado extends Model
{
    protected $table = 'CAT_DESTILADO';
    protected $primaryKey = 'ID_DESTILADO';

    protected $fillable = [
        'ID_DESTILADO', 'ID_GRUPO', 'DESTILADO', 'IMAGEN', 'ANEJAMIENTO', 'CARACTERISTICAS',
        'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO',
    ];
}
