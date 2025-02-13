<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentStatus extends Model
{
    use HasFactory;

    public function investments(): HasMany {
        return $this->hasMany(Investment::class, 'status_id');
    }
}
