<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
  protected $table = 'subject_teacher';
  
  public function classSectionYear(){
    return $this->belongsTo('App\ClassSectionYear');
  }

  public function subject(){
    return $this->belongsTo(Subject::class);
  }

  public function teacher(){
    return $this->belongsTo('App\Teacher');
  }

}
