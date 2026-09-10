<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    public function oauthProviders()
   {
    return $this->hasMany(OauthProvider::class);
    }

    public function postedJobs()
    {
     return $this->hasMany(JobPost::class);
    }

     public function submittedJobs()
    {
     return $this->hasMany(JobSubmit::class);
    }

    public function notifications()
    {
     return $this->hasMany(UserNotification::class);
    }

    public function transactions()
    {
    return $this->hasMany(UserTransaction::class);
    }

    public function hiddenJobs()
   {
    return $this->hasMany(HideJob::class);
   }
}
