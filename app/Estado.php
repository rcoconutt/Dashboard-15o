<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'CAT_ESTADO';
    protected $primaryKey = 'ID_ESTADO';
    public $timestamps = false;

    protected $fillable = [
        "ID_ESTADO","ID_PAIS","ESTADO","ISO_3166_2","FECHA_ALTA","FECHA_BAJA","ACTIVO"
    ];
}
