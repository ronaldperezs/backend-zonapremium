<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plataforma extends Model
{
    protected $table = 'plataformas';
    protected $fillable = [
        'nombre'
    ];

    public function planes()
    {
        return $this->hasMany(Plan::class,'plataforma_id');
    }
}
