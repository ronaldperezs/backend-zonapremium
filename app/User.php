<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'usuarios';
    protected $fillable = [
        'email', 'nombres', 'apellidos', 'celular','tipo_usuario','saldo'
    ];
    protected $hidden = [
        'password', 'api_token',
    ];

    public function suscripciones()
    {
        return $this->hasMany(Suscription::class,'usuario_id');
    }
}
