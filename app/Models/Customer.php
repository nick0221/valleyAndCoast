<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'email',
        'address',
        'contact',
        'companyName',
        'fullname',
    ];


    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }




    /**
     * @return void
     * Auto create a fullname upon saving
     */
    protected static function booted(): void
    {
        static::created(function ($staff) {
            $staff->fullname = Str::upper($staff->lastname .', '.$staff->firstname);
            $staff->save();
        });


    }








}
