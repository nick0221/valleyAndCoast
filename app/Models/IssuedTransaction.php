<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IssuedTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tranReference',
        'notes',
        'tranStatus',
        'issuedBy',
        'user_id',
    ];



    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
    public function careOfBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'issuedBy');
    }



    public function issuedItems(): HasMany
    {
        return $this->hasMany(IssuedItem::class);
    }





    protected static function booted(): void
    {
        static::created(function ($issuedTran) {
            $issuedTran->tranReference = 'ITR-'.now('Asia/Manila')->timestamp;
            $issuedTran->save();
        });


    }


}
