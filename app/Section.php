<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public function classSectionYear(){
        return $this->hasMany('App\ClassSectionYear');
    }
}
