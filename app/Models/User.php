<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email',
        'password',
        'phone',
        'street',
        'street_nr',
        'apartment_nr',
        'postcode',
        'city',
        'country',
        'commission',
        'costs',
        'distribution'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => Hash::make($value),
        );
    }

    public function listings(): HasMany{
        return $this->hasMany(
            \App\Models\Listing::class,
            'by_user_id',
        );
    }

    public function offers(): HasMany{
        return $this->hasMany(
            \App\Models\Offer::class,
            'bidder_id',
        );
    }

    public function clients(): HasMany{
        return $this->hasMany(Client::class, 'created_by_user_id');
    }

    public function isVerified(): bool{
        if(isset($this->email_verified_at)){
            return true;
        }
        else{
            return false;
        }
   }
}
