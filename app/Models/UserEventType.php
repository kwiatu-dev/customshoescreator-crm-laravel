<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserEventType extends Model
{
    use HasFactory;

    public function events(): HasMany{
        return $this->hasMany(UserEvents::class, 'type_id');
    }
}
