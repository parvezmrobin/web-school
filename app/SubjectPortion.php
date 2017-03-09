<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectPortion extends Model
{
    protected $table = 'subject_teacher_portion';

    public function subjectTeacher(){
        return $this->belongsTo("App\SubjectTeacher");
    }

    public function portion(){
        return $this->belongsTo("App\Portion");
    }

}
