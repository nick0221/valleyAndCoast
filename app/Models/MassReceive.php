<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MassReceive extends Model
{
    use HasFactory;

    protected $fillable = [
        'tranReference',
        'notes',
        'receivedBy',
        'user_id',
    ];


    public function receiveBy():BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function itemInventory(): HasMany
    {
        return $this->hasMany(ReceivedStock::class);
    }








    protected static function booted(): void
    {
        static::created(function ($massReceive) {
            $massReceive->tranReference = 'RCV-'.now('Asia/Manila')->timestamp;
            $massReceive->save();
        });


    }
}
