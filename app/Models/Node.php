<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    //
    protected $table = 'node';

    protected $fillable = [ 'workflow_id',
                            'workflow_template_id',
                            'type',
                            'array_index',
                            'name'
                            ];

    public function Workflow(){
    	return $this->belongsTo('App\Models\Workflow','workflow_id','id');
    }

    public function WorkflowTemplate(){
    	return $this->belongsTo('App\Models\WorkflowTemplate','workflow_template_id','id');
    }

    public function ProgramNote(){
        return $this->hasMany('App\Models\ProgramNote','node_id','id');
    }
}
