<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramTeamRole extends Model
{
    //
    protected $table = 'program_team_role';

    protected $fillable = [ 'role',
                            'workload_note',
                            'plan_workload',
                            'actual_workload',
                            'employee_id'
                        ];

    public function Program(){
        return $this->belongsTo('App\Models\Program','program_id','id');
    }
    public function Employee(){
        return $this->belongsTo('App\Models\Employee','employee_id','id');
    }
    public function ProgramTeamRoleNote(){
        return $this->hasMany('App\Models\ProgramTeamRoleNote','programteamrole_id','id');
    }

}
