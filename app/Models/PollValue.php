<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PollValue extends Model
{
    //
    protected $table = 'poll_value';

    protected $fillable = [ 'poll_column_id',
                            'value',
                            'employee_id'
                            ];

    public function Employee(){
    	return $this->belongsTo('App\Models\Employee','employee_id','id');
    }

    public function PollColumn(){
    	return $this->belongsTo('App\Models\PollColumn','poll_column_id','id');
    }
}
