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
}
