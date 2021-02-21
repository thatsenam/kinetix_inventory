<?php

namespace App\Http\Controllers;

use App\AccTransaction;
use App\BankTransaction;
use App\Category;
use App\Cost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostController extends Controller
{
    public function add_cost(Request $req){
        $fieldValues = json_decode($req['fieldValues'], true);
        $vno = $fieldValues['vno'];
        $head = $fieldValues['head'];
        $description = $fieldValues['description'];
        $paytype = $fieldValues['paytype'];
       
        $remarks = $fieldValues['remarks'] ?? '';
        

        $clients_bank = $fieldValues['clients_bank'] ?? '';
        $clients_bank_acc = $fieldValues['clients_bank_acc'] ?? '';
        $check_amount = $fieldValues['check_amount'] ?? '';
        $check_cash = $fieldValues['check_cash'] ?? '';
        $check_no = $fieldValues['check_no'] ?? '';
        $check_date = $fieldValues['check_date'] ?? '';
        $check_type = $fieldValues['check_type'] ?? '';
        
        $shops_bank_account = $fieldValues['shops_bank_account'] ?? '';
        $shops_bank_name = $fieldValues['shops_bank_name'] ?? '';
        $check_remarks = $fieldValues['check_remarks'] ?? '';

        
        $mobile_bank_acc_cust = $fieldValues['mobile_bank_acc_cust'] ?? '';
        $mobile_bank_account = $fieldValues['mobile_bank_account'] ?? '';
        $mobile_bank_name = $fieldValues['mobile_bank_name'] ?? '';
        
        $mobile_amount = $fieldValues['mobile_amount'] ?? '';
        $mobile_cash = $fieldValues['mobile_cash'] ?? '';
        $tranxid = $fieldValues['tranxid'] ?? '';
        $mobile_remarks = $fieldValues['mobile_remarks'] ?? '';

        $card_type = $fieldValues['card_type'] ?? '';
        
        $card_bank_account = $fieldValues['card_bank_account'] ?? '';
        $card_bank_name = $fieldValues['card_bank_name'] ?? '';
        $card_amount = $fieldValues['card_amount'] ?? '';
        $card_cash = $fieldValues['card_cash'] ?? '';
        $card_remarks = $fieldValues['card_remarks'] ?? '';

        $card = '';
        if($card_type == 'visa'){
            $card = "ভিসা কার্ড";
        }else if($card_type == 'master'){
            $card = "মাস্টার কার্ড";
        }else if($card_type == 'credit'){
            $card = "ক্রেডিট কার্ড";
        }else if($card_type == 'debit'){
            $card = "ডেবিট কার্ড";
        }else{
            $card = "ক্যাশ";
        }

        if($paytype == 'ক্যাশ'){
            $note = "ক্যাশে পরিশোধ";
        }
        if($paytype == 'কার্ড'){
            $note = "কার্ডে পরিশোধ";
        }
        if($paytype == 'ব্যাংক'){
            $note = "চেকে পরিশোধ";
        }
        if($paytype == 'মোবাইল'){
            $note = "ব্যাংক ট্রান্সফার";
        }

        $amount = $fieldValues['amount'];
        $date = $fieldValues['date'];
        $user = Auth::id();

        if($amount > 0){
            $head = $head;
            $cost_debit = $amount;
            $cost_credit = 0;

                AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => 0,
                'notes' => $note,
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }
        if($mobile_cash >0){
            $head = "Cash In Hand";
            $description = "ব্যয় নগদ পরিশোধ ".$vno;
            $cost_debit = 0;
            $cost_credit = $mobile_cash;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => -1,
                'notes' => "নগদ পরিশোধ",
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }
        if($check_cash > 0 ){
            $head = "Cash In Hand";
            $description = "ব্যয় নগদ পরিশোধ ".$vno;
            $cost_debit = 0;
            $cost_credit = $check_cash;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => -1,
                'notes' => "নগদ পরিশোধ",
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }
        if($card_cash >0 ){
            $head = "Cash In Hand";
            $description = "ব্যয় নগদ পরিশোধ ".$vno;
            $cost_debit = 0;
            $cost_credit = $card_cash;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => -1,
                'notes' => "নগদ পরিশোধ",
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }
        if($paytype == 'ক্যাশ'){
            $head = "Cash In Hand";
            $description = "ব্যয় নগদ পরিশোধ ".$vno;
            $cost_debit = 0;
            $cost_credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => -1,
                'notes' => "নগদ পরিশোধ",
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }

        
        ///// Card transaction

        if($paytype == 'কার্ড'){
            
            $str_arr = explode (",", $card_bank_account);  
            $str_arr2 = explode (",", $card_bank_name);  
//            DB::table('bank_transactions')->insert([
            BankTransaction::create([

                'seller_bank_id' => $str_arr[0],
                'seller_bank_acc_id' => $str_arr[1],
                'clients_bank' => $card,
                'clients_bank_acc' => "Payment by Card",
                'date' => $date,
                'sid' => '',
                'invoice_no' => $vno,
                'withdraw' => $card_amount,
                'type' => $card,
                'status' => 'paid',
                'remarks' => $remarks,
                'user' => $user,

            ]);

            $head = $str_arr2[0]." ".$str_arr2[1];
            $description = "ব্যয় কার্ড পরিশোধ ".$vno;
            $cost_debit = 0;
            $cost_credit = $card_amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => -1,
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }

        ///// Check transaction

        if($paytype == 'ব্যাংক'){
            $str_arr = explode (",", $shops_bank_account);  
            $str_arr2 = explode (",", $shops_bank_name);  
            BankTransaction::create([

                'seller_bank_id' => $str_arr[0],
                'seller_bank_acc_id' => $str_arr[1],
                'clients_bank' => $clients_bank,
                'clients_bank_acc' => $clients_bank_acc,
                'check_no' => $check_no,
                'check_date' => $check_date,
                'date' => $date,
                'sid' => '',
                'invoice_no' => $vno,
                'withdraw' => $check_amount,
                'type' => 'check',
                'status' => 'pending',
                'remarks' => $remarks,
                'user' => $user,

            ]);

        }

        ///// Mobile/Bank Transfer transaction

        if($paytype == 'মোবাইল'){
            $str_arr = explode (",", $mobile_bank_account);  
            $str_arr2 = explode (",", $mobile_bank_name);
            BankTransaction::create([

                'seller_bank_id' => $str_arr[0],
                'seller_bank_acc_id' => $str_arr[1],
                'clients_bank' => $clients_bank,
                'clients_bank_acc' => $clients_bank_acc,
                'date' => $date,
                'sid' => '',
                'invoice_no' => $vno,
                'withdraw' => $mobile_amount,
                'type' => 'mobile',
                'status' => 'paid',
                'tranxid' => $tranxid,
                'remarks' => $remarks,
                'user' => $user,

            ]);

            
            $head = $str_arr2[0]." ".$str_arr2[1];
            $description = "ব্যয় ব্যাংক ট্র্যান্সফার ".$vno;
            $cost_debit = 0;
            $cost_credit = $mobile_amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => -1,
                'description' => $description,
                'debit' => $cost_debit,
                'credit' => $cost_credit,
                'date' => $date,

            ]);
        }

    }
    public function CreateCost(Request $request){
        $categories = Category::orderBy('name', 'ASC')->get();
        if($request->isMethod('post')){
            $data = $request->all();
            $cost = new Cost();
            $cost->category_id = $data['category'] ?? '';
            $cost->sub_category = $data['sub_category'] ?? '';
            $cost->reason = $data['reason'] ?? '';
            $cost->amount = $data['amount'] ?? '';
            $cost->details = $data['details'] ?? '';
            $cost->date = $data['date'] ?? '';

            $cost->save();
            return redirect('/admin/create_cost')->with('flash_message_success', ' ব্যয় সফলভাবে যুক্ত হয়েছে!');
        }
        return view('admin.pos.cost.add_cost')->with('categories', $categories);
    }

    public function cost_reports(){
        $costs = AccTransaction::orderBy('date', 'DESC')->where('client_id',auth()->user()->client_id)->where('sort_by','0')->get();
        return view('admin.pos.cost.view_costs')->with('costs', $costs);
    }

    public function editCost(Request $req, $id = null){
        $cost = Cost::where(['id'=>$id])->first();
        $categories = Category::orderBy('id','ASC')->get();
        if($req->isMethod('post')){
            $data = $req->all();
            
            Cost::where(['id'=>$id])->update(['category_id'=>$data['category'],'sub_category'=>$data['sub_category'],'reason'=>$data['reason'],'amount'=>$data['amount'],'details'=>$data['details'],'date'=>$data['date'],]);
            
            return redirect('/admin/cost_reports')->with('flash_message_success', ' ব্যয় সফলভাবে আপডেট হয়েছে!');
        }
        return view('admin.pos.cost.edit_cost')->with('cost', $cost)->with('categories', $categories)->with('id', $id);
    }

    public function deleteCost($id)
    {
        $delete = AccTransaction::where('vno', $id)->delete();
        $delete=BankTransaction::where('invoice_no',$id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = " ব্যয় সফলভাবে ডিলিট হয়েছে!";
        } else {
            $success = true;
            $message = " ব্যয়টি খুজে পাওয়া যায়নি !";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }
}
