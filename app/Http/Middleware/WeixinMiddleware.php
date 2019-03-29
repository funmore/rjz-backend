<?php

namespace App\Http\Middleware;

use Closure;
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
        


        $key = config('rjz.AppSecret');
        $t = $request->header('Time');
        $s = $request->header('Sha');
        $curtime = time();
        


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
