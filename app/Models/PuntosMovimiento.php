<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntosMovimiento extends Model
{
    protected $fillable = [
        'user_id',
        'cantidad',
        'tipo',
        'descripcion',
        'referencia_loggro',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
