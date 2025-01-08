<?php

namespace App\Models;

use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
    use HasFactory, HasFilters, HasSorting, HasPagination, SoftDeletes;

    protected $fillable = [
        'first_name', 
        'last_name', 
        'email',
        'email_verified_at',
        'phone',
        'street',
        'street_nr',
        'apartment_nr',
        'postcode',
        'city',
        'country',
        'username',
        'conversion_source_id',
        'social_link'
    ];

    protected $appends = ['editable', 'deletable', 'restorable'];

    protected $filterable = [
        'search' => 'string',
        'dictionary' => [
            ['conversion_source_id' => 'string']
        ],
        'others' => [
            ['deleted' => 'boolean'],
            ['created_by_user' => 'boolean']
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'first_name', 
        'last_name', 
        'email',
        'phone',
        'street',
        'street_nr',
        'apartment_nr',
        'postcode',
        'city',
        'country',
        'username',
    ];

    protected $searchable = [
        'first_name', 
        'last_name', 
        'email',
        'phone',
        'postcode',
        'city',
        'country',
        'username',
        'conversion_source_id',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function projects(): HasMany{
        return $this->hasMany(Project::class, 'client_id');
    }

    public function conversion_source(): BelongsTo{
        return $this->belongsTo(ConversionSource::class, 'conversion_source_id');
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

    public function getUsernameAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getEditableAttribute()
    {
        return $this->deleted_at == null && $this->created_by_user_id === Auth::user()->id;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at === null && $this->created_by_user_id === Auth::user()->id;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at !== null && $this->created_by_user_id === Auth::user()->id;
    }
}
