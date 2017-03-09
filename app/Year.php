<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Year extends Model
{
    public function classSectionYear(){
        return $this->hasMany('App\ClassSectionYear');
    }

    public function getClassesAttribute(){
        return DB::table('class_section_year')
            ->join('classes', 'classes.id', 'class_section_year.class_id')
            ->where('year_id', $this->id)
            ->select('classes.*')
            ->distinct()
            ->get();
    }

    public function getSectionsAttribute(){
        return DB::table('class_section_year')
            ->join('sections', 'sections.id', 'class_section_year.class_id')
            ->where('year_id', $this->id)
            ->select('sections.*')
            ->distinct()
            ->get();
    }

}
