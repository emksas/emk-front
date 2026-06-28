<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserType extends Model
{
    protected $table = 'tipo_usuario';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role');
    }
}
