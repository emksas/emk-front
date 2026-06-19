<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userType extends Model
{
    protected $table = 'tipo_usuario';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
    ];
}
