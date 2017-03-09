<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSectionYearTerm extends Model
{
    protected $table = 'class_section_year_term';

    public function classSectionYear(){
        return $this->belongsTo('App\ClassSectionYear');
    }

    public function term(){
        return $this->belongsTo('App\Term');
    }

}
