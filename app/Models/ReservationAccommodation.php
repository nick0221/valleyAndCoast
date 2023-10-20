<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationAccommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'reservation_id',
        'accommodationPrice',
        'withBreakfast',
        'totalAmtDue',
        'bedtype',
    ];

    protected $casts = [

    ];




    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }





    public function bedType(): BelongsTo
    {
        return $this->belongsTo(BedType::class);
    }




}
