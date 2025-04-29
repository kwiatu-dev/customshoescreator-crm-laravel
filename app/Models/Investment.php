<?php

namespace App\Models;

use App\Notifications\Investment\InvestmentStatusNotification;
use App\Services\NotificationService;
use App\Traits\HasFooter;
use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Investment extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_COMPLETED = 2;

    protected $table_name = 'investments';

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

    protected $appends = [/* 'editable', 'deletable', 'restorable', */ 'total'];

    protected $filterable = [
        'search' => 'string',
        'numbers' => [
            ['amount_start' => 'numeric', 'amount_end' => 'numeric']
        ],
        'dates' => [
            ['date_start' => 'date', 'date_end' => 'date']
        ],
        'dictionary' => [
            ['status_id' => 'string'],
            ['user_id' => 'string'],
        ],
        'others' => [
            ['deleted' => 'boolean'],
            ['created_by_user' => 'boolean'],
            ['after_date' => 'boolean'],
            ['related_with_user_id' => 'string']
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'title',
        'amount',
        'date',
        'total_repayment',
        'remarks',
        'investor' => ['first_name', 'last_name'],
        'status' => ['name'],
    ];

    protected $searchable = [
        'investor' => ['first_name', 'last_name'],
        'status' => ['name'],
        'title',
        'remarks',
    ];

    protected $footer = [
        'total_repayment' => 'sum'
    ];

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

    public function total() {
        return round(($this->amount * $this->interest_rate / 100) + $this->amount, 2);
    }

    public function getTotalAttribute() 
    {
        return $this->total();
    }

    private function setFooterDynamicFields() {
        $this->footer += [
            'amount' => function($investment) {
                return round($investment->amount + $investment->amount * ($investment->interest_rate / 100), 2);
            }
        ];
    }

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

    public function scopeUseModelFilters($query, $filters) {
        $query->when(
            $filters['after_date'] ?? false,
            function ($query, $value){
                $query->afterDateInvestment($this->table_nam);
            }
        );

        $query->when(
            $filters['related_with_user_id'] ?? false,
            function ($query, $value){
                $query->relatedInvestment($value, $this->table_name);
            }
        );
    }

    public function scopeRelatedInvestment($query, int $user_id, ?string $table_name = null)
    {
        return $query->where(function ($query) use ($user_id, $table_name) {
            $query
                ->where("{$table_name}.user_id", $user_id)
                ->orWhere("{$table_name}.created_by_user_id", $user_id);
        });
    }

    public function scopeAfterDateInvestment($query, ?string $table_name = null) 
    {
        return $query->where('status_id', Investment::STATUS_COMPLETED)->where("{$table_name}.date", '<', now());
    }

    public function addRepaymentValue($repayment_value) {
        if (!$this->editable) {
            throw new Exception('Nie można edytować tej inwestycji!');
        }

        $left = round((float) $repayment_value, 2);
        $right = round((float) $this->total - (float) $this->total_repayment, 2);

        if ($left > $right) {
            throw ValidationException::withMessages([
                'repayment' => "Kwota spłaty przekroczyła wartość inwestycji.", 
            ]);
        }

        $status_id = Investment::STATUS_ACTIVE;

        if ($this->total == $this->total_repayment + $repayment_value) {
            $status_id = Investment::STATUS_COMPLETED;
        }

        $this->update([
            'total_repayment' => $this->total_repayment + $repayment_value,
            'status_id' => $status_id
        ]);
    }
}
