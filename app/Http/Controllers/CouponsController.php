<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
use Session;

class CouponsController extends Controller
{
    public function createCoupon(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $coupon = new Coupon;
            $coupon->coupon_code = $data['inputCode'];
            $coupon->amount = $data['inputAmount'];
            $coupon->amount_type = $data['inputType'];
            $coupon->expiry_date = $data['ExpiryDate'];
            $coupon->status = $data['inputEnable'];
            $coupon->save();
            return redirect()->action('CouponsController@ViewCoupons')->with('flash_message_success', 'Coupon Created Successfully!');
        }
        return view('admin.create_coupon');
    }

    public function editCoupon(Request $request,$id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            $coupon = Coupon::find($id);
            $coupon->coupon_code = $data['inputCode'];
            $coupon->amount = $data['inputAmount'];
            $coupon->amount_type = $data['inputType'];
            $coupon->expiry_date = $data['ExpiryDate'];
            if(empty($data['inputEnable'])){
                $data['inputEnable'] = 0;
            }
            $coupon->status = $data['inputEnable'];
            $coupon->save();
            return redirect()->action('CouponsController@ViewCoupons')->with('flash_message_success', 'Coupon Updated Successfully!');
        }
        $couponData = Coupon::find($id);
        return view('admin.edit_coupon')->with(compact('couponData'));
    }

    public function ViewCoupons(){
        $coupons = Coupon::get();
        return view('admin.view_coupons')->with(compact('coupons'));
    }

    public function deleteCoupon($id){
        $delete = Coupon::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Coupon deleted successfully!";
        } else {
            $success = true;
            $message = "Coupon not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
