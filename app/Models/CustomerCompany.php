<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'businessType',
        'birNum',
        'address',
    ];








}
