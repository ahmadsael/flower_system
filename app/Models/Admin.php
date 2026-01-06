<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guard = "admin";

    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'birthday',
        'role_id',
        'created_by',
        'updated_by',
        'status',
        'type',
        'age',
        'gender',
        'img',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class , 'role_id');
    }

    public function farmers()
    {
        return $this->hasMany(Farmer::class , 'created_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class , 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class , 'updated_by');
    }

 
    
        protected $hidden = [
            'password',
            'remember_token',
        ];
    
   
        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'password' => 'hashed',
            ];
        }
}
