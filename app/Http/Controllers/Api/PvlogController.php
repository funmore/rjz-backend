<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Input;


use App\Models\UserInfo;
use App\Models\Token;
use App\Models\Employee;
use App\Models\ProgramTeamRole;
use App\Models\Program;
use App\Models\SoftwareInfo;
use App\Models\Workflow;
use App\Models\Node;
use App\Models\Pvlog;
use App\Models\Pvstate;
use Illuminate\Database\Eloquent\Collection;

class PvlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $ret = array('success'=>0, 'note'=>null,'noticeArray'=>null);

        $token = $request->header('AdminToken');
        $employee  =Token::where('token', $token)->first()->Employee;
        $programTeamRoles=ProgramTeamRole::where('employee_id',$employee->id)->get();
        $noDuplicates = array();
        foreach ($programTeamRoles as $v) {
            if (isset($noDuplicates[$v['program_id']])) {
                continue;
            }
            $noDuplicates[$v['program_id']] = $v;
        }
        $ProgramTeamRoleNoDuplicates = array_values($noDuplicates);
        $noticeArray=array();
        foreach($ProgramTeamRoleNoDuplicates as $member){
            $program = $member->Program;
            $pvstate =Pvstate::where('program_id',$program->id)->where('employee_id',$employee->id)->first();
            $is_read= $pvstate->is_read;
            if($is_read==1) continue;
            $pvlogs  = Pvlog::where('program_id',$program->id)
                            ->where('changer_id','!=',$employee->id)
                            ->where('updated_at','>=',$pvstate->updated_at)->get();
            if(sizeof($pvlogs)==0) continue;

            foreach ($pvlogs as $pvlog) {
                $singlenotice['id']=$program->id;
                $singlenotice['name']=$program->name;
                $singlenotice['changer']=Employee::find($pvlog->changer_id)->name;
                $singlenotice['change_note']=$pvlog->change_note;
                array_push($noticeArray, $singlenotice);
            }
        }
        $ret['noticeArray']=$noticeArray;
        return json_encode($ret);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
