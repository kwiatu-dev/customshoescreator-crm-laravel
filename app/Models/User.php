<?php

namespace App\Models;

use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, HasFilters, HasSorting, HasPagination, SoftDeletes;

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

    //protected $appends = ['editable'];

    protected $searchable = [
        'first_name', 
        'last_name', 
        'email',
        'phone',
        'postcode',
        'city',
        'street',
        'street_nr',
        'apartment_nr',
        'country',
        'commission',
        'costs',
        'distribution',
    ];

    protected $filterable = [
        'search' => 'string',
        'others' => [
            ['deleted' => 'boolean'],
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'first_name', 
        'last_name', 
        'email',
        'street',
        'street_nr',
        'apartment_nr',
        'city',
        'country',
        'commission',
        'costs',
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
        'distribution' => 'array'
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => Hash::make($value),
        );
    }

    public function clients(): HasMany{
        return $this->hasMany(Client::class, 'created_by_user_id');
    }

    public function expenses(): HasMany{
        return $this->hasMany(Expenses::class, 'created_by_user_id');
    }

    public function projects(): HasMany{
        return $this->hasMany(Project::class, 'created_by_user_id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'created_by_user_id');
    }

    public function projectIncomes()
    {
        return $this->hasManyThrough(
            Income::class,
            Project::class,
            'created_by_user_id',
            'project_id'         
        );
    }

    public function investments(): HasMany {
        return $this->hasMany(Investment::class, 'user_id');
    }

    public function isVerified(): bool{
        if(isset($this->email_verified_at)){
            return true;
        }
        else{
            return false;
        }
    }

    public function isAdmin(): bool{
        return $this->is_admin;
    }

    public function getEditableAttribute()
    {
        return $this->deleted_at == null && $this->is_admin == false;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at === null && $this->is_admin == false;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at !== null && $this->is_admin == false;
    }

    public function getStreetAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getStreetNrAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getApartmentNrAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getPostcodeAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getCityAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getCountryAttribute($value)
    {
        return $value === null ? '' : $value;
    }
}
