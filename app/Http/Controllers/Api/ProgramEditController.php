<?php

namespace App\Http\Controllers\Api;

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
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

class ProgramEditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $ret = array('success'=>0, 'note'=>null,'total'=>0,'items'=>null );
        $listQuery=$request->all();
        $token = $request->header('AdminToken');
        $employee =Token::where('token',$token)->first()->Employee;

        $programs=Collection::make();


        if(array_key_exists('title',$listQuery)&&$listQuery['title']!=''){
            if(strpos($listQuery['title'], '*') !== false) {
                $programsTitle = Program::where('name', 'LIKE', str_replace('*', '%', $listQuery['title']))->get();
            }else{
                $programsTitle = Program::where('name', 'LIKE', '%'.$listQuery['title'].'%')->get();
            }
            if($programs->isEmpty()){
                $programs=$programs->merge($programsTitle);
            }else{
                $programs=$programs->intersect($programsTitle);
            }
        }
        if(array_key_exists('model',$listQuery)&&$listQuery['model']!=''){
            $programsModel = Program::where('model', $listQuery['model'])->get();
            if($programs->isEmpty()){
                $programs=$programs->merge($programsModel);
            }else{
                $programs=$programs->intersect($programsModel);
            }
        }

        if(array_key_exists('classification',$listQuery)&&$listQuery['classification']!=''){
            $programsClassification = Program::where('classification', $listQuery['classification'])->get();
            if($programs->isEmpty()){
                $programs=$programs->merge($programsClassification);
            }else{
                $programs=$programs->intersect($programsClassification);
            }
        }

        if(array_key_exists('program_type',$listQuery)&&$listQuery['program_type']!=''){
            $programsProgramType = Program::where('program_type', $listQuery['program_type'])->get();
            if($programs->isEmpty()){
                $programs=$programs->merge($programsProgramType);
            }else{
                $programs=$programs->intersect($programsProgramType);
            }
        }

        if(array_key_exists('manager',$listQuery)&&$listQuery['manager']!=''){
            $programsManager = Program::where('manager_id', (int)$listQuery['manager'])->get();
            if($programs->isEmpty()){
                $programs=$programs->merge($programsManager);
            }else{
                $programs=$programs->intersect($programsManager);
            }
        }

        if(filter_var($listQuery['isMeCreated'], FILTER_VALIDATE_BOOLEAN)==true){
            $programsisMeCreated = Program::where('creator_id', $employee->id)->get();
            if($programs->isEmpty()){
                $programs=$programs->merge($programsisMeCreated);
            }else{
                $programs=$programs->intersect($programsisMeCreated);
            }
        }


        if(filter_var($listQuery['isMeMember'], FILTER_VALIDATE_BOOLEAN)==true){
            $programsisMeMember=Collection::make();
           $programTeamRoles=ProgramTeamRole::where('employee_id',$employee->id)->get();
           $noDuplicates = array();
           foreach ($programTeamRoles as $v) {
               if (isset($noDuplicates[$v['program_id']])) {
                   continue;
               }
               $noDuplicates[$v['program_id']] = $v;
           }
           $ProgramTeamRoleNoDuplicates = array_values($noDuplicates);

           if(sizeof($ProgramTeamRoleNoDuplicates)!=0){
               foreach ($ProgramTeamRoleNoDuplicates as $programTeamRole) {
                   $programToAdd = Program::find($programTeamRole->program_id);
                   $programsisMeMember = $programsisMeMember->push($programToAdd);   //push用来添加单个item  merge用来合并两个集合
               }
           }

            if($programs->isEmpty()){
                $programs=$programs->merge($programsisMeMember);
            }else{
                $programs=$programs->intersect($programsisMeMember);
            }
        }


        if(filter_var($listQuery['isMeLeader'], FILTER_VALIDATE_BOOLEAN)==true){
            $programsisMeMember=Collection::make();
            $programTeamRoles=ProgramTeamRole::where('employee_id',$employee->id)->get();
            $noDuplicates = array();
            foreach ($programTeamRoles as $v) {
                if (isset($noDuplicates[$v['program_id']])) {
                    continue;
                }
                $noDuplicates[$v['program_id']] = $v;
            }
            $ProgramTeamRoleNoDuplicates = array_values($noDuplicates);

            if(sizeof($ProgramTeamRoleNoDuplicates)!=0){
                foreach ($ProgramTeamRoleNoDuplicates as $programTeamRole) {
                    if($programTeamRole->role!='项目组长')
                        continue;
                    $programToAdd = Program::find($programTeamRole->program_id);
                    $programsisMeMember = $programsisMeMember->push($programToAdd);   //push用来添加单个item  merge用来合并两个集合
                }
            }

            if($programs->isEmpty()){
                $programs=$programs->merge($programsisMeMember);
            }else{
                $programs=$programs->intersect($programsisMeMember);
            }
        }


        //将programs按照创建时间的降序排列
        $programs=$programs->filter(function($program){
          return $program->state=='项目进行中';
        })->sortBy(function($program)
        {
            return $program->created_at;
        })->reverse();

         $programsToArray=$programs->map(function($program){
             $node=$program->Workflow->Node->first(function ($key, $value) {
                                return $value->array_index==$value->Workflow->active;
                            });
             $programTeamLeader=$program->ProgramTeamRole->first(function($key,$value){
                                return $value->role=='项目组长';
                            });
             $programTeamStrict=$program->ProgramTeamRole->filter(function($value){
                 return $value->role=='项目组长'||$value->role=='项目组员';
             })->map(function ($item) {
                 return Employee::find($item->employee_id)->name;
             })->all();
             $programTeamStrictName=implode('/',$programTeamStrict);

             $programIssue =$node->ProgramNote->filter(function($value){
                                     return   $value->is_up=='是';
                                 })->map(function($item,$key){
                                    return $item->note;
                                 })->all();
             $programIssue=implode('/',$programIssue);              
             $program=collect($program->toArray())->only([
                 'id',
                 'overdue_reason',
                 'plan_start_time',
                 'plan_end_time',
                 'actual_start_time',
                 'actual_end_time',
                 'contract_id',
                 'workflow_id',
                 'name',
                 'program_identity',
                 'model',
                 'program_type',
                 'classification',
                 'program_stage',
                 'dev_type',
                 'state',
                 'creator_id',
                 'manager_id'])
                 ->put('manager_name',Employee::find($program['manager_id'])->name)
                 ->put('program_leader',Employee::find($programTeamLeader->employee_id)->name)
                 ->put('program_team_strict',$programTeamStrictName)
                 ->put('workflow_state',$node->name)
                 ->put('issue',$programIssue)
                 ->all();
             return $program;
         });

        $ret['items']=$programsToArray->toArray();
        $ret['total']=sizeof($programsToArray);
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
        $ret = array('success'=>0, 'note'=>null,'total'=>0,'id'=>0 );
        $token = $request->header('AdminToken');
        $employee =Token::where('token',$token)->first()->Employee;

        $postData=$request->all();
        $programBasic=$postData['programBasic'];

        $program['plan_start_time'] = $programBasic['plan_start_time'];
        $program['plan_end_time']   = $programBasic['plan_end_time'];
        $program['name']            = $programBasic['name'];
        $program['type']            = $programBasic['type'];
        $program['ref']            = $programBasic['ref'];
        $program['program_source']  = $programBasic['program_source'];
        $program['state']  = '项目进行中';
        $program['program_identity']= $programBasic['program_identity'];
        $program['model']           = $programBasic['model'];
        $program['program_type']    = $programBasic['program_type'];
        $program['classification']  = $programBasic['classification'];
        $program['program_stage']   = $programBasic['program_stage'];
        $program['dev_type']        = $programBasic['dev_type'];
        $program['creator_id']      = $employee->id;
        $program['manager_id']      = $programBasic['manager_id'];

        $program=Program::create($program);
        $program->save();

        $contacts=$postData['contact'];

        foreach($contacts as $member){
            $memberRole = new Contact(array(        'is_12s'      => $member['is_12s'],
                                                    'type'=> $member['type'],
                                                    'organ'  => $member['organ'],
                                                    'name'  => $member['name'],
                                                    'tele'  => $member['tele']
                                                ));
            $program->Contact()->save($memberRole);
        }
        if(array_key_exists('softwareInfo',$postData)){
            $softInfo=$postData['softwareInfo'];
            foreach($softInfo as $member){
                $softwareInfo = new SoftwareInfo(array( 'name'      => $member['name'],
                                                        'version_id'=> $member['version_id'],
                                                        'complier'  => $member['complier'],
                                                        'runtime'  => $member['runtime'],
                                                        'size'     => $member['size'],
                                                        'reduced_code_size'  => $member['reduced_code_size'],
                                                        'reduced_reason'  => $member['reduced_reason'],
                                                        'software_cate'  => $member['software_cate'],
                                                        'software_sub_cate'  => $member['software_sub_cate'],
                                                        'cpu_type'  => $member['cpu_type'],
                                                        'code_langu'  => $member['code_langu'],
                                                        'software_usage'  => $member['software_usage'],
                                                        'software_type'  => $member['software_type'],
                                                        'info_typer_id'   =>$employee->id  
                                                        ));
                $program->SoftwareInfo()->save($softwareInfo);
            }
        }else{
            $program['state']='项目预备中';
        }


        if(array_key_exists('workflow',$postData)){
            $workflowInfo=$postData['workflow'];
            $workflow = Workflow::create([          'workflow_name'  => $workflowInfo['workflow_name'],
                                                    'active'=>      0,
                                                    'workflow_template_id'  =>      1
                                                    ]);
            $program->Workflow()->associate($workflow);
            $program->save();


            $workflowArray=$workflowInfo['workflowArray'];

            foreach($workflowArray as $key=>$workflowNode){
                $node = new Node(array(     'workflow_template_id'      => 1,
                                            'type'=> $workflowNode['type'],
                                            'plan_day'=> $workflowNode['plan_day'],                                        
                                            'name'  => $workflowNode['name'],
                                            'array_index'=>  $key
                                            ));
                $program->Workflow->Node()->save($node);
            }
        }

        if(array_key_exists('workflow',$postData)) {
            $programTeamRoles = $postData['programTeamRole'];
            foreach ($programTeamRoles as $member) {
                $memberRole = new ProgramTeamRole(array('role' => $member['role'],
                    'workload_note' => $member['workload_note'],
                    'plan_workload' => $member['plan_workload'],
                    'actual_workload' => $member['actual_workload'],
                    'employee_id' => $member['employee_id']
                ));
                $program->ProgramTeamRole()->save($memberRole);
            }


            $noDuplicates = array();
            foreach ($programTeamRoles as $v) {
                if (isset($noDuplicates[$v['employee_id']])) {
                    continue;
                }
                $noDuplicates[$v['employee_id']] = $v;
            }
            $ProgramTeamRoleNoDuplicates = array_values($noDuplicates);
            foreach ($ProgramTeamRoleNoDuplicates as $member) {
                $pvstate = new Pvstate(array(
                    'employee_id' => $member['employee_id'],
                    'is_read' => '0'
                ));
                if ($member['employee_id'] == $employee->id) {
                    $pvstate->is_read = '1';
                }
                $program->Pvstate()->save($pvstate);
            }


            $pvlog = new Pvlog(array('changer_id' => $employee->id,
                'change_note' => '创建了新项目',
            ));
            $program->Pvlog()->save($pvlog);
        }





        $ret['id']=$program->id;
        return json_encode($ret);
        //return json_encode($request->header('Cookie'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $token = $request->header('AdminToken');
        $employee =Token::where('token',$token)->first()->Employee;

        $ret = array('success'=>0, 'note'=>null,'items'=>null,'is_leader'=>false );

        $program = Program::find($id);
        $isleader=false;
        $ptrAll=$program->ProgramTeamRole;
        foreach($ptrAll as $one){
            if($one->role=='项目组长'&&$one->employee_id==$employee->id) {
                $isleader = true;
                break;
            }
        }
        $ret['is_leader']=$isleader;
        //修改pvstate start

        $pvstate=Pvstate::where('program_id',$program->id)->where("employee_id",$employee->id)->first();
        if($pvstate!=null){
            $pvstate->is_read=1;
            $pvstate->save();
        }
        //修改pvstate end


        $softwareInfoCol=$program->SoftwareInfo;
        $softwareInfoCol=$softwareInfoCol->map(function($softwareInfo){
            return collect($softwareInfo->toArray())->only([
                'id',
                'name',
                'version_id',
                'complier',
                'runtime',
                'size',
                'reduced_code_size',
                'reduced_reason',
                'software_cate',
                'software_sub_cate',
                'cpu_type',
                'code_langu',
                'software_usage',
                'software_type'])->all();
        });


        $workflow = array('id'=>null,'workflow_name'=>null, 'active'=>null,'workflowArray'=>null );
        $workflow['id']=$program->Workflow->id;
        $workflow['workflow_name']=$program->Workflow->workflow_name;
        $workflow['active']=$program->Workflow->active;

        $workflow['workflowArray']=$program->Workflow->Node->map(function($node){
            return collect($node->toArray())->only([
                'id',
                'plan_day',
                'actual_day',
                'array_index',
                'name',
                'type'])->all();
        })->sortBy('array_index');


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

        $contacts=$program->Contact;
        $contacts=$contacts->map(function($member){
            return collect($member->toArray())->only([
                'id',
                'is_12s',
                'type',
                'organ',
                'name',
                'tele'])->all();
        });



        $programToArray=collect($program->toArray())->only([
                'id',
                'ref',
                'type',
                'program_source',
                'state',
                'overdue_reason',
                'plan_start_time',
                'plan_end_time',
                'actual_start_time',
                'actual_end_time',
                'contract_id',
                'workflow_id',
                'name',
                'program_identity',
                'model',
                'program_type',
                'classification',
                'program_stage',
                'dev_type',
                'manager_id'])->put('manager_name',Employee::find($program->manager_id)->name)->all();


        $item['programBasic']=$programToArray;
        $item['softwareInfo']=$softwareInfoCol;
        $item['workflow'] =$workflow;
        $item['programTeamRole']=$programTeamRoles;
        $item['contact']=$contacts;
        $ret['items']=$item;



        return json_encode($ret);
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
        return json_encode($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return json_encode($id);
    }
}
