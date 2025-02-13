<?php

namespace App\Models;

use App\Traits\HasFooter;
use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $fillable = [
        'title',
        'amount',
        'date',
        'interest_rate',
        'total_repayment',
        'remarks',
        'user_id',
        'status_id',
        'created_by_user_id'
    ];

    protected $appends = ['editable', 'deletable', 'restorable'];

    public function getEditableAttribute()
    {
        return $this->deleted_at == null && $this->status_id == 1;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at === null && $this->status_id == 1;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at !== null && $this->status_id == 1;
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function investor(): BelongsTo{
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(InvestmentStatus::class, 'status_id');
    }

    public function repayments(): HasMany {
        return $this->hasMany(InvestmentRepayment::class, 'investment_id');
    }
}
