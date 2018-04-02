<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoConsumo extends Model
{
    protected $table = 'CAT_TIPO_CONSUMO';
    protected $primaryKey = 'ID_TIPO_CONSUMO';

    protected $fillable = ['ID_TIPO_CONSUMO', 'TIPO_CONSUMO', 'FECHA_INICIO', 'FECHA_FIN', 'ACTIVO',];
}
