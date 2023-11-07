<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ref_id',
    ];
    
    protected $hidden = [
       
    ];

    protected $guarded = [
    'id',
    'created_at',
];
}
