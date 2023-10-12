<?php

namespace App\Models;

use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasFooter;
use App\Traits\HasPagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expenses extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $fillable = [
        'title',
        'date',
        'price',
        'shop_name',
        'file_name'
    ];

    protected $filterable = [
        'deleted' => 'boolean',
        'search' => 'string',
        'date' => ['date_start' => 'date', 'date_end' => 'date'],
        'number' => ['price_start' => 'numeric', 'price_end' => 'numeric'],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'title',
        'date',
        'price',
        'shop_name',
    ];

    protected $searchable = [
        'title',
        'date',
        'price',
        'shop_name',
    ];

    protected $footer = [
        'price' => 'sum'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
