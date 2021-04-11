<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class SuperAdmin extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $table = 'super_admin';

    protected $fillable = [ 'username', 'email', 'status', 'avatar' ];

    protected $hidden = [ 'password', 'remember_token' ];

    protected $cast = [
        'email_verified_at' => 'datetime',
    ];
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value); 
    }
}
