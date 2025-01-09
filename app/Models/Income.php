<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function project(): BelongsTo{
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(IncomeStatus::class, 'status_id');
    }
}
