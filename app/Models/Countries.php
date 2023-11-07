<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;

public $fillable = [
    'code',
    'name',
    'phone_code',
    'flag'
];

}
