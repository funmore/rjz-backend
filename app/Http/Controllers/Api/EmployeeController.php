<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Token;
use Faker\Provider\Company;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


use App\Libraries\JSSDK;
use App\Models\Accept_log;
use App\Models\DestinationOfOrders;
use App\Models\Approve_log;
use App\Models\PerformanceUse;

use Illuminate\Database\Eloquent\Collection;








class EmployeeController extends Controller
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
     * 查看型号管理员
     *
     * @return \Illuminate\Http\Response
     */
    public function getManager()
    {
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $employee = Employee::where('openid', $token->openid)->first();

        $managers = Employee::where('second_privileges',1)->get();

        $managersToRet=array();
        $index=0;
        foreach ($managers as $manager) {
            $managerSingle=array('index'=>$index , 'id'=>$manager->id,'name'=>$manager->name);
            $managersToRet[$index]=$managerSingle;
            $index+=1;
        }
        return json_encode($managersToRet);
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
