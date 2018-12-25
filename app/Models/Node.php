<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    //
    protected $table = 'node';

    public function Workflow(){
    	return $this->belongsTo('App\Models\Workflow','workflow_id','id');
    }

    public function WorkflowTemplate(){
    	return $this->belongsTo('App\Models\WorkflowTemplate','workflow_template_id','id');
    }
}
