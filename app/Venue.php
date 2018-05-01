<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $table = 'CAT_CENTRO';
    protected $primaryKey = 'ID_CENTRO';
    public $timestamps = false;

    protected $fillable = [
        'ID_CENTRO', 'ID_MUNICIPIO', 'CENTRO', 'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO',
    ];
}
