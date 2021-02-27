<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * @param int $id
 * @param int $word_id
 * @param string $details
 * @param string $entries
 * @param string $source
 * @param Carbon $created_at
 * @param Carbon $updated_at
 */
class Result extends Model
{
    use HasFactory;

    public $fillable = ['details', 'entries', 'source'];

    protected $casts = [
        'details' => 'collection',
        'entries' => 'collection',
    ];

    public function setDetailsAttribute(Collection $details): void
    {
        $this->attributes['details'] = $details->map(function (array $detail) {
            return [
                'name' => mb_strtolower($detail['name']),
                'value' => $detail['value'],
            ];
        })->toJson();
    }

    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }
}
