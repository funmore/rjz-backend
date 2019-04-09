<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\SoftwareInfo;
use App\Models\Pvlog;
use App\Models\Pvstate;
use App\Models\Token;

class SoftwareInfoController extends Controller
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


        $softwareInfo=SoftwareInfo::find($id);
        
        $postData=$request->all();

        $softwareInfo->name  = $postData['name'];
        $softwareInfo->version_id= $postData['version_id'];
        $softwareInfo->complier  = $postData['complier'];
        $softwareInfo->runtime  = $postData['runtime'];
        $softwareInfo->size     = $postData['size'];
        $softwareInfo->reduced_code_size  = $postData['reduced_code_size'];
        $softwareInfo->reduced_reason  = $postData['reduced_reason'];
        $softwareInfo->software_cate = $postData['software_cate'];
        $softwareInfo->software_sub_cate  = $postData['software_sub_cate'];
        $softwareInfo->cpu_type  = $postData['cpu_type'];
        $softwareInfo->code_langu  = $postData['code_langu'];
        $softwareInfo->software_usage  = $postData['software_usage'];
        $softwareInfo->software_type  = $postData['software_type'];        
        $softwareInfo->save();

        $program=$softwareInfo->Program;
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
                                   'change_note'=> '被测件信息变更'
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
