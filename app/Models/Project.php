<?php

namespace App\Models;

use App\Traits\HasFooter;
use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    protected $fillable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'commission',
        'costs',
        'distribution',
        'visualization', 
        'created_by_user_id',
        'client_id',
        'status_id',
        'type_id'
    ];

    protected $filterable = [
        'search' => 'string',
        'dates' => [
            ['start_start' => 'date', 'start_end' => 'date'],
            ['deadline_start' => 'date', 'deadline_end' => 'date'],
        ],
        'numbers' => [
            ['price_start' => 'numeric', 'price_end' => 'numeric'],
            ['visualization_start' => 'numeric', 'visualization_end' => 'numeric']
        ],
        'dictionary' => [
            ['type_id' => 'string'],
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
        'remarks',
        'price',
        'start',
        'deadline',
        'visualization', 
    ];

    protected $searchable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'visualization', 
    ];

    protected $footer = [
        'price' => 'sum',
        'visualization' => 'sum',
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function client(): BelongsTo{
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function type(): BelongsTo{
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function images(): HasMany{
        return $this->hasMany(ProjectImage::class, 'project_id');
    }

    public function addImages($tmp_files, $type_id)
    {
        foreach($tmp_files as $file){
            $disk = Storage::disk('private');

            $disk->move(
                $file['path'] .'/'. $file['name'], 
                'projects/'. $this->id .'/'. $file['name']
            );

            $disk->deleteDirectory($file['path']);

            ProjectImage::create([
                'type_id' => $type_id,
                'project_id' => $this->id,
                'file' => $file['name']
            ]);
        }
    }
}
