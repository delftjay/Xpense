<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'invite_code',
        'mobile',
        'mobile_verified'
    ];

    protected $visible = [
        'id',
        'name',
        'authentication_id',
        'email_verified',
        'mobile_verified',
        'avatar',
    ];

    protected $casts = [
        'email_verified' => 'boolean',
        'mobile_verified' => 'boolean',
        'avatar' => 'array',
        'register_ips' => 'collection',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获取与用户关联的空投记录。
     */
    public function airport()
    {
        return $this->hasOne(Airdrop::class);        
    }
}
