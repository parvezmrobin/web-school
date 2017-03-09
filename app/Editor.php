<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Editor extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'id');
    }

    public function authorizedClassSectionYears(){
        return $this->belongsToMany('App\ClassSectionYear', 'transaction_auth');
    }

    public function authorizedSubjectTeachers(){
        return $this->belongsToMany('App\SubjectTeacher', 'mark_auth');
    }

}
