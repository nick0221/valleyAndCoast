<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'email',
        'subject',
        'msg',
    ];


    public  function currentlyInquired($query)
    {
        return $query->whereDate('created', now()->toDate())->count();

    }




}
