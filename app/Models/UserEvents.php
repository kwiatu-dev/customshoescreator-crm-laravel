<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEvents extends Model
{
    use HasFactory, HasFilters, SoftDeletes;

    protected $table_name = 'user_events';

    protected $fillable = [
        'title',
        'start',
        'end',
        'remarks',
        'created_by_user_id',
        'user_id',
        'type_id',
    ];

    protected $appends = ['editable', 'deletable', 'restorable'];


    protected $filterable = [
        'search' => 'string',
        'dates' => [
            ['start_start' => 'date', 'start_end' => 'date'],
            ['end_start' => 'date', 'end_end' => 'date'],
        ],
        'dictionary' => [
            ['type_id' => 'string'],
            ['user_id' => 'string'],
        ],
        'others' => [
            ['deleted' => 'boolean'],
        ],
    ];

    protected $sortable = [];

    protected $searchable = [
        'title',
        'remarks',
    ];

    protected $footer = [];


    public function getEditableAttribute()
    {
        return $this->deleted_at == null;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at === null;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at !== null;
    }

    public function creator(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo{
        return $this->belongsTo(UserEventType::class, 'type_id');
    }
}
