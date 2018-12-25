<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Libraries\JSSDK;
use App\Models\Company;
use App\Models\DestinationOfOrders;
use App\Models\Approve_log;
use App\Models\Accept_log;
use App\Models\Order;
use App\Models\Car;
use App\Models\Driver;
use App\Models\Use_log;

use Illuminate\Support\Facades\Input;
use App\Models\Token;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use App\Models\OrderNum;

class CompanyController extends Controller
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
     * Display all cars  related to the company
     *
     * @return \Illuminate\Http\Response
     */
    public function carShow()
    {
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $company=Company::where('openid',$token->openid)->first();

        $cars=Car::where('company_id',$company->id)->get();
        $carsToArray = $cars->toArray();


        return json_encode($carsToArray);
    }
    /**
     * Display all cars and driver related to the company
     *
     * @return \Illuminate\Http\Response
     */
    public function driverShow()
    {
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $company=Company::where('openid',$token->openid)->first();

        $drivers=Driver::where('company_id',$company->id)->get();

        $driversToArray = $drivers->toArray();
        return json_encode($driversToArray);
    }

    /**
     * company accept the order request
     *
     * @param  null
     * @return \Illuminate\Http\Response
     */
    public function companyAccept()
    {
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $company=Company::where('openid',$token->openid)->first();

        $order=Order::find(Input::get('id'));
        $order->state=40;
        $order->save();


        //$accepetLog=new Accept_log(['order_id'=>Input::get('id'),'u_id'=>$company->id,'d_id'=>Input::get('driverId'),'c_id'=>Input::get('carId')]);
        $acceptLog=Accept_log::where('order_id',$order->id)->first();
        if(Input::get('driverId')!=0) {
            $acceptLog->d_id = Input::get('driverId');
        }else{
            $driver=new Driver(['name'=>Input::get('new_driver_name'),'mobilephone'=>Input::get('new_driver_mobilephone'),'company_id'=>$company->id]);
            $driver->save();
            $acceptLog->d_id=$driver->id;
        }

        if(Input::get('carId')!=0) {
            $acceptLog->c_id = Input::get('carId');
        }else{
            $car=new Car(['name'=>Input::get('new_car_name'),'license'=>Input::get('new_car_license'),'company_id'=>$company->id]);
            $car->save();
            $acceptLog->c_id=$car->id;
        }
        $acceptLog->save();

        $origin=$order->getOriginAttribute();
        $dest=$order->getDestinationAttribute();
        $originStr=implode('/',$origin);
        $destStr=implode('/',$dest);
        $jssdk = new JSSDK(config('yueche.AppID'), config('yueche.AppSecret'));
        $msg = array(
            "touser" =>$company->openid,
            "template_id" => config('yueche.OrderMsg'),
            'page'=>"pages/company/company",
            "form_id" => Input::get('formId'),
            "data" => array(
                "keyword1" => array(
                    "value" =>'已匹配车型',
                    "color" => "#173177",
                ),
                "keyword2" => array(
                    "value" => $order->reason,
                    "color" => "#173177",
                ),
                "keyword3" => array(

                    "value" => $order->usetime,
                    "color" => "#173177",
                ),
                "keyword4" => array(
                    "value" =>$originStr,
                    "color" => "#173177",
                ),
                "keyword5" => array(
                    "value" =>$destStr,
                    "color" => "#173177",
                )
            ),
            "emphasis_keyword" => "keyword1.DATA"
        );

        $jssdk->sendWxMsg($msg);

        return 0;
    }

    /**
     * company accept the order request
     *
     * @param  null
     * @return \Illuminate\Http\Response
     */
    public function companyAccept39()
    {
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $company=Company::where('openid',$token->openid)->first();


        $order=Order::find(Input::get('itemId'));
        //true说明订单被别的公司抢到  false说明没有被抢到
        if($order->state>=39||$order->state==0){  //state == 39
            return 0;
        }else {                //state <= 39
            $order->state = 39;
            $order->save();
            $acceptLog=new Accept_log(['order_id'=>Input::get('itemId'),'u_id'=>$company->id,'d_id'=> 0,'c_id'=>0]);
            $acceptLog->save();

            $origin=$order->getOriginAttribute();
            $dest=$order->getDestinationAttribute();
            $originStr=implode('/',$origin);
            $destStr=implode('/',$dest);
            $jssdk = new JSSDK(config('yueche.AppID'), config('yueche.AppSecret'));
            $msg = array(
                "touser" =>$company->openid,
                "template_id" => config('yueche.OrderMsg'),
                'page'=>"pages/company/company",
                "form_id" => Input::get('formId'),
                "data" => array(
                    "keyword1" => array(
                        "value" =>'已接单',
                        "color" => "#173177",
                    ),
                    "keyword2" => array(
                        "value" => $order->reason,
                        "color" => "#173177",
                    ),
                    "keyword3" => array(

                        "value" => $order->usetime,
                        "color" => "#173177",
                    ),
                    "keyword4" => array(
                        "value" =>$originStr,
                        "color" => "#173177",
                    ),
                    "keyword5" => array(
                        "value" =>$destStr,
                        "color" => "#173177",
                    )
                ),
                "emphasis_keyword" => "keyword1.DATA"
            );

            $jssdk->sendWxMsg($msg);

            return 1;
        }
    }

    /**
     * company settle order
     *Settle
     * @param  null
     * @return \Illuminate\Http\Response
     */
    public function orderSettle()
    {
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $company=Company::where('openid',$token->openid)->first();


        $order=Order::find(Input::get('order_id'));


        $start_time= \DateTime::createFromFormat('Y-m-d H:i:s',Input::get('start_time'));
        $end_time= \DateTime::createFromFormat('Y-m-d H:i:s',Input::get('end_time'));
        $worker=$order->workers;
        $mileage=(int)Input::get('mileage');
        $gq_fee=(float)Input::get('gq_fee');
        $pause_fee=(float)Input::get('pause_fee');
        $gs_fee=(float)Input::get('gs_fee');
        $account=(float)Input::get('account');
        $remark=Input::get('remark');

        $useLog=$order->uselog;
        if($useLog==null){
            $useLog=new Use_log();
        }

        $useLog->order_id= $order->id;
        $useLog->start_time=$start_time;
        $useLog->end_time=$end_time;
        $useLog->worker=$worker;
        $useLog->mileage=$mileage;
        $useLog->gq_fee=$gq_fee;
        $useLog->pause_fee=$pause_fee;
        $useLog->gs_fee=$gs_fee;
        $useLog->account=$account;
        $useLog->remark=$remark;

        $useLog->save();

        $order->state=44;


        $order->save();
        if($useLog->order_id){
            $origin=$order->getOriginAttribute();
            $dest=$order->getDestinationAttribute();
            $originStr=implode('/',$origin);
            $destStr=implode('/',$dest);
            $jssdk = new JSSDK(config('yueche.AppID'), config('yueche.AppSecret'));
            $msg = array(
                "touser" =>$company->openid,
                "template_id" => config('yueche.OrderMsg'),
                'page'=>"pages/company/company",
                "form_id" => Input::get('formId'),
                "data" => array(
                    "keyword1" => array(
                        "value" =>'已结算',
                        "color" => "#173177",
                    ),
                    "keyword2" => array(
                        "value" => $order->reason,
                        "color" => "#173177",
                    ),
                    "keyword3" => array(

                        "value" => $order->usetime,
                        "color" => "#173177",
                    ),
                    "keyword4" => array(
                        "value" =>$originStr,
                        "color" => "#173177",
                    ),
                    "keyword5" => array(
                        "value" =>$destStr,
                        "color" => "#173177",
                    )
                ),
                "emphasis_keyword" => "keyword1.DATA"
            );

            $jssdk->sendWxMsg($msg);
            return 0;
        }else{

            return 1;
        }

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


    public function getCompany(){
        $token = Input::get('token');
        $token = Token::where('token', $token)->orderBy('created_at')->first();
        $employee = Employee::where('openid', $token->openid)->first();

        $companys = Company::all();

        $compnaysToRet=array();
        $index=0;
        foreach ($companys as $company) {
            $companySingle=array('index'=>$index , 'id'=>$company->id,'name'=>$company->name);
            $companysToRet[$index]=$companySingle;
            $index+=1;
        }
        return json_encode($companysToRet);
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
