<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'first_name', 'last_name', 'fathers_name', 'mothers_name', 'address', 'sex_id', 'email', 'password',
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password', 'remember_token',
  ];

  public function getRolesAttribute()
  {
    $roles = [];

    if (Student::find($this->id)) {
      array_push($roles, 'Student');
    }

    if (Teacher::find($this->id)) {
      array_push($roles, 'Teacher');
    }

    if (Editor::find($this->id)) {
      array_push($roles, 'Editor');
    }

    if (Admin::find($this->id)) {
      array_push($roles, 'Admin');
    }

    return $roles;
  }

  public function isInRole($value)
  {
    if(is_array($value)){
      foreach ($value as $key => $role) {
        if ($this->forRole($role)) {
          return true;
        }
      }
      return false;
    }
    return $this->forRole($value);
  }

  private function forRole($value)
  {
    $value = strtolower($value);

    if(!strcmp($value, 'student') && Student::find($this->id))
    return true;
    else if(!strcmp($value, 'teacher') && Teacher::find($this->id))
    return true;
    else if(!strcmp('editor', $value) && Editor::find($this->id))
    return true;
    else if(!strcmp($value, 'admin') && Admin::find($this->id))
    return true;
    
    return false;

  }
}
