<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Classs extends Model
{
    protected $table = "classes";

    public function classSectionYears(){
        return $this->hasMany('App\ClassSectionYear');
    }

    public function getSectionsAttribute(){
        return DB::table('class_section_year')
            ->join('sections', 'sections.id', 'class_section_year.class_id')
            ->join('years', 'years.id', 'class_section_year.year_id')
            ->where('class_id', $this->id)
            ->where('year', \Carbon\Carbon::now()->year)
            ->select('sections.*')
            ->distinct()
            ->get();
    }

}
