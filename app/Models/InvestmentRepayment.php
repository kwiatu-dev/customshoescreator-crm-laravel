<?php

namespace App\Models;

use App\Traits\HasFooter;
use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestmentRepayment extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $fillable = [
        'repayment',
        'date',
        'remarks',
        'investment_id',
        'created_by_user_id'
    ];

    //protected $appends = ['editable', 'deletable', 'restorable'];

    protected $filterable = [
        'search' => 'string',
        'numbers' => [
            ['repayment_start' => 'numeric', 'repayment_end' => 'numeric']
        ],
        'dates' => [
            ['date_start' => 'date', 'date_end' => 'date']
        ],
        'others' => [
            ['deleted' => 'boolean'],
            ['created_by_user' => 'boolean']
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'repayment',
        'date',
        'remarks'
    ];

    protected $searchable = [
        'repayment',
        'date',
        'remarks'
    ];

    protected $footer = [
        'repayment' => 'sum'
    ];

    public function getEditableAttribute()
    {
        return $this->deleted_at == null && $this->investment->editable;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at == null && $this->investment->editable;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at != null && $this->investment->editable;
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id')->withTrashed();
    }

    public function investment(): BelongsTo{
        return $this->belongsTo(Investment::class, 'investment_id')->withTrashed();
    }
}
