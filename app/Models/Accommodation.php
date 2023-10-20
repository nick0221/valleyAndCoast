<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'roomNumber',
        'bedCount',
        'bed_type_id',
        'maxOccupancy',
        'pricePerNight',
        'availability',
        'isSmokingAllowed',
        'hasBalcony',
        'roomSize',
        'amenities',
        'isAirconditioned',
        'description',
        'user_id',
        'last_edited_by_id',
        'image'
    ];

    protected $casts = [
      'amenities' => 'json',

    ];


    public function bedType(): BelongsTo
    {
        return $this->belongsTo(BedType::class);
    }






}
