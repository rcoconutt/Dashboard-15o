<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'CAT_BRAND';
    protected $primaryKey = 'ID_BRAND';

    public $timestamps = false;

    protected $fillable = [
        'ID_BRAND', 'BRAND'
    ];
}
