<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }


    public $fillable= [
        'id',
        'user_id',
        'comment',
        'post_id',
        'parent_comment_id',
        'ref_type',
    ];


    protected $guarded = [
        'created_at',
        'updated_at',
    ];
    

}
