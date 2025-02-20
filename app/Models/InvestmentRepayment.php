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

    protected $appends = ['editable', 'deletable', 'restorable'];

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
