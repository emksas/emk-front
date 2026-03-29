<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $table = 'ingreso';
    public $timestamps = false;

    protected $primaryKey = 'id'; // 👈 CORRECTO según tu BD

    protected $fillable = [
        'valor',
        'fuente',
        'categoria',
        'metododepago',
        'fecha',
        'referencia',
        'planificacionfinanciera_id',
        'cuentacontable_id'
    ];
}