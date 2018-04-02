<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'CAT_CENTRO';
    protected $primaryKey = 'ID_CENTRO';
    protected $fillable = [
        'ID_CENTRO', 'ID_MUNICIPIO', 'CENTRO', 'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO',
    ];
}
