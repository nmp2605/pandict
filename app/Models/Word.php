<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @param int $id
 * @param string $word
 * @param Carbon $created_at
 * @param Carbon $updated_at
 */
class Word extends Model
{
    use HasFactory;

    public $fillable = ['word'];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
