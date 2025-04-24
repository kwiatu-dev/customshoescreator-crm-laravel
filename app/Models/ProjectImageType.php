<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectImageType extends Model
{
    use HasFactory;

    const TYPE_VISUALIZATION = 1;
    const TYPE_PROCESS = 2;
    const TYPE_FINAL = 3;
    const TYPE_INSPIRATION = 4;

    public function projectImages(): HasMany{
        return $this->hasMany(Project::class, 'type_id');
    }
}
