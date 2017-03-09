<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    public function classSectionYear(){
        return $this->belongsTo('App\ClassSectionYear');
    }

    public function subject(){
        return $this->belongsTo('App\Subject');
    }

    public function teacher(){
        return $this->belongsTo('App\Teacher');
    }

}
