<?php

namespace App\Models;

use App\Traits\HasFilters;
use App\Traits\HasFooter;
use App\Traits\HasPagination;
use App\Traits\HasSorting;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Income extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $table_name = 'incomes';

    protected $fillable = [
        'title',
        'remarks',
        'price',
        'date',
        'costs',
        'distribution',
        'created_by_user_id',
        'project_id',
        'status_id',
    ];

    protected $appends = ['editable', 'deletable', 'restorable', 'settleable'];

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
        'remarks',
        'status' => ['name']
    ];

    protected $searchable = [
        'title',
        'date',
        'price',
        'remarks',
    ];

    protected $footer = [
        'price' => 'sum'
    ];

    protected $casts = [
        'distribution' => 'array'
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
        return $this->deleted_at == null && $this->status_id != 2 && $this->project_id == null;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at === null && $this->status_id != 2 && $this->project_id == null;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at !== null && $this->project_id == null;
    }

    public function getSettleableAttribute() {
        return $this->deleted_at == null && $this->status_id == 1;
    }
}
