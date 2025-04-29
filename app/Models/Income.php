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

    const STATUS_ACTIVE = 1;
    const STATUS_SETTLED = 2;

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

    protected $appends = [/* 'editable', 'deletable', 'restorable', */ 'settleable'];

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
            ['created_by_user' => 'boolean'],
            ['related_with_user_id' => 'string']
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'title',
        'date',
        'price',
        'remarks',
        'status' => ['name'],
        'project.costs'
    ];

    protected $searchable = [
        'title',
        'date',
        'price',
        'remarks',
        'status' => ['name'],
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
        return $this->belongsTo(Project::class, 'project_id')->withTrashed();
    }

    public function status(): BelongsTo{
        return $this->belongsTo(IncomeStatus::class, 'status_id');
    }

    public function getEditableAttribute()
    {
        return $this->deleted_at == null && $this->status_id != Income::STATUS_SETTLED && $this->project_id == null;
    }

    public function getDeletableAttribute()
    {
        return $this->deleted_at === null && $this->status_id != Income::STATUS_SETTLED && $this->project_id == null;
    }
    
    public function getRestorableAttribute()
    {
        return $this->deleted_at !== null && $this->project_id == null;
    }

    public function getSettleableAttribute() {
        return $this->deleted_at == null && $this->status_id == Income::STATUS_ACTIVE;
    }

    public function scopeUseModelFilters($query, $filters) {
        $query->when(
            $filters['related_with_user_id'] ?? false,
            function ($query, $value){
                $query->relatedIncome($value, $this->table_name);
            }
        );
    }

    public function calculateEarnings(int $user_id): float {
        $price = (float) $this->price;
        $costs = (float) $this->costs;
        $commission = (float) $this->commission;
        $distribution = is_array($this->distribution) ? $this->distribution : json_decode($this->distribution, true);

        $creator = 0;
        $participant = 0;

        $base = $price - ($price * ($costs / 100));

        if ($commission) {
            $creator = round($base * ($commission / 100), 2);
        }

        if (is_array($distribution) && array_key_exists($user_id, $distribution)) {
            $participant = round(($base - $creator) * (($income->distribution[$user_id] ?? 0) / 100), 2);
        }

        return round($creator + $participant, 2);
    }

    public function scopeRelatedIncome($query, int $user_id, ?string $table_name = null)
    {
        return $query->where(function ($query) use ($user_id, $table_name) {
            $query->whereHas('project', function ($query) use ($user_id) {
                $query->where('created_by_user_id', $user_id);
            })
            ->orWhere("{$table_name}.created_by_user_id", $user_id)
            ->orWhereRaw("JSON_EXTRACT(" . ($table_name ? "{$table_name}.distribution" : "distribution") . ", '$.{$user_id}') IS NOT NULL");
        });
    }

    public function scopeUserHasIncome($query, int $user_id, ?string $table_name = null)
    {
        return $query->where(function ($query) use ($user_id, $table_name) {
            $query->whereHas('project', function ($query) use ($user_id) {
                $query->where('created_by_user_id', $user_id);
            })
            ->orWhereRaw("JSON_EXTRACT(" . ($table_name ? "{$table_name}.distribution" : "distribution") . ", '$.{$user_id}') IS NOT NULL");
        });
    }
}
