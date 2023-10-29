<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'customerName',
        'check_in',
        'check_out',
        'contact',
        'email',
        'status',
    ];



    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }




}
