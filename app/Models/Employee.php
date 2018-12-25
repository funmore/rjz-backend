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
}
