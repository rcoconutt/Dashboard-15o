<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'CAT_MARCA';
    protected $primaryKey = 'ID_MARCA';

    protected $fillable = [
        'ID_MARCA', 'ID_PAIS', 'ID_DESTILADO', 'MARCA', 'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO',
    ];

    public function destilado() {
        return $this->hasOne(Destilado::class, 'ID_DESTILADO', 'ID_DESTILADO');
    }
}
