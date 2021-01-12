<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use App\UserProfile;

Use Session;
Use App\Order;

class UsersController extends Controller
{

    public function LoginRegister(){
        return view('users.login_register');
    }
    public function register(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $userCheck = User::where('email',$data['inputEmail'])->count();
            if($userCheck>0){
                return redirect()->back()->with('flash_message_error', 'Email Address Already Exists!');
            }else{
                $user = new User;
                $user->name = $data['inputName'];
                $user->email = $data['inputEmail'];
                $user->phone = $data['inputPhone'];
                $user->password = bcrypt($data['inputPass']);
                $user->type = "customer";
                $user->address = $data['inputAddress'];
                $user->save();

                $get_user_id = User::where('email',$data['inputEmail'])->first();
                $user_id = $get_user_id->id;

                $user_profile = new UserProfile;
                $user_profile->user_id = $user_id;
                $user_profile->billing_name = $data['inputName'];
                $user_profile->billing_address = $data['inputAddress'];
                $user_profile->billing_phone = $data['inputPhone'];
                $user_profile->email = $data['inputEmail'];
                $user_profile->shipping_name = $data['inputName'];
                $user_profile->shipping_address = $data['inputAddress'];
                $user_profile->shipping_phone = $data['inputPhone'];
                $user_profile->status = "active";
                $user_profile->save();

                if(Auth::attempt(['email'=>$data['inputEmail'],'password'=>$data['inputPass']])){
                    Session::put('logSession',$data['logEmail']);
                    return redirect('/myacount');
                }
            }
        }
    }

    public function logoin(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            if(Auth::attempt(['email'=>$data['logEmail'],'password'=>$data['logPass']])){
                Session::put('logSession',$data['logEmail']);
                $userDetails = DB::table('user_profiles')->select('user_profiles.billing_name as bname', 'user_profiles.billing_address as baddress','user_profiles.billing_phone as bphone', 'user_profiles.email as email', 'user_profiles.shipping_name as sname', 'user_profiles.shipping_address as saddress','user_profiles.shipping_phone as sphone','user_profiles.image as img','user_profiles.status as status','user_profiles.created_at as date')
                ->where('user_profiles.email',$data['logEmail'])->get();
                return redirect('/myaccount')->with('userDetails');
            }else{
                return redirect()->back()->with('flash_message_error', 'Invalid Username or Password!');
            }
        }
    }

    public function logout(){
        Auth::logout();
        Session::forget('logSession');
        return redirect('/');
    }

    public function myaccount(){
        if(Auth::check()) {
            if(Auth::user()->type == 'admin'){
                return redirect('dashboard');
            }
            $user_id = Auth::id();
            // echo $user_id; die;
            $user_info = DB::table('users')
                ->select('users.id as user_id', 'users.name as name', 'users.phone as phone', 'users.email as email', 'users.created_at as since',
            'user_profiles.billing_name as billing_name', 'user_profiles.billing_address as billing_address', 'user_profiles.billing_phone as billing_phone',
            'user_profiles.shipping_name as shipping_name', 'user_profiles.shipping_address as shipping_info', 'user_profiles.shipping_phone as shipping_phone','user_profiles.status as status','user_profiles.image as image')
            ->join('user_profiles', 'users.id', 'user_profiles.user_id')
            ->where('user_profiles.user_id', $user_id)
                ->where('user_profiles.status', 'active')->first();

            $orders = Order::where('user_id', $user_id)->get();
            $ordCount = count($orders);

            $ord_array = array();
            foreach($orders as $ord){
                $ordno = $ord['order_number'];
                $ordid = $ord['id'];
                $ord_array[] = $ordno;
                $ord_array[] = $ord['order_date'];
                // $img_array = array();
                // $order_details = DB::table('order_details')->where('order_id', $ordno)->get();

                // // foreach($order_details as $dtl){
                // //     $pid = $dtl->product_id;
                // //     $get_name = DB::table('products')->where('id', $pid)->get();
                // //     foreach($get_name as $name){
                // //         $name_array[] = $name->product_name;
                // //     }
                // // }

                // // $ord_array[] = $name_array;
                $ord_array[] = $ord['grand_total'];
                $ord_array[] = $ord['status'];
                $ord_array[] = $ordid;
            }

            $test = "Test";

            return view('users.myaccount')->with('user_info', $user_info)->with('ord_array', $ord_array)->with('ordCount', $ordCount);

        } else {
            return redirect('login_register');
        }
    }

    public function invoice($id){
        if(Auth::check()) {
            $user_id = Auth::id();
            // echo $user_id; die;
            $user_info = DB::table('users')->select('users.id as user_id', 'users.name as name', 'users.phone as phone', 'users.email as email', 'users.created_at as since',
            'user_profiles.billing_name as billing_name', 'user_profiles.billing_address as billing_address', 'user_profiles.billing_phone as billing_phone',
            'user_profiles.shipping_name as shipping_name', 'user_profiles.shipping_address as shipping_info', 'user_profiles.shipping_phone as shipping_phone','user_profiles.status as status','user_profiles.image as image')
            ->join('user_profiles', 'users.id', 'user_profiles.user_id')
            ->where('user_profiles.user_id', $user_id)->where('user_profiles.status', 'active')->first();

            $orders = Order::where('user_id', $user_id)->get();
            $ordCount = count($orders);
        }

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

        $order_details = DB::table('order_details')->select('products.product_name as name', 'products.product_img as image', 'order_details.quantity as qnt', 'order_details.price as price')
        ->join('products', 'order_details.product_id', 'products.id')->where('order_details.order_id', $order_no)->get();

        $user_profile = DB::table('user_profiles')->where('user_id', Auth::id())->get();

        $order_status = DB::table('orders')->where('order_number', $order_no)->get();

        $status_array = array();

        foreach($order_status as $sta){
            $status_array[] = $sta->order_date;
            $status_array[] = $sta->confirm_date;
            $status_array[] = $sta->shipped_date;
            $status_array[] = $sta->delivered_date;
            $status_array[] = $sta->cancel_date;
        }

        return view('users.order-details')->with('order_no', $order_no)->with('order_details', $order_details)->with('order_data', $order_data)
        ->with('user_profile', $user_profile)->with('status_array', $status_array)->with('user_info',$user_info)->with('ordCount',$ordCount);

    }

    public function updateOrder($id)
    {
        $date = date("Y-m-d");
        $update = Order::where('id',$id)->update(['cancel_date' => $date, 'status' => "canceled"]);
        if ($update == 1) {
            $success = true;
            $message = "Order canceled successfully!";
        } else {
            $success = true;
            $message = "Order not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function updateBilling(Request $request){
        $billName = $request['billName'];
        $billPhone = $request['billPhone'];
        $billAddress = $request['billAddress'];
        if(Auth::check()) {
            $user_id = Auth::id();
            UserProfile::where('user_id',$user_id)->update(['billing_name' => $billName, 'billing_address' => $billAddress, 'billing_phone' => $billPhone]);

            // $user = UserProfile::find($user_id);
            // if($request->hasFile('ProImg')){
            //     // $prev_img = $user->image;
            //     // $image_path = 'images/users/'.$prev_img;
            //     // if(file_exists($image_path)) {
            //     //     @unlink($image_path);
            //     // }
            //     $file = $request->file('ProImg');
            //     $basename = basename($file);
            //     $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
            //     $file->move('images/users/', $img_name);
            //     $user->image = $img_name;
            // }
            // $user->save();

            echo 'Billing Info Updated Successfully!';
        }
    }

    public function updateShipping(Request $request){
        $ShipName = $request['ShipName'];
        $shipPhone = $request['shipPhone'];
        $shipAddress = $request['shipAddress'];
        if(Auth::check()) {
            $user_id = Auth::id();
            UserProfile::where('user_id',$user_id)->update(['shipping_name' => $ShipName, 'shipping_address' => $shipAddress, 'shipping_phone' => $shipPhone]);

            echo 'Shipping Info Updated Successfully!';
        }
    }

}
