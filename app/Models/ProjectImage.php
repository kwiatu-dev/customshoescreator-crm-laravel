<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'type_id',
        'file',
        'project_id'
    ];

    public function project(): BelongsTo{
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function projectImageType(): BelongsTo{
        return $this->belongsTo(ProjectImageType::class, 'type_id');
    }
}
