<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'planes';
    protected $fillable = [
        'nombre', 'duracion', 'calidad', 'tipo_cuenta', 'descripcion', 'precio','precio_revendedor','plataforma_id'
    ];

    public function plataforma()
    {
        return $this->belongsTo(Plataforma::class);
    }
    public function suscripciones()
    {
        return $this->hasMany(Suscription::class);
    }
}
