<?php

namespace App\Http\Controllers\API;

use App\Models\Program;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Pvlog;
use App\Models\Pvstate;
use App\Models\Token;
use App\Libraries\PV;



class ProgramController extends Controller
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
        $ret = array('success'=>0, 'note'=>null,'total'=>0,'items'=>null,'isOkay'=>true );
        $token = $request->header('AdminToken');
        $employee =Token::where('token',$token)->first()->Employee;

        $postData=$request->all();

        $program=Program::find($id);
        $pv = new PV();
        $pv_isset=false;
        if(array_key_exists('plan_start_time',$postData)&&$postData['plan_start_time']!=''){
                $program['plan_start_time'] = $postData['plan_start_time'];
        }
        if(array_key_exists('plan_end_time',$postData)&&$postData['plan_end_time']!=''){
                $program['plan_end_time'] = $postData['plan_end_time'];
        }
        if(array_key_exists('actual_start_time',$postData)&&$postData['actual_start_time']!=''){
                $program['actual_start_time'] = $postData['actual_start_time'];
        }
        if(array_key_exists('actual_end_time',$postData)&&$postData['actual_end_time']!=''){
                $program['actual_end_time'] = $postData['actual_end_time'];
        }
        if(array_key_exists('name',$postData)&&$postData['name']!=''){
                $program['name'] = $postData['name'];
        }
        if(array_key_exists('program_identity',$postData)&&$postData['program_identity']!=''){
                $program['program_identity'] = $postData['program_identity'];
        }
        if(array_key_exists('model_id',$postData)&&$postData['model_id']!=''){
                $program['model_id'] = $postData['model_id'];
        }
        if(array_key_exists('program_type',$postData)&&$postData['program_type']!=''){
                $program['program_type'] = $postData['program_type'];
        }
        if(array_key_exists('classification',$postData)&&$postData['classification']!=''){
                $program['classification'] = $postData['classification'];
        }
        if(array_key_exists('program_stage',$postData)&&$postData['program_stage']!=''){
                $program['program_stage'] = $postData['program_stage'];
        }
        if(array_key_exists('dev_type',$postData)&&$postData['dev_type']!=''){
                $program['dev_type'] = $postData['dev_type'];
        }
        if(array_key_exists('overdue_reason',$postData)&&$postData['overdue_reason']!=''){
                $program['overdue_reason'] = $postData['overdue_reason'];
        }
        if(array_key_exists('note',$postData)&&$postData['note']!=''){
                $program['note'] = $postData['note'];
        }
        if(array_key_exists('state',$postData)&&$postData['state']!=''){
                if($program['state']!="首轮测试执行中"&&$postData['state']=="首轮测试执行中"){
                    $ret['noticeArray']=$pv->storePvState($program,$employee);
                }else{
                    $pv->storePvlog($program,$employee,'项目信息变更');
                    $pv_isset==true;
                }
                $program['state'] = $postData['state'];
        }
        if(array_key_exists('type',$postData)&&$postData['type']!=''){
                $program['type'] = $postData['type'];
        }
        if(array_key_exists('ref',$postData)&&$postData['ref']!=''){
                $program['ref'] = $postData['ref'];
        }
        $program->save();

        if($pv_isset==false){
            $pv->storePvlog($program,$employee,'项目信息变更');
        }

        // $pvstates= Pvstate::where('program_id',$program->id)->where('employee_id','!=',$employee->id)->get();
        // if(sizeof($pvstates)!=0) {
        //     foreach ($pvstates as $pvstate) {
        //         $pvstate->is_read = 0;
        //         $pvstate->save();
        //     }
        // }

        // $pvlog = new Pvlog(array( 'changer_id'      => $employee->id,
        //                            'change_note'=> '项目信息变更'
        //                         ));
        // $program->Pvlog()->save($pvlog);


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
