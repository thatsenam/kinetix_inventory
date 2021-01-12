<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Products;
use App\Order;
use App\User;
use App\UserProfile;
use App\Order_detail;
use Session;

class CheckoutController extends Controller
{
    public function index(){
        $session_id = Session::get('session_id');
        $userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();
        foreach($userCart as $key => $product){
            $productData = Products::where('id',$product->product_id)->first();
            $userCart[$key]->image = $productData->product_img;
        }
        return view('checkout')->with(compact('userCart'));
    }

    public function thankyou(){
        return view('thankyou');
    }

    public function get_details(Request $request){
        $phone = $request['phone'];
        $get_details = DB::table('user_profiles')->where('billing_phone',$phone)->first();
        $details = array();
        $details['name'] = $get_details->shipping_name;
        $details['email'] = $get_details->email;
        $details['address'] = $get_details->shipping_address;
        return json_encode($details);
    }

    public function create(Request $request){

        $s_name = $request['s_name'];
        $s_phone = $request['s_phone'];
        $s_email = $request['s_email'];
        $s_address = $request['s_address'];
        $s_note = $request['s_note'];
        $discount = $request['discount'];
        $smethod = $request['smethod'];
        $pmethod = $request['pmethod'];
        if($smethod == 'Inside City'){
            $s_charge = 50.00;
        }
        else{
            $s_charge = 100.00;
        }
        
        date_default_timezone_set('Europe/London');
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $order_id = "RRWV-".date("his");
        
        $session_id = Session::get('session_id');
        $take_cart_items = DB::table('cart')->where(['session_id'=>$session_id])->get();
        $total_amount = 0;
        foreach($take_cart_items as $item){
            $total_amount = $total_amount + ($item->price * $item->quantity);
        }

        $isUniqueEmail = DB::table('users')->select('*')->where('email', $s_email)->get();
                
                foreach($isUniqueEmail as $unique){
                    $checkEmail = $unique->email;
                    $user_id = $unique->id;
                }
                
                if(!empty($checkEmail)){
                    
                    DB::table('user_profiles')->where('user_id', $user_id)->update(array(
                        'billing_name' => $s_name,
                        'billing_address' => $s_address,
                        'billing_phone' => $s_phone,
                        'email' => $s_email,
                        'shipping_name' => $s_name,
                        'shipping_address' => $s_address,
                        'shipping_phone' => $s_phone,
                    ));
                    
                  
                }else{
                    
                    try{
                        $user = new User; 
                    
                        $user->name = $s_name;
                        $user->phone = $s_phone;
                        $user->email = $s_email;
                        $user->type = "customer";
                        $user->password = bcrypt($request['s_phone']);
                        $user->save();
                        
                        $get_user_id = $user::where('email', $s_email)->first();
                        $user_id = $get_user_id->id;
                    }
                    catch(Exception $e)
                    {
                        echo $e->getMessage();
                        
                        exit;
                    }
                    
                    $user_profile = new UserProfile;
                
                    try{
                        
                        $user_profile->user_id = $user_id;
                        
                        $user_profile->billing_name = $s_name;
                        $user_profile->billing_address = $s_address;
                        $user_profile->billing_phone = $s_phone;
                        $user_profile->email = $s_email;
                        $user_profile->shipping_name = $s_name;
                        $user_profile->shipping_address = $s_address;
                        $user_profile->shipping_phone = $s_phone;
                        $user_profile->status = "active";

                        $user_profile->save(); 
                    }
                    catch(Exception $e)
                    {
                        echo $e->getMessage();
                        
                        exit;
                    }
                    
                }
                    
                if($request->ajax()){
                    $order = new Order; 
                    $user = $user_id;
                    $ip = request()->ip();
                    
                    $order->order_number = $order_id;
                    $order->user_id = $user;
                    $order->ip_address = $ip;
                    $order->name = $s_name;
                    $order->phone_no = $s_phone;
                    $order->email = $s_email;
                    $order->order_note = $s_note;
                    $order->total = $total_amount;
                    $order->discount = $discount;
                    $order->delivery_charge = $s_charge;
                    $hid_alltotal = ($total_amount + $s_charge) - $discount;
                    $order->grand_total = $hid_alltotal;
                    
                    $order->billing_info = $s_address;
                    $order->billing_name = $s_name;
                    $order->billing_phone = $s_phone;
                    $order->shipping_name = $s_name;
                    $order->shipping_phone = $s_phone;
                    $order->shipping_info = $s_address;
                    
                    $order->payment_method = $pmethod;
                    $order->shipping_method = $smethod;
                    $order->order_date = $date;
                    $order->status = "pending";
                    Session::put('order_number',$order->order_number);
                    $order->save();
                }

                if($request->ajax()){
                    foreach($take_cart_items as $key => $val){
                        $cartData = DB::table('cart')->where('product_id',$val->product_id)->first();
                        $take_cart_items[$key]->product_id = $cartData->product_id;
                        $proId = $take_cart_items[$key]->product_id;
                        $quantity = $take_cart_items[$key]->quantity;
                        $weight = $take_cart_items[$key]->weight;
                        $price = $take_cart_items[$key]->price;
                        if(!empty($val)){
                            
                            $OrderDetail = new Order_detail;
                            // $getId = $order = DB::table('orders')->where('order_number',$order_id)->get();
    
                            $OrderDetail->order_id = $order_id;
                            $OrderDetail->product_id = $proId;
                            $OrderDetail->quantity = $quantity;
                            $OrderDetail->price = $price;
                            $OrderDetail->filter = "Weight";
                            $OrderDetail->filter_value = $weight;
                            
                            $OrderDetail->save();
                        }
                    }
                }
                if($request->ajax()){
                    $abc = DB::table('cart')->where('session_id',$session_id)->get();
                    foreach ($abc as $a) {
                        $aid = $a->id;
                        DB::table('cart')->where('id',$aid)->delete();
                    }
                }


        ///////////////////Send Email TO Customer//////////////
        
        // $to = $s_email;
        // $subject = "Your order has been placed";
        // $msg = "Thank you for ordering with us. Your order will be ready wthin 4 working days. <br> Your Order Number is <b>".$order_id."</b> <br> 
        // Your Total bill is <b>&#163;".$hid_alltotal."<br></b> Hope you will enjoy the meal.";
        // $msg = wordwrap($msg,70);
            
        // $fromEmail = "monarul007@gmail.com";
            
        // $fromName = "BeautyShop";
            
        // $headers = 'MIME-Version: 1.0' . "\r\n";
        // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // $headers .= 'From:  ' . $fromName . ' <' . $fromEmail .'>' . " \r\n" .
        //                 'Reply-To: '.  $fromEmail . "\r\n" .
        //                 'X-Mailer: PHP/' . phpversion();
                
        // if(mail($s_email, $subject, $msg, $headers)){
        //     $msg = "A Mail has been sent to your eMail address.";
        // }
        
        /////////////////////////////////////////////////////

        // $order = DB::table('orders')->where('order_number', $order_id)->get();
        
        // $order_details = Order_detail::where('order_id', $order_id)->get();
        
        // $order_details = DB::table('products')->select('products.product_name as pname', 'products.product_img as image', 'order_details.quantity as qnt', 'order_details.price as price', 'order_details.filter_value as filter_value')
        // ->join('order_details', 'order_details.product_id', '=', 'products.id')
        // ->where('order_details.order_id', $order_id)->get();
        
        return redirect('invoice');
        
    }

    public function paypal(){
        return view('test');
    }


    public function invoice(){

        $order_number = Session::get('order_number');
        $order = DB::table('orders')->where('order_number', $order_number)->get();

        $order_details = Order_detail::where('order_id', $order_number)->get();
        $order_details = DB::table('products')->select('products.product_name as pname', 'products.product_img as image', 'order_details.quantity as qnt', 'order_details.price as price', 'order_details.filter as filter', 'order_details.filter_value as filter_value')
        ->join('order_details', 'order_details.product_id', '=', 'products.id')
        ->where('order_details.order_id', $order_number)->get();

        return view('invoice')->with(compact('order','order_details'));
    }

}
