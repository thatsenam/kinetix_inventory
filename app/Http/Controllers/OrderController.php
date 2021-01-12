<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;

class OrderController extends Controller
{
    // public function getPending(){
    //     $getPendings = Order::where('status', 'pending')->get();
    //     return view('admin.orders.pending-orders')->with('getPendings', $getPendings);
    // }

    public function pendings(Request $request){
        $getPendings = Order::where('status', 'pending')->get();
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $data = DB::table('orders')
                ->where('status', 'pending')
                ->whereBetween('order_date', array($request->from_date, $request->to_date))->get();
            }else{
                $data = Order::where('status', 'pending')->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.orders.pending-orders')->with('getPendings', $getPendings);
    }

    public function getConfirmed(Request $request){
        $getConfirmed = Order::where('status', 'confirmed')->get();
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $data = DB::table('orders')
                ->whereNotNull('confirm_date')
                ->whereBetween('order_date', array($request->from_date, $request->to_date))->get();
            }else{
                $data = Order::where('status', 'confirmed')->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.orders.confirmed-orders')->with('getConfirmed', $getConfirmed);
    }

    public function save_confirmed(Request $request){
        
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->shipped_date = Date('Y-m-d');
            $order->status = "Shipped";
            $order->save();
        }
        echo "Status Updated";
    }

    public function ConfirmAsDelivered(Request $request){
        
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->delivered_date = Date('Y-m-d');
            $order->status = "Delivered";
            $order->save();
        }
        echo "Status Updated";
    }

    public function ConfirmAsCancel(Request $request){
        
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->cancel_date = Date('Y-m-d');
            $order->status = "Canceled";
            $order->save();
        }
        echo "Status Updated";
    }

    public function ConfirmAsPaid(Request $request){
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->payment_status = "Paid";
            $order->is_paid = 1;
            $order->save();
        }
        echo "Status Updated";
    }

    public function getInvoice($id){
        $order_no = $id;
        $order = Order::where('order_number', $order_no)->get();
        $order_data = array();
        foreach($order as $ord){
            $order_data[] = $order_no;
            $order_data[] = $ord['delivery_charge'];
            $order_data[] = $ord['total'];
            $order_data[] = $ord['grand_total'];
            $order_data[] = $ord['created_at'];
        }
        $order_details = DB::table('order_details')->select('products.product_name as name', 'products.product_img as image', 'order_details.quantity as qnt', 'order_details.price as price','order_details.filter as filter', 'order_details.filter_value as filter_value')
        ->join('products', 'order_details.product_id', 'products.id')->where('order_details.order_id', $order_no)->get();

        return view('admin.orders.invoice')->with(compact('order_no','order','order_data','order_details'));
    }

    public function save_pending(Request $request){
        
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->confirm_date = Date('Y-m-d');
            $order->status = "Confirmed";
            $order->save();
        }
        echo "Status Updated";
    }

    public function cancel_pending(Request $request){
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->cancel_date = Date('Y-m-d');
            $order->status = "Canceled";
            $order->save();
        }
        echo "Status Updated";
    }

    public function saveas_paid(Request $request){
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->confirm_date = Date('Y-m-d');
            $order->payment_status = "Paid";
            $order->is_paid = 1;
            $order->save();
        }
        echo "Status Updated";
    }

    //Shipped
    public function getShipped(){
        $getShipped = Order::where('status', 'shipped')->get();
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $data = DB::table('orders')
                ->whereNotNull('shipped_date')
                ->whereBetween('order_date', array($request->from_date, $request->to_date))->get();
            }else{
                $data = Order::where('status', 'shipped')->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.orders.shipped-orders')->with('getShipped', $getShipped);
    }

    public function ShippedAsDelievred(Request $request){
        
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->delivered_date = Date('Y-m-d');
            $order->status = "Delivered";
            $order->save();
        }
        echo "Status Updated";
    }

    public function ShippedAsPaid(Request $request){
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->payment_status = "Paid";
            $order->is_paid = 1;
            $order->save();
        }
        echo "Status Updated";
    }

    public function ShippedAsCancel(Request $request){
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->cancel_date = Date('Y-m-d');
            $order->status = "Canceled";
            $order->save();
        }
        echo "Status Updated";
    }
    
    //Delivered
    public function getDelivered(){
        $getDelivered = Order::where('status', 'delivered')->get();
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $data = DB::table('orders')
                ->whereNotNull('delivered_date')
                ->whereBetween('order_date', array($request->from_date, $request->to_date))->get();
            }else{
                $data = Order::where('status', 'Delivered')->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.orders.delivered-orders')->with('getDelivered', $getDelivered);
    }

    public function DeliveredAsPaid(Request $request){
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->payment_status = "Paid";
            $order->is_paid = 1;
            $order->save();
        }
        echo "Status Updated";
    }

    public function getCanceled(){
        $getCanceled = Order::where('status', 'canceled')->get();
        if(request()->ajax()){
            if(!empty($request->from_date)){
                $data = DB::table('orders')
                ->whereNotNull('cancel_date')
                ->whereBetween('order_date', array($request->from_date, $request->to_date))->get();
            }else{
                $data = Order::whereNotNull('cancel_date')->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.orders.canceled-orders')->with('getCanceled', $getCanceled);
    }

    public function saveCanceled(Request $request){
        
        $orderid = $request['orderid'];
        $array = explode(",",$orderid);
		$iterate = count($array);
		for($i = 0; $i < $iterate; $i++){
            $id = $array[$i];
            $order = Order::where('order_number', $id)->first();
            $order->confirm_date = Date('Y-m-d');
            $order->status = "Confirmed";
            $order->save();
        }
        echo "Status Updated";
    }
}
