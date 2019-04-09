<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelayApply extends Model
{
    protected $table = 'delay_apply';

    protected $fillable = [ 'ptr_note_id',
                            'delay_day',
                            'delay_reason',
                            'is_approved'
                        ];

    public function ProgramTeamRoleNote(){
        return $this->belongsTo('App\Models\ProgramTeamRoleNote','ptr_note_id','id');
    }

}
