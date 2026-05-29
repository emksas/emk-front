<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'egreso';
     public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        
        'valor', 
        'descripcion', 
        'fecha',
        'estado', 
        'planificacion_financiera_id', 
        'cuentacontable_id'
    ]; 
}
