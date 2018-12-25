<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Models\Accept_log;
use App\Models\Company;
use App\Models\DestinationOfOrders;
use App\Models\Approve_log;
use App\Models\OtherInfoOfOrder;
use App\Models\PerformanceUse;
use App\Models\Order;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $a=1;
        $schedule->call(function(){

//            $oneWeeksFromNow = Carbon::now()->subWeeks(1); // or ->format(..)
//            $orders40=Order::where('state','40')
//                ->where("usetime","<=",$oneWeeksFromNow)
//                ->get();
//
//            foreach($orders40 as $order){
//                $performanceUse=new PerformanceUse(['orderID'=>$order->id,'reviewText'=>'默认好评','score_of_order'=>5.0]);
//                $performanceUse->save();
//                $order->state=41;
//                $order->save();
//            }

            $oneHoursFromNow =Carbon::now()->subHour(1);
            $orders22_34=Order::where('state','>=','22')->where('state','<=','35')
               //->where('updated_at','>=',Carbon\Carbon::now()->toDateString() . ' 00:00:00')
                ->get();

            foreach($orders22_34 as $order){
                    if($order->updated_at->lt($oneHoursFromNow)) {
                        $order->state = 35;
                        $order->save();
                    }
            }
        });
    }
}
