<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentRoll extends Model
{
    protected $table = 'student_roll';

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function classSectionYear(){
        return $this->belongsTo('App\ClassSectionYear');
    }

    public function studentSubjects(){
        return $this->hasMany('App\StudentSubject');
    }

    public function aggregatePayments(){
        return $this->hasMany('App\AgrregatePayment');
    }

    public function individualTransactions(){
        return $this->hasMany('App\IndividualTransaction');
    }

    public function subjectTeacherStudents()
    {
        return $this->hasMany(SubjectTeacherStudent::class);
    }
}
