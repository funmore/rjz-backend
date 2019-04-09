<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyNote extends Model
{
    protected $table = 'daily_note';

    protected $fillable = [ 'ptr_note_id',
                            'assist_name',
                            'plan_work',
                            'actual_work',
                            'output'
                        ];

    public function ProgramTeamRoleNote(){
        return $this->belongsTo('App\Models\ProgramTeamRoleNote','ptr_note_id','id');
    }
}
