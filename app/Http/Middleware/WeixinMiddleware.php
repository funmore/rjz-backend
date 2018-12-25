<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Token;
use Illuminate\Support\Facades\Input;
use App\Models\Order;
use App\Models\OtherInfoOfOrder;
use App\Models\OrderNum;

class WeixinMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $orders=Order::all();
//
//        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
//        foreach ($orders as $order) {
//            $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
//            $ordernum = new OrderNum(array('order_numb' => $orderSn));
//            $order->ordernum()->save($ordernum);
//        }
        


        //original code start
        // $key = config('app.key');
        // $token = Input::get('token');
        // $t = Input::get('t');
        // $s = Input::get('s');
        // $curtime = time();


        // //echo Token::where('token', $token)->where('updated_at', '>', $curtime-7200)->count();
        // if (!empty($token) && !empty($t) && !empty($s) && $curtime-$t < 300 && sha1($key.$t) == $s
        //     && Token::where('token', $token)->where('updated_at', '>', $curtime-7200)->count() > 0) {
        //     return $next($request);
        // }
        // else {
        //     return $next($request);
        //     echo "{'success':false}";
        // }
        //original code end

        $key = config('app.key');
        $token = Input::get('token');
        $t = Input::get('t');
        $s = Input::get('s');
        $curtime = time();
                //echo Token::where('token', $token)->where('updated_at', '>', $curtime-7200)->count();
        if (!empty($token) && !empty($t) && !empty($s) && $curtime-$t < 300 && sha1($key.$t) == $s) {
            echo "{'success':true}";
            return $next($request);

        }
        else {
            return $next($request);
            echo "{'success':false}";
        }
    }
}
