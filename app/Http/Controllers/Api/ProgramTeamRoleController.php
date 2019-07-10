<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\ProgramTeamRole;


use App\Models\Employee;
use App\Models\UserInfo;
use App\Models\Token;
use App\Models\Workflow;
use App\Models\Program;

use App\Models\Node;
use App\Models\Pvlog;
use App\Models\Pvstate;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;
use App\Libraries\PV;


class ProgramTeamRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $ret = array('success'=>0, 'note'=>null,'total'=>0,'items'=>null);
        $postData=$request->all();
        $program=Program::find($postData['programId']);
        $programTeamRoles=$postData['data'];
            foreach ($programTeamRoles as $member) {
                $memberRole = new ProgramTeamRole(array('role' => $member['role'],
                    'workload_note' => $member['workload_note'],
                    'plan_workload' => $member['plan_workload'],
                    'actual_workload' => $member['actual_workload'],
                    'employee_id' => $member['employee_id']
                ));
                $program->ProgramTeamRole()->save($memberRole);
            }
        $token = $request->header('AdminToken');
        $employee =Token::where('token',$token)->first()->Employee;

        $pv = new PV();
        $pv->storePvlog($program,$employee,'创建工作流程');
        
        // $pvstates= Pvstate::where('program_id',$program->id)->where('employee_id','!=',$employee->id)->get();
        // if(sizeof($pvstates)!=0) {
        //     foreach ($pvstates as $pvstate) {
        //         $pvstate->is_read = 0;
        //         $pvstate->save();
        //     }
        // }
        // $pvlog = new Pvlog(array( 'changer_id'      => $employee->id,
        //                           'change_note'=> '创建工作流程'
        // ));
        // $program->Pvlog()->save($pvlog);

        $programTeamRoles=$program->ProgramTeamRole;
        $programTeamRoles=$programTeamRoles->map(function($programTeamRole){
            return collect($programTeamRole->toArray())->only([
                'id',
                'role',
                'workload_note',
                'plan_workload',
                'actual_workload',
                'employee_id'])->put('employee_name',Employee::find($programTeamRole->employee_id)->name)->all();
        });


        $ret['items']=$programTeamRoles;
        return json_encode($ret);

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
        $ret = array('success'=>0, 'note'=>null,'total'=>0,'items'=>null );


        $programTeamRole=ProgramTeamRole::find($id);
        
        $postData=$request->all();

        $programTeamRole->plan_workload  = $postData['plan_workload'];
        $programTeamRole->workload_note= $postData['workload_note'];
        $programTeamRole->actual_workload  = $postData['actual_workload'];      
        $programTeamRole->save();





        return json_encode($ret);
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
