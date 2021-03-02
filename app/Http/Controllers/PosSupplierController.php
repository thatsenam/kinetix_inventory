<?php

namespace App\Http\Controllers;

use App\AccHead;
use App\BankTransaction;
use App\PaymentInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Supplier;
use App\ProductImage;
use App\AccTransaction;
use App\GeneralSetting;
use Image;
use Auth;

class PosSupplierController extends Controller
{
    public function setSupplier(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            $supplier = new Supplier;
            $supplier->name = $data['inputName'];
            $supplier->phone = $data['inputPhone'];
            $supplier->address = $data['inputAddress'];
            $supplier->user = Auth::id();
            $supplier->save();

            $sid = (DB::table('suppliers')->max('id'));

//            DB::table('acc_heads')->insert([
            AccHead::create([
                'cid' => 'sid'." ".$sid,
                'parent_head' => 'Liabilities',
                'sub_head' => 'Suppliers Payable',
                'head' => $data['inputName']." ".$data['inputPhone'],
                // 'user' => Auth::id(),
            ]);

            return redirect('/dashboard/suppliers')->with('flash_message_success', 'Supplier Created Successfully!');
        }
        $suppliers = DB::table('suppliers')->where('client_id',auth()->user()->client_id)->orderBy('id', 'DESC')->get();
        return view('admin.pos.suppliers.suppliers')->with(compact('suppliers'));
    }

    public function edit(Request $request){
        $id = $request->id;
        $get_data = DB::table('suppliers')
            ->where('client_id',auth()->user()->client_id)
            ->select('suppliers.name','suppliers.phone','suppliers.address','suppliers.email')->where('id',$id)->first();

        $data = array(
            'name' => $get_data->name,
            'phone' => $get_data->phone,
            'address' => $get_data->address,
            'email' => $get_data->email
        );
        return json_encode($data);
    }

    public function updateSupp(Request $request){
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $address = $request->address;
        $email = $request->email;

        $prev_supplier = Supplier::find($id);

        DB::table('suppliers')
            ->where('client_id',auth()->user()->client_id)
            ->where(['id'=>$id])->update(['name'=>$name,'phone'=>$phone,'address'=>$address,'email'=>$email]);
        
        $sid = "sid ".$id;
        $head = $name." ".$phone;
        
        AccHead::where('client_id', auth()->user()->client_id)
                ->where('cid', $sid)->update([
                    'head' => $head,
                ]);

        $prev_supp_name = $prev_supplier->name;
        // $prev_cust_phone = $prev_supplier->phone;
        // $cust_head = $prev_cust_name." ".$prev_cust_phone;
        
        AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('sort_by', $sid)
            ->where('head', $prev_supp_name)
            ->update([
                'head' => $name,
            ]);

        echo 'Supplier Data Updated Successfully!';
    }

    public function deleteSupp($id){
        $delete = Supplier::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Supplier deleted successfully!";
        } else {
            $success = true;
            $message = "Supplier not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function suppDetails(Request $request){
        $id = $request->id;
        $get_data = DB::table('suppliers')
            ->where('client_id',auth()->user()->client_id)
            ->select('suppliers.name','suppliers.phone','suppliers.address')->where('id', $id)->first();
        $get_head = DB::table('acc_heads')
            ->where('client_id',auth()->user()->client_id)
            ->where('cid',"sid ".$id)->first();
        $head = $get_head->head;
        $debit = DB::table('acc_transactions')
            ->where('client_id',auth()->user()->client_id)
            ->where('head',$head)->sum('debit');
        $credit = DB::table('acc_transactions')
            ->where('client_id',auth()->user()->client_id)
            ->where('head',$head)->sum('credit');
        $balance = $debit - $credit;
        $data = array(
            'id' => $id,
            'name' => $get_data->name,
            'phone' => $get_data->phone,
            'address' => $get_data->address,
            'head' => $get_head->head,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $balance
        );
        return json_encode($data);
    }

    public function addPayment(Request $request){

        $fieldValues = json_decode($request['fieldValues'], true);

        $supp_id = $fieldValues['supp_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $amount = $fieldValues['amount'];
        dd(json_encode($fieldValues));
        $paytype = $fieldValues['paytype'];
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();
        $cardtype = $fieldValues['cardtype'];
        $cardbank = $fieldValues['cardbank'];
        $card_bank_id = $fieldValues['card_bank_id'];
        $card_bank_account = $fieldValues['card_bank_account'];
        $card_bank_acc_id = $fieldValues['card_bank_acc_id'];

        $clients_bank = $fieldValues['clientsbank'];
        $clientsbacc = $fieldValues['clientsbacc'];
        $checkno = $fieldValues['checkno'];
        $checktype = $fieldValues['checktype'];
        $checkdate = $fieldValues['checkdate'];
        $shopbank = $fieldValues['shopbank'];
        $bank_id = $fieldValues['bank_id'];
        $shops_bank_account = $fieldValues['checksbacc'];
        $account_id = $fieldValues['account_id'];

        $btcbank = $fieldValues['btcbank'];
        $btcbankacc = $fieldValues['btcbankacc'];
        $bankaacno = $fieldValues['bankaacno'];
        $mobile_bank = $fieldValues['bt_shops_bank'];
        $mobile_bank_account = $fieldValues['bt_shops_bank_acc'];
        $mobile_bank_id = $fieldValues['mobile_bank_id'];
        $mobile_bank_acc_id = $fieldValues['mobile_bank_acc_id'];
        $tranxid = $fieldValues['tranxid'];

        $card = '';
        if($cardtype == 'visa'){
            $card = "Visa Card";
        }else if($cardtype == 'master'){
            $card = "MasterCard";
        }else if($cardtype == 'credit'){
            $card = "Crebit Card";
        }else if($cardtype == 'debit'){
            $card = "Debit Card";
        }else{
            $card = "Cash";
        }

        $desc = '';
        if($paytype == 'cash'){
            $desc = "Paid In Cash";
        }
        if($paytype == 'card'){
            $desc = "Card Payment";
        }
        if($paytype == 'cheque'){
            $desc = "Cheque Payment";
        }
        if($paytype == 'bank_transfer'){
            $desc = "Mobile Banking/Bank Tranfer";
        }

        $maxid = (DB::table('payment_invoice')->max('id') + 1);
        $invoice = "Inv-".$maxid;

//        DB::table('payment_invoice')->insert([
            PaymentInvoice::create([

            'invoice_no' => $invoice,
            'cid' => 'sid'." ".$supp_id,
            'amount' => $amount,
            'method' => $card,
            'description' => $desc,
            'remarks' => $remarks,
            'date' => $date,
            'user' => $user,
        ]);

        if($paytype == 'cash'){
            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = $cust_name." ".$cust_phone;
            $description = "Payment Invoice ".$invoice;
            $debit = $amount;
            $credit = 0;

//            DB::table('acc_transactions')->insert([
                AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'notes' => "Paid In Cash",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            // if($request->hasFile('inputImage')){
            //     $file = $request->file('inputImage');
            //     $basename = basename($file);
            //     $img_name = $basename.time().$file->getClientOriginalExtension();
            //     $file->move('images/documents/', $img_name);
            //     $product->product_img = $img_name;
            // }

            $head = "Cash In Hand";
            $description = "Cash Payment Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'notes' => "Recieved In Cash",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);
        }

        ///// Card transaction

        if($paytype == 'card'){

//            DB::table('bank_transactions')->insert([
            BankTransaction::create([

                'seller_bank_id' => $card_bank_id,
                'seller_bank_acc_id' => $card_bank_acc_id,
                'clients_bank' => $card,
                'clients_bank_acc' => "Payment by Card",
                'date' => $date,
                'sid' => $supp_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'card',
                'status' => 'paid',
                'remarks' => $remarks,
                'user' => $user,

            ]);

            /////Insert into Accounts For Card Transaction

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = $cust_name." ".$cust_phone;
            $description = "Payment Invoice ".$invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = $cardbank." A/C: ".$card_bank_account;
            $description = "Card Payment Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);
        }

        ///// Check transaction

        if($paytype == 'cheque'){

            BankTransaction::create([

                'seller_bank_id' => $bank_id,
                'seller_bank_acc_id' => $account_id,
                'clients_bank' => $clients_bank,
                'clients_bank_acc' => $clientsbacc,
                'check_no' => $checkno,
                'check_date' => $checkdate,
                'date' => $date,
                'sid' => $supp_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'check',
                'status' => 'pending',
                'remarks' => $remarks,
                'user' => $user,

            ]);

        }

        ///// Mobile/Bank Transfer transaction

        if($paytype == 'bank_transfer'){

            BankTransaction::create([

                'seller_bank_id' => $mobile_bank_id,
                'seller_bank_acc_id' => $mobile_bank_acc_id,
                'clients_bank' => $btcbank,
                'clients_bank_acc' => $btcbankacc,
                'date' => $date,
                'sid' => $supp_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'mobile',
                'status' => 'paid',
                'tranxid' => $tranxid,
                'remarks' => $remarks,
                'user' => $user,

            ]);

            /////Insert into Accounts For Mobile Transaction

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = $cust_name." ".$cust_phone;
            $description = "Payment Invoice ".$invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = $mobile_bank." A/C: ".$mobile_bank_account;
            $description = "Bank Transfer Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);
        }

        echo $invoice;

    }

    public function getSupplier($id){

        $supplier = Supplier::where(['id'=>$id])->first();
        $get_head = DB::table('acc_heads')->where('cid',"sid ".$id)->first();
        $head = $get_head->head;
        $ledgers = AccTransaction::where(['head'=>$head])->get();
        $total_purchase = DB::table('purchase_primary')
            ->where('client_id',auth()->user()->client_id)
            ->where('sid',$id)->sum('amount');
        $purchase = DB::table('purchase_primary')
            ->where('client_id',auth()->user()->client_id)
            ->where('sid',$id)->get();
        $fromPurchase = DB::table('purchase_primary')
            ->where('client_id',auth()->user()->client_id)
            ->where('sid',$id)->sum('payment');
        $fromPayment = DB::table('payment_invoice')
            ->where('client_id',auth()->user()->client_id)
            ->where('cid',"sid ".$id)->sum('amount');
        $total_purchase_paid = $fromPurchase + $fromPayment;
        $purchase_due = $total_purchase - $total_purchase_paid;
        // $total_return = DB::table('purchase_returns')->where('sid',$id)->sum('total');
        // $cash_return = DB::table('sales_return')->where('cid',$id)->sum('cash_return');
        // $return_due = $total_return - $cash_return;
        return view('admin.pos.suppliers.supplier_details')->with(compact('supplier','get_head','total_purchase','total_purchase_paid','purchase_due','ledgers','purchase'));

    }

    public function filter_data(Request $request){
        if(request()->ajax()){
            $sid = $request->sid;
            if(!empty($request->from_date)){
                $data = DB::table('acc_transactions')
                    ->where('client_id',auth()->user()->client_id)
                    ->where('sort_by',$sid)->whereBetween('date', array($request->from_date, $request->to_date))->get()->toArray();
            }else{
                $data = DB::table('acc_transactions')
                    ->where('client_id',auth()->user()->client_id)
                    ->where('sort_by',$sid)->get()->toArray();
            }

            $balance  = 0;

            foreach($data as $index=>$d){
                if($d->debit > 0){
                    $balance = ($balance + $d->debit);
                }else{
                    $balance = ($balance - $d->credit);
                }

                $d->balance = ($balance);
            }
            return datatables()->of($data)->make(true);
        }
    }

    public function purchaseinvoice($id){
        $invoiceno = $id;
        $get_supplier = DB::table('purchase_primary')
            ->where('pur_inv', $id)->first();
        $sid = $get_supplier->sid;
        $supp_details = Supplier::where(['id'=>$sid])->get();

        $details = DB::table('purchase_details')->select('products.product_name as name', 'products.product_img as image', 'purchase_details.qnt as qnt', 'purchase_details.price as price', 'purchase_details.total as total')
        ->join('products', 'purchase_details.pid', 'products.id')->where('purchase_details.pur_inv', $invoiceno)->get();

        $settings = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.suppliers.payinvoice')->with(compact('supp_details','get_supplier','details', 'settings'));
    }
}
