<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSectionYear extends Model
{
    protected $table = 'class_section_year';

    public function classs(){
        return $this->belongsTo('App\Classs');
    }

    public function section(){
        return $this->belongsTo('App\Section');
    }

    public function year(){
        return $this->belongsTo('App\Year');
    }

    public function subjectTeachers(){
        return $this->hasMany(SubjectTeacher::class);
    }

    public function editors(){
        return $this->belongsToMany('App\Editor', 'transaction_auth');
    }

    public function aggregateImposes(){
        return $this->hasMany('App\AggregateImpose');
    }

    public function classSectionYearTerms()
    {
        return $this->hasMany(ClassSectionYearTerm::class);
    }

    public function studentRolls()
    {
        return $this->hasMany(StudentRoll::class);
    }
}
