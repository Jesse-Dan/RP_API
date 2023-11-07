<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function views()
    {
        return $this->hasMany(View::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }


    protected $fillable = [
        'user_id',
        'subject',
        'categories',
        'sub_categories',
        'sub_categories_child',
        'content',
        'file',
        
    ];
    
    protected $hidden = [
       
    ];

    protected $guarded = [
    'id',
    'created_at',
    'updated_at',
];





}
