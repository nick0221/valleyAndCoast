<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalCharges extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'chargeFor',
        'chargePrice',
    ];


    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }





}
