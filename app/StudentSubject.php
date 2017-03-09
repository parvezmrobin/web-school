<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentSubject extends Model
{
    protected $table = 'subject_teacher_student';

    public function subjectTeacher(){
        return $this->belongsTo('App\SubjectTeacher');
    }

    public function student(){
        return $this->belongsTo('App\Student');
    }

}
