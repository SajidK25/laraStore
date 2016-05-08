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

    public static function boot() {
        // Reference the parent class
        parent::boot();

        // When we are creating a record (for user registration),
        // then we want to set a token to some random string.
        static::creating(function($user) {
            $user->token = str_random(30);
        });
    }

    public function confirmEmail() {
        $this->verified = true;
        $this->token = null;
        $this->save();
    }
}
