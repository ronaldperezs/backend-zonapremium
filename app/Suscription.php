<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suscription extends Model
{
    protected $table = 'suscripciones';
    protected $fillable = [
        'email', 'password','usuario_id'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
