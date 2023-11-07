<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'currency',
        'symbol',
        'digital',
        'name',
        'country',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }
}
