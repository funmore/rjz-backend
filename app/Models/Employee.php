<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employee';

    public function UserInfo(){
    	return $this->hasOne('App\Models\UserInfo','employee_id','id');
    }
    public function Token(){
    	return $this->hasOne('App\Models\Token','employee_id','id');
    }
    public function CreatedProgram(){
    	return $this->hasMany('App\Models\Program','creator_id','id');
    }
    public function Pvstate(){
        return $this->hasMany('App\Models\Pvstate','employee_id','id');
    }
    public function Pvlog(){
        return $this->hasMany('App\Models\Pvlog','changer_id','id');
    }
    public function WorkflowNote(){
        return $this->hasMany('App\Models\WorkflowNote');
    }
    public function ProgramNote(){
        return $this->hasMany('App\Models\ProgramNote','employee_id','id');
    }
}
