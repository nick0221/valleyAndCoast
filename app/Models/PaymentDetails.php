<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'payMethod',
        'totalCharge',
        'cardNumber',
        'cardType',
        'cardHolder',
        'notes',
    ];


    /**
     * @return BelongsTo
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }


    /**
     * @return HasMany
     */
    public function additionalCharges(): HasMany
    {
        return $this->hasMany(AdditionalCharges::class);
    }


}
