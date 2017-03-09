<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AggregatePayment extends Model
{
    public function aggregateImposes(){
        return $this->belongsTo('App\AggregateImpose');
    }

    public function student(){
        return $this->hasManyThrough('App\Student', 'App\StudentRoll');
    }

}
