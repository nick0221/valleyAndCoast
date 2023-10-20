<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Staff extends Model
{
    use HasFactory;


    protected $fillable = [
        'firstname',
        'lastname',
        'fullname',
        'gender',
        'contact',
        'address',
        'dateHired',
        'dateResign',
        'designation_id',
    ];



    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }




    protected static function booted(): void
    {
        static::created(function ($staff) {
            $staff->fullname = Str::upper($staff->firstname .' '.$staff->lastname);
            $staff->save();
        });


    }

}
