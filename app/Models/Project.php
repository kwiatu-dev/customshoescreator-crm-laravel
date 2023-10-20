<?php

namespace App\Models;

use App\Traits\HasFooter;
use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory,  HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $fillable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'commission',
        'costs',
        'distribution',
        'visualization', 
        'created_by_user_id',
        'client_id',
        'status_id',
        'type_id'
    ];

    protected $filterable = [
        'deleted' => 'boolean',
        'search' => 'string',
        'dates' => [
            ['start_start' => 'date', 'start_end' => 'date'],
            ['deadline_start' => 'date', 'deadline_end' => 'date'],
        ],
        'numbers' => [
            ['price_start' => 'numeric', 'price_end' => 'numeric'],
            ['visualization_start' => 'numeric', 'visualization_end' => 'numeric']
        ],
        'pagination' => 'string',
        'status_id' => 'string',
        'type_id' => 'string',
        'created_by_user' => 'boolean'
    ];

    protected $sortable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'visualization', 
    ];

    protected $searchable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'visualization', 
    ];

    protected $footer = [
        'price' => 'sum',
        'visualization' => 'sum',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function client(): BelongsTo{
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function type(): BelongsTo{
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function images(): HasMany{
        return $this->hasMany(ProjectImage::class, 'project_id');
    }
}
