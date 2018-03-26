<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Dinamica extends Model
{
    protected $table = 'CAT_DINAMICA';
    protected $primaryKey = 'ID_DINAMICA';

    protected $fillable = [
        'ID_DINAMICA', 'ID_ZONA', 'ID_BRAND', 'OBJETIVO', 'DINAMICA', 'DESCRIPCION', 'PREMIO',
        'FECHA_INICIO', 'FECHA_FIN', 'ACTIVO',

        // New data
        'municipio_id', 'marca_id'
    ];

    public $timestamps = false;
}
