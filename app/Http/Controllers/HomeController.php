<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\Order;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(Auth::check()) {
            if(Auth::user()->type == 'admin'){
                $yesterday = date('Y-m-d', strtotime('yesterday'));
                $today = date('Y-m-d');
                $data = array();
                $order = Order::where('cancel_date', null)->get();
                $totalOrder = $order->count();
                $data['totalOrder'] = $totalOrder;
                $pending = Order::where('order_date', '!=', null)->where('confirm_date', null)->where('cancel_date', null)->get();
                $totalPending = $pending->count();
                $data['totalPending'] = $totalPending;
                $shipped = Order::where('shipped_date', '!=', null)->where('delivered_date', null)->where('cancel_date', null)->get();
                $totalShipped = $shipped->count();
                $data['totalShipped'] = $totalShipped;
                $delivered = Order::where('delivered_date', '!=', null)->where('cancel_date', null)->get();
                $totalDelivered = $delivered->count();
                $data['totalDelivered'] = $totalDelivered;
                
                //////Get Todays Order////////
                $todays_pending = Order::where('order_date', $today)->where('status','pending')->get();
                $todays_order = Order::where('order_date', $today)->get();
                $data['todays_order'] = $todays_order;
                //////Get Weekly Sales//////
                $lastday = date('Y-m-d', strtotime('-6 days'));
                $WeekDayArray = array();
                $SaleByDateArray = array();
                $last_week_order = Order::where('confirm_date', $lastday)->get();
                for($i = $lastday; $i < $today; ){
                    $lastday = date('Y-m-d', strtotime($lastday.'+1 days'));
                    $WeekDayArray[] = $lastday;
                    $SaleByDate = DB::table('orders')->select(DB::raw('SUM(total) as total'))->where('confirm_date', $lastday)->get();
                    foreach($SaleByDate as $sdate){
                        if($sdate->total == null){
                            $SaleByDateArray[] = 0;
                        }else{
                            $SaleByDateArray[] = $sdate->total;
                        }
                    }
                    $i = $lastday;
                }
                $data['WeekDayArray'] = $WeekDayArray;
                $data['SaleByDateArray'] = $SaleByDateArray;
                //////Get Monthly Sales//////
                $monthsArray = array();
                $SaleByMonthArray = array();
                for($i = 11; $i >= 0;  ){
                    $lastYearDay = date('Y-m-d', strtotime('-'.$i.' months'));
                    $firstDay = date('Y-m-01', strtotime($lastYearDay));
                    $lastDay = date('Y-m-t', strtotime($lastYearDay));
                    $monthsArray[] = date('M', strtotime($firstDay));

                    $SaleByMonth = DB::table('orders')->select(DB::raw('SUM(total) as total'))->whereBetween('confirm_date', [$firstDay, $lastDay])->where('cancel_date', null)->get();;
                    foreach($SaleByMonth as $smonth){
                        if($smonth->total == null){
                            $SaleByMonthArray[] = 0;
                        }else{
                            $SaleByMonthArray[] = $smonth->total;
                            
                        }
                    }
                    $i = $i-1;
                }
                $data['monthsArray'] = $monthsArray;
                $data['SaleByMonthArray'] = $SaleByMonthArray;

                return view('dashboard')->with(compact('data','todays_order','todays_pending'));
            }elseif(Auth::user()->type == 'customer'){
                return redirect('myaccount');
            }else{
                return redirect('login_register');
            }
        }
    }
}
