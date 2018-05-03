<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $table = 'CAT_RECIBO';
    protected $primaryKey = 'ID_RECIBO';
    public $timestamps = false;
    protected $fillable = [
        'ID_RECIBO', 'ID_CENTRO', 'ID_USUARIO', 'RECIBO', 'NUMERO', 'FECHA', 'status', 'url'
    ];

    public function desgloce() {
        return $this->hasMany(Desgloce::class, 'ID_RECIBO', 'ID_RECIBO');
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'ID_USUARIO', 'ID_USUARIO')->select([
            'ID_USUARIO', 'ID_TIPO_LOGIN', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'CORREO', 'TELEFONO',
            'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO',
        ]);
    }

    public function centro() {
        return $this->hasOne(Centro::class, 'ID_CENTRO', 'ID_CENTRO');
    }
}
