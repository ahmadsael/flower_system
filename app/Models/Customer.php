<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guard = "customer";

   protected $fillable = [
       'name',
       'email',
       'email_verified_at',
       'password',
       'gender',
       'status',
       'age',
       'birthday',
       'phone',
       'img',
       'created_by',
       'updated_by',
  ];

   public function createdBy()
   {
       return $this->belongsTo(Admin::class , 'created_by');
   }

   public function updatedBy()
   {
       return $this->belongsTo(Admin::class , 'updated_by');
   }

   public function favorites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
   {
       return $this->belongsToMany(Product::class, 'customer_favorites');
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
