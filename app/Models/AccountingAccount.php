<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingAccount extends Model
{
    protected $table = 'cuentacontable';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'descripcion',
        'userId'
    ];
}
