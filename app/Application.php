<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $casts = [
        'info' => 'array',
        'deadline' => 'dateTime'
    ];

    public function responses(){
        return $this->hasMany('App\Response');
    }

}
