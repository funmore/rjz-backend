<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    //
    protected $table = 'poll';

    protected $fillable = [ 'name',
                            'due_day',
                            'employee_id',
                            'range'
                            ];

    public function PollColumn(){
    	return $this->hasMany('App\Models\PollColumn','poll_id','id');
    }
    public function PollFill(){
        return $this->hasMany('App\Models\PollFill','poll_id','id');
    }

    public function Employee(){
    	return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

}
