<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table="users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password','admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot(){
      parent::boot();
      static::creating(function($user){
        $user->token->str_random(30);
      });
    }

    public function confirmEmail() {
        $this->verified = true;
        $this->token = null;
        $this->save();
    }
}
