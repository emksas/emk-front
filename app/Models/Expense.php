<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'egreso';
     public $timestamps = false;
    protected $primaryKey = 'idegreso';

    protected $fillable = [
        
        'valor', 
        'descripcion', 
        'fecha',
        'estado', 
        'idPlanificacion', 
        'cuentaContable_id'
    ]; 
}
