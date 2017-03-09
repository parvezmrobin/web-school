<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AggregateImpose extends Model
{

    public function classSectionYear(){
        return $this->belongsTo('App\ClassSectionYear');
    }

    public function transactionType(){
        return $this->belongsTo('App\TransactionType');
    }

    public function imposer(){
        return $this->belongsTo('App\User', 'imposer_id');
    }

}
