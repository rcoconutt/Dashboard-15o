<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'CAT_USUARIO';
    protected $fillable = [
        'ID_USUARIO', 'ID_TIPO_LOGIN', 'ID_PUESTO', 'ID_CENTRO', 'NOMBRE', 'USERNAME', 'IMAGEN', 'CONTRASENA',
        'CORREO', 'TELEFONO', 'FECHA_ALTA', 'FECHA_BAJA', 'ACTIVO',
    ];

    protected $primaryKey = 'ID_USUARIO';

    public function recibos() {
        return $this->hasMany(Recibo::class, 'ID_USUARIO', 'ID_USUARIO');
    }
}