<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Consumer extends Authenticatable implements JWTSubject
{
    use  Notifiable;
    protected $guard = 'consumer-api';

    protected $table = 'consumers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
    ];

    protected $hidden = [
        'password',
        'remember_token',

    ];
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function cart()
    {
        // 1==>many
        return $this->hasOne(Cart::class);
    }

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

}
