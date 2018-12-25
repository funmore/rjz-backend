<?php

namespace App\Http\Controllers\Api;

use App\Libraries\JSSDK;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\UserInfo;
use App\Models\Token;
use App\Models\Employee;


// 50008:非法的token; 50012:其他客户端登录了;  50014:Token 过期了;
//token: 0  success
//       1  false
class UserController extends Controller
{
    public function logout(){
        $token=Input::get('token');
        $ret = array('success'=>4, 'note'=>'loged out' );
        return json_encode($ret);
    }
    public function login()
    {
        $name = Input::get('name');
        $pw = Input::get('password');
        $userinfo = UserInfo::where('username',$name)->first();

        $ret = array('success'=>0, 'token'=>null, 'note'=>null );
        if($userinfo==null){
            $ret['success'] = 1;
            $ret['note']    = '用户名不存在';
            return json_encode($ret);
        }

        if($userinfo->password==$pw){

            $token = new Token;
            $token->token = md5(uniqid().$pw.$name);
            
            $employee=$userinfo->Employee;
            $token->employee_id=$employee->id;
            if($employee==null)
                return;

            $token->save();
            $ret['token'] = $token->token;
        }else{
            $ret['success'] = 2;
            $ret['note']    = '密码错误';
        }
        return json_encode($ret);


        // $code = Input::get('code');
        // //$ret = array('success'=>true, 'token'=>$code, 'role'=>'noauth');
        // //$ret = array('success'=>false, 'msg'=>'请求参数错误', 'role'=>'noauth');
        // $jssdk = new JSSDK(config('yueche.AppID'), config('yueche.AppSecret'));
        // $data = $jssdk->wxLogin($code);

        // if (isset($data['openid'])){
        //     $token = new Token;
        //     $token->token = md5(uniqid().$data['openid'].$data['session_key']);
        //     $token->openid = $data['openid'];
        //     $token->session_key = $data['session_key'];

        //     $ret = array('success'=>true, 'token'=>$token->token, 'role'=>'noauth','name'=>'null','mobilephone'=>'null','depart_id'=>0);
        //     $employee = Employee::where('openid', $data['openid'])->first();
        //     if ($employee) {
        //         $ret['name'] = $employee->name;
        //         $ret['mobilephone'] = $employee->mobilephone;
        //         $ret['depart_id'] = $employee->depart_id;
        //         if ($employee->admin) {
        //             $ret['role'] = 'admin';
        //             $token->role = 'admin';
        //         }
        //         else {
        //             $ret['role'] = 'employee';
        //             $token->role = 'employee';
        //         }
        //         if($employee->privileges){
        //             $ret['privileges']=1;
        //         }else{
        //             $ret['privileges']=0;
        //         }
        //         if($employee->second_privileges){
        //             $ret['second_privileges']=1;
        //         }else{
        //             $ret['second_privileges']=0;
        //         }
        //         $ret['id']=$employee->id;
        //     }
        //     else {
        //         $company = Company::where('openid', $data['openid'])->first();
        //         if ($company) {
        //             $ret['name'] = $company->name;
        //             $ret['role'] = 'company';
        //             $token->role = 'company';
        //         }
        //         else {
        //             $ret['name'] = 'client';
        //             $ret['role'] = 'noprivilege';
        //             $token->role = 'noprivilege';
        //         }
        //     }
        //     $token->save();

        // }
        // else {
        //     $ret['success'] = false;
        // }
        // return json_encode($ret);
    }

    public function getInfo(){
        $ret = array('success'=>0, 'note'=>null,'roles'=>null,'name'=>'' );

        $token = Input::get('token');
        $employee  =Token::where('token', $token)->first()->Employee;
        if($employee!=null){
                $ret['name']=$employee->name;
                $ret['roles']=array($employee->is_director,$employee->is_v_director,      
                                       $employee->is_chiefdesigner,   
                                       $employee->is_v_chiefdesigner,
                                       $employee->is_p_leader,        
                                       $employee->is_p_principal,     
                                       $employee->is_qa,              
                                       $employee->is_cm,              
                                       $employee->is_bd,             
                                       $employee->is_tester,          
                                       $employee->is_admin);
                            }
        return json_encode($ret);




    }


    public function grant() {
        $token = Input::get('token');
        $code = Input::get('code');
        $nick = Input::get('nick');
        $wx = Token::where('token', $token)->first();
        $employee = Employee::where('openid', $code)->first();
        $msg = array(
            //"touser" => $leader->openid,
            "template_id" => config('yueche.BindMsg'),
            //"page" => "index",
            "form_id" => Input::get('formId'),
            "data" => array(
                "keyword3" => array(
                    "value" => $nick,
                    "color" => "#173177",
                ),
                "keyword4" => array(
                    "value" => '已绑定',
                    "color" => "#173177",
                )
            ),
            "emphasis_keyword" => "keyword4.DATA"
        );
        if ($employee) {
            $employee->openid = $wx->openid;
            $employee->save();
            $msg['data']['keyword1'] = array(
                "value" => '欢迎使用遥感约车',
                "color" => "#173177",
            );
            $msg['data']['keyword2'] = array(
                "value" => $employee->name,
                "color" => "#173177",
            );
            if ($employee->admin) {
                $ret['role'] = 'admin';
            }
            else {
                $ret['role'] = 'employee';
            }

            if($employee->privileges){
                $ret['privileges']=1;
            }else{
                $ret['privileges']=0;
            }
            if($employee->second_privileges){
                $ret['second_privileges']=1;
            }else{
                $ret['second_privileges']=0;
            }

        }
        else {
            $company = Company::where('openid', $code)->first();

            if ($company) {
                $company->openid = $wx->openid;
                $company->save();
                $msg['data']['keyword1'] = array(
                    "value" => $company->name,
                    "color" => "#173177",
                );
                $msg['data']['keyword2'] = array(
                    "value" => $company->name,
                    "color" => "#173177",
                );
                $ret['role'] = 'company';
            }
            else {
                $ret['role'] = 'noprivilege';
            }
        }
        $admins = Employee::where('admin', true)->get();
        $jssdk = new JSSDK(config('yueche.AppID'), config('yueche.AppSecret'));
        //foreach ($admins as $admin) {
            $msg['touser'] =$employee->openid;
            $jssdk->sendWxMsg($msg);



        return json_encode($ret);
    }
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
