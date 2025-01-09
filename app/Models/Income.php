<?php

namespace App\Models;

use App\Traits\HasFilters;
use App\Traits\HasFooter;
use App\Traits\HasPagination;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Income extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $fillable = [
        'title',
        'remarks',
        'price',
        'date',
        'created_by_user_id',
        'project_id',
        'status_id',
    ];

    protected $appends = ['editable', 'deletable', 'restorable'];

    protected $filterable = [
        'search' => 'string',
        'dates' => [
            ['date_start' => 'date', 'date_end' => 'date']
        ],
        'numbers' => [
            ['price_start' => 'numeric', 'price_end' => 'numeric']
        ],
        'dictionary' => [
            ['status_id' => 'string'],
            ['created_by_user_id' => 'string'],
        ],
        'others' => [
            ['deleted' => 'boolean'],
            ['created_by_user' => 'boolean']
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'title',
        'date',
        'price',
        'remarks'
    ];

    protected $searchable = [
        'title',
        'date',
        'price',
        'remarks'
    ];

    protected $footer = [
        'price' => 'sum'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function project(): BelongsTo{
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(IncomeStatus::class, 'status_id');
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
