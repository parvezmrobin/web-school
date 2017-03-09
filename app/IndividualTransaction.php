<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndividualTransaction extends Model
{
    public function studentRoll(){
        return $this->belongsTo('App\StudentRoll');
    }

    public function transactionType(){
        return $this->belongsTo('App\TransactionType');
    }

}
