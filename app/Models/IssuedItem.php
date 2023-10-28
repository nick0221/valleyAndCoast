<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuedItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'inventory_id',
        'issued_transaction_id',
        'qty',
        'tranType',
    ];




    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }









}
