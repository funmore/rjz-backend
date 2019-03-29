<?php

namespace App\Http\Controllers\API;

use App\Models\Program;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Pvlog;
use App\Models\Pvstate;
use App\Models\Token;


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
        $ret = array('success'=>0, 'note'=>null,'total'=>0,'items'=>null );

        $postData=$request->all();

        $program=Program::find($id);
        $program['plan_start_time'] = $postData['plan_start_time'];
        $program['plan_end_time']   = $postData['plan_end_time'];
        $program['actual_start_time'] = $postData['actual_start_time'];
        $program['actual_end_time']   = $postData['actual_end_time'];
        $program['name']            = $postData['name'];
        $program['program_identity']= $postData['program_identity'];
        $program['model']           = $postData['model'];
        $program['program_type']    = $postData['program_type'];
        $program['classification']  = $postData['classification'];
        $program['program_stage']   = $postData['program_stage'];
        $program['dev_type']        = $postData['dev_type'];
        $program['overdue_reason']  = $postData['overdue_reason'];
        $program->save();


        $token = $request->header('AdminToken');
        $employee =Token::where('token',$token)->first()->Employee;

        $pvstates= Pvstate::where('program_id',$program->id)->where('employee_id','!=',$employee->id)->get();
        if(sizeof($pvstates)!=0) {
            foreach ($pvstates as $pvstate) {
                $pvstate->is_read = 0;
                $pvstate->save();
            }
        }

        $pvlog = new Pvlog(array( 'changer_id'      => $employee->id,
                                   'change_note'=> '项目信息变更'
                                ));
        $program->Pvlog()->save($pvlog);


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
