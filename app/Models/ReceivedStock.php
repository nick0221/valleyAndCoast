<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivedStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'mass_receive_id',
        'remarks',
        'tranType',
        'qty',
    ];


    public function itemInfo(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }


    public function receivedBy($record): void
    {
          MassReceive::where('id', $record)->first();

    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }





}
