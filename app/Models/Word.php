<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Word extends Model
{
    use HasFactory;

    public $fillable = ['value'];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
