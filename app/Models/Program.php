<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    //
    protected $table = 'program';

    public function SoftwareInfo(){
    	return $this->hasMany('App\Models\SoftwareInfo');
    }
    public function ProgramsTeamRoles(){
    	return $this->hasMany('App\Models\ProgramsTeamRole');
    }
    public function Workflow(){
        return $this->belongsTo('App\Models\Workflow','workflow_id','id');
    }
    public function Contract(){
        return $this->belongsTo('App\Models\Contract','contract_id','id');
    }
    public function Contact()
    {
        return $this->belongsToMany('App\Models\Contact');
    }

}
