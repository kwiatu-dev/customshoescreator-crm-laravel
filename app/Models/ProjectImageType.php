<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectImageType extends Model
{
    use HasFactory;

    public function projectImages(): HasMany{
        return $this->hasMany(Project::class, 'type_id');
    }
}
