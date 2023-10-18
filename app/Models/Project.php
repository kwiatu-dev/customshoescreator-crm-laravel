<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

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
}
