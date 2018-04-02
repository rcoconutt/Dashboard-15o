<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desgloce extends Model
{
    protected $table = 'CTL_DESGLOSE_RECIBO';
    protected $fillable = [
        'ID_DESGLOSE_RECIBO', 'ID_RECIBO', 'ID_MARCA', 'ID_TIPO_CONSUMO', 'CANTIDAD', 'FECHA',
    ];

    protected $primaryKey = 'ID_DESGLOSE_RECIBO';

    public function recibo() {
        return $this->hasOne(\App\Recibo::class, 'ID_RECIBO', 'ID_RECIBO')->select(['ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'NUMERO', 'FECHA']);
    }

    public function marca() {
        return $this->hasOne(Marca::class, 'ID_MARCA', 'ID_MARCA');
    }

    public function consumo() {
        return $this->hasOne(TipoConsumo::class, 'ID_TIPO_CONSUMO', 'ID_TIPO_CONSUMO');
    }
}
