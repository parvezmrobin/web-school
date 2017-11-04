<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacherStudent extends Model
{
    public $table = 'subject_teacher_student';

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
