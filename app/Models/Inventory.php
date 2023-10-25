<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'itemname',
        'itemdesc',
        'category_id',
        'remainingStocks',
        'image',
    ];



    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class);
    }


    public function countReceived($record): int
    {
        return $inv = ReceivedStock::where('inventory_id', $record)->get()->sum('qty');
    }


    public function received_stocks():HasMany
    {
        return $this->hasMany(ReceivedStock::class);
    }



    public function receivedStock():HasMany
    {
        return $this->hasMany(ReceivedStock::class);
    }



}
