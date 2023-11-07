<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'ref_id',
        'react_id',
        'ref_type',
        'parent_comment_id',
        'post_id'
    ];
    
    protected $hidden = [
        
       
    ];

    protected $guarded = [
    
    'created_at',
    'updated_at',
];


}
