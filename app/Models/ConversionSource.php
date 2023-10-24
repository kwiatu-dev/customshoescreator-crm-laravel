<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConversionSource extends Model
{
    use HasFactory;

    public function clients(): HasMany{
        return $this->hasMany(Client::class, 'conversion_source_id');
    }
}
