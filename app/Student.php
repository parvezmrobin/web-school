<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function classSectionYears(){
        return $this->hasMany('App\ClassSectionYear', 'student_roll');
    }

    public function studentRolls(){
        return $this->hasMany('App\StudentRoll');
    }


}
