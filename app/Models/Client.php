<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name', 
        'last_name', 
        'email',
        'email_verified_at',
        'phone',
        'street',
        'street_nr',
        'apartment_nr',
        'postcode',
        'city',
        'country',
        'username',
        'conversion_source',
        'social_link'
    ];

    protected $search = [
        'first_name', 
        'last_name', 
        'email',
        'phone',
        'postcode',
        'city',
        'country',
        'username',
        'conversion_source',
    ];

    protected static $sortable = [
        'first_name', 
        'last_name', 
        'email',
        'phone',
        'street',
        'postcode',
        'city',
        'country',
        'username',
        'conversion_source',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function projects(): HasMany{
        return $this->hasMany(Project::class, 'client_id');
    }

    protected function conversionSource(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? ucfirst($value) : $value,
        );
    }

    public function scopeFilter(Builder $query, array $filters): Builder{
        return $query->when(
            $filters['deleted'] ?? false,
            fn ($query, $value) => $query->withTrashed()
        )->when(
            $filters['search'] ?? false,
            fn ($query, $value) => $query->where(
                function ($query) use ($value) {
                    foreach ($this->search as $column) {
                        $query->orWhere($column, 'like', "%$value%");
                    }

                    $query->orWhereRaw(
                        "CONCAT(street, ' ', street_nr, '/', apartment_nr) LIKE ?",
                        ["%$value%"]
                    );
                }
            )
        );
    }

    public function scopeSort(Builder $query, array $sort): Builder{
        return $query->when(
            $sort ?? false,
            function ($query, $value) {
                foreach ($value as $column => $direction){
                    if(in_array($column, self::$sortable) && in_array($direction, ['asc', 'desc'])){
                        $query->orderBy($column, $direction);
                    }
                }
            }
        );
    }

    public static function getSortFields(Request $request): array{
        $sort = [];

        foreach($request->all() as $key => $value){
            if(in_array($key, self::$sortable)){
                $sort[$key] = $value;
            }
        }

        return $sort;
    }

    public static function getFilterFields(Request $request): array{
        $filters = [];

        if($request->boolean('deleted')){
            $filters = array_merge($filters, $request->only('deleted'));
        }

        if($request->string('search') != ""){
            $filters = array_merge($filters, $request->only('search'));
        }

        return $filters;
    }
}
