<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];








    protected static function booted(): void
    {
        static::created(function ($staff) {
            $staff->fullname = Str::upper($staff->firstname .' '.$staff->lastname);
            $staff->save();
        });


    }

}
