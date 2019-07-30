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
use App\Models\FlightModel;


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
        if(array_key_exists('model_id',$listQuery)&&$listQuery['model_id']!=''){
            $programsModel = Program::where('model_id', $listQuery['model_id'])->get();
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
        if(array_key_exists('type',$listQuery)&&$listQuery['type']!=''){
            if($listQuery['type']=='creator'){
                $programsisMeCreated = Program::where('creator_id', $employee->id)->get();
                if($programs->isEmpty()){
                    $programs=$programs->merge($programsisMeCreated);
                }else{
                    $programs=$programs->intersect($programsisMeCreated);
                }
            }


            if($listQuery['type']=='member'){
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


            if($listQuery['type']=='leader'){
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
            if($listQuery['type']=='supervisor'){
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
                        if($programTeamRole->role!='监督员')
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
            if($listQuery['type']=='qa'){
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
                        if($programTeamRole->role!='质量保证员')
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
            if($listQuery['type']=='cm'){
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
                        if($programTeamRole->role!='配置管理员')
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
        }
        


        //将programs按照创建时间的降序排列
        $programs=$programs->filter(function($program){
          return $program->state=='首轮测试执行中'
               ||$program->state=='首轮测试结束'
               ||$program->state=='完成最终报告待评审'
               ||$program->state=='通过评审未归档'
               ||$program->state=='已归档';
        })->sortBy(function($program)
        {
            return $program->created_at;
        })->reverse();

         $programsToArray=$programs->map(function($program){
             $manager=$program->FlightModel==null?'':Employee::find($program->FlightModel->employee_id);
             $program_leader=null;
             $program_team_strict=null;
             $workflow_state=null;
             $issue=null;
             if(sizeof($program->Workflow)!=0) {
                 $node = $program->Workflow->Node->first(function ($key, $value) {
                     return $value->array_index == $value->Workflow->active;
                 });
                 $programIssue =$node->NodeNote->filter(function($value){
                     return   $value->is_up=='是';
                 })->map(function($item,$key){
                     return $item->note;
                 })->all();
                 $programIssue=implode('/',$programIssue);

                 $workflow_state=$node->name;
                 $issue=$programIssue;
             }
             if(sizeof($program->ProgramTeamRole)!=0) {
                 $programTeamLeader = $program->ProgramTeamRole->first(function ($key, $value) {
                     return $value->role == '项目组长';
                 });
                 $programTeamStrict = $program->ProgramTeamRole->filter(function ($value) {
                     return $value->role == '项目组长' || $value->role == '项目组员';
                 })->map(function ($item) {
                     return Employee::find($item->employee_id)->name;
                 })->all();
                 $programTeamStrictName = implode('/', $programTeamStrict);

                 $program_leader=Employee::find($programTeamLeader->employee_id)==null?null:Employee::find($programTeamLeader->employee_id)->name;
                 $program_team_strict=$programTeamStrictName;
             }
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
                 'model_id',
                 'program_type',
                 'classification',
                 'program_stage',
                 'dev_type',
                 'state',
                 'creator_id',
                 'manager_id'])
                 ->put('manager',$manager)
                 ->put('program_leader',$program_leader)
                 ->put('program_team_strict',$program_team_strict)
                 ->put('workflow_state',$workflow_state)
                 ->put('issue',$issue)
                 ->all();
             return $program;
         });

        $ret['items']=$programsToArray->toArray();
        $ret['total']=sizeof($programsToArray);
        return json_encode($ret);
    }












     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function custom(Request $request)
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
        if(array_key_exists('model_id',$listQuery)&&$listQuery['model_id']!=''){
            $programsModel = Program::where('model_id', $listQuery['model_id'])->get();
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
        if(array_key_exists('type',$listQuery)&&$listQuery['type']!=''){
            if($listQuery['type']=='creator'){
                $programsisMeCreated = Program::where('creator_id', $employee->id)->get();
                if($programs->isEmpty()){
                    $programs=$programs->merge($programsisMeCreated);
                }else{
                    $programs=$programs->intersect($programsisMeCreated);
                }
            }


            if($listQuery['type']=='member'){
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


            if($listQuery['type']=='leader'){
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
            if($listQuery['type']=='supervisor'){
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
                        if($programTeamRole->role!='监督员')
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
            if($listQuery['type']=='qa'){
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
                        if($programTeamRole->role!='质量保证员')
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
            if($listQuery['type']=='cm'){
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
                        if($programTeamRole->role!='配置管理员')
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
        }
        


        //将programs按照创建时间的降序排列
        $programs=$programs->filter(function($program){
          return $program->state=='首轮测试执行中'
               ||$program->state=='首轮测试结束'
               ||$program->state=='完成最终报告待评审'
               ||$program->state=='通过评审未归档'
               ||$program->state=='已归档';
        })->sortBy(function($program)
        {
            return $program->created_at;
        })->reverse();







         $programsToArray=$programs->map(function($program){

            $manager=$program->FlightModel==null?'':Employee::find($program->FlightModel->employee_id);
            $manager_name=$manager->name;
            $model_name=$program->FlightModel==null?'':$program->FlightModel->model_name;
            $programBasic=collect($program->toArray())->only([
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
                'model_id',
                'program_type',
                'classification',
                'program_stage',
                'dev_type',
                'state',
                'creator_id',
                'manager_id'])
                ->put('model_name',$model_name)
                ->put('manager_name',$manager_name)
                ->put('manager',$manager)
                ->all();

            $contact=array('plan'=>null,'quality'=>null,'code'=>null);
            if(sizeof($program->Contact)==0){
                }else{
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
                    $contact['plan']=Contact::where('program_id', $program->id)->where('type','计划')->first()->name;
                    $contact['quality']=Contact::where('program_id', $program->id)->where('type','质量')->first()->name;
                    $contact['code']=Contact::where('program_id', $program->id)->where('type','设计')->first()->name;
                }
                $softwareInfoCol=null;
                if(sizeof($program->SoftwareInfo)==0){

                }else{
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
                }

             $workflow=null;
             if(sizeof($program->Workflow)!=0) {
                 $node = $program->Workflow->Node->first(function ($key, $value) {
                     return $value->array_index == $value->Workflow->active;
                 });
                 $workflow_issue =$node->NodeNote->filter(function($value){
                     return   $value->is_up=='是';
                 })->map(function($item,$key){
                     return $item->note;
                 })->all();
                 $workflow_issue=implode('/',$workflow_issue);

                 $workflow_state=$node->name;
                 $workflow=array('workflow_state'=>$workflow_state,'workflow_issue'=>$workflow_issue);
             }
             $programTeamRole=null;
             if(sizeof($program->ProgramTeamRole)!=0) {
                 $programTeamLeader = $program->ProgramTeamRole->first(function ($key, $value) {
                     return $value->role == '项目组长';
                 });
                 $programTeamStrict = $program->ProgramTeamRole->filter(function ($value) {
                     return $value->role == '项目组长' || $value->role == '项目组员';
                 })->map(function ($item) {
                     return Employee::find($item->employee_id)->name;
                 })->all();
                 $programTeamStrictName = implode('/', $programTeamStrict);

                 $program_leader=Employee::find($programTeamLeader->employee_id)==null?null:Employee::find($programTeamLeader->employee_id)->name;
                 $program_team_strict=$programTeamStrictName;
                 $programTeamRole=array('program_leader'=>$program_leader,'program_team_strict'=>$program_team_strict);
             }
        
            $item=array('programBasic'=>$programBasic,
                        'contact'=>$contact,
                        'softwareInfoCol'=>$softwareInfoCol,
                        'workflow'=>$workflow,
                        'programTeamRole'=>$programTeamRole);
             return $item;
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

        $ret = array('success'=>0, 'note'=>null,'items'=>null,'program_role'=>null );

        $program = Program::find($id);


        if(sizeof($program->Contact)==0){
             $contacts=null;
        }else{
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
        }

        if(sizeof($program->SoftwareInfo)==0){
            $softwareInfoCol=null;
        }else{
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
        }
        

        if(sizeof($program->Workflow)==0){
             $workflow=null;
        }else{
            $workflow = array('id'=>null,'workflow_name'=>null, 'active'=>null,'workflowArray'=>null );
            $workflow['id']=$program->Workflow->id;
            $workflow['workflow_name']=$program->Workflow->workflow_name;
            $workflow['active']=$program->Workflow->active;

            $workflow['workflowArray']=$program->Workflow->Node->map(function($node){
                $undo_task_count=0;
                if(sizeof($node->ProgramTeamRoleTask)!=0){
                    $undo_task_collection=$node->ProgramTeamRoleTask->filter(function ($value) {
                                return $value->state != '100';
                            });
                    $undo_task_count=sizeof($undo_task_collection);
                }
                return collect($node->toArray())->only([
                    'id',
                    'plan_day',
                    'actual_day',
                    'array_index',
                    'name',
                    'type'])
                    ->put('undo_task_count',$undo_task_count)
                    ->all();
            })->sortBy('array_index');
        }

        
        
        if(sizeof($program->ProgramTeamRole)==0){
             $programTeamRoles=null;
        }else{
            $programTeamRoles=$program->ProgramTeamRole;
            $programTeamRoles=$programTeamRoles->map(function($programTeamRole){
                $employee_name=Employee::find($programTeamRole->employee_id)->name;
                return collect($programTeamRole->toArray())->only([
                    'id',
                    'role',
                    'workload_note',
                    'plan_workload',
                    'actual_workload',
                    'employee_id'])->put('employee_name',$employee_name)->all();
            });
        }
        $programRole=array();
        if(sizeof($program->ProgramTeamRole)!=0){
            $programTeamRolesForRoleAdd=$program->ProgramTeamRole;
            foreach($programTeamRolesForRoleAdd as $one){
                if($one->employee_id==$employee->id) {
                    array_push($programRole, $one->role);
                }
            }
        }
        if($program->FlightModel->Employee->id==$employee->id){
            array_push($programRole, '型号负责人');
        }
        if(sizeof($programRole)==0){
            array_push($programRole, '只读');
        }



        

        //修改pvstate start

        $pvstate=Pvstate::where('program_id',$program->id)->where("employee_id",$employee->id)->first();
        if($pvstate!=null){
            $pvstate->is_read=1;
            $pvstate->save();
        }
        //修改pvstate end
        


        


        $manager_name=Employee::find($program->manager_id)==null?null:Employee::find($program->manager_id)->name;
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
                'model_id',
                'program_type',
                'classification',
                'program_stage',
                'dev_type',
                'manager_id'])->put('manager_name',$manager_name)->all();

        $ret['program_role']=$programRole;
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
