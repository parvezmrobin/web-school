<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    public function subjectPortion(){
        return $this->belongsTo("App\SubjectPortion");
    }

    public function studentSubject(){
        return $this->belongsTo('App\StudentSubject');
    }

    public function classSectionYearTerm(){
        return $this->belongsTo('App\ClassSectionYearTerm');
    }

}
