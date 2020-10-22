<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'score',
        'is_block',
        'level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'level'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(Role $role)
    {
        foreach ($this->roles()->get() as $userRoles) {
            if ($role->id == $userRoles->id){
                return true;
            }
        }
        return false;
    }

    public function hasRoleGate($roles)
    {
        if(is_string($roles)){
            return $this->roles->contains('title',$roles);
        }
        else{
            foreach ($roles as $role){
                return $this->hasRoleGate($role);
            }
        }
        return false;
    }

    public function assignRole(String $role)
    {
        $role = Role::where('title',$role)->firstOrFail();
        $this->roles()->attach($role);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
