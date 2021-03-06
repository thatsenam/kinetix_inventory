<?php

namespace App\Http\Controllers;

use App\AccHead;
use App\AccTransaction;
use App\BankInfo;
use App\BankTransaction;
use App\Customer;
use App\GeneralSetting;
use App\Helpers\AppHelper;
use App\PaymentInvoice;
use App\Product;
use App\ProductImage;
use App\Supplier;
use App\SupplierGroup;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

class PosSupplierController extends Controller
{


    public function getPreviousBalance_customer(Request $request)
    {
        $start_date = $request->from_date;
        $end_date = $request->to_date;

        $sid = $request->sid;
        $id = explode(' ', $sid)[1];
        $supplier = Customer::find($id);
//        return $supplier;
        $head = $supplier->name . ' ' . $supplier->phone;

        $previousBalance = 0;
        $previousTxn = DB::table('acc_transactions')
            ->where('client_id', auth()->user()->client_id)
            ->where('description', 'OpeningBalance')
            ->where('head', $head);


        $debit = $previousTxn->sum('debit');
        $credit = $previousTxn->sum('credit');

        return ['p_balance' => $debit];
//        return $previousTxn->get();
    }

    public function setSupplierGroup(Request $request)
    {
        if ($request->isMethod('post')) {
            //dd($request->all());
            $data = $request->all();
            $supplier = new SupplierGroup();
            $supplier->supplier_group = $data['inputName'];
            $supplier->user_id = Auth::id();
            $supplier->client_id = auth()->user()->client_id;
            $supplier->save();
            return redirect('/dashboard/supplier_group')->with('flash_message_success', 'Supplier Group Added Successfully!');
        }
        $supplier_groups = SupplierGroup::query()->orderBy('id', 'DESC')->get();
        return view('admin.pos.suppliers.supplier_group')->with(compact('supplier_groups'));
    }

    public function edit_group(Request $request)
    {
        $id = $request->id;
        $get_data = DB::table('supplier_groups')->where('id', $id)->first();

        $data = array(
            'id' => $get_data->id,
            'name' => $get_data->supplier_group,
        );
        return json_encode($data);
    }

    public function setSupplier(Request $request)
    {


        if ($request->isMethod('post')) {
            //dd($request->all());
            $data = $request->all();
            $supplier = new Supplier;
            $supplier->name = $data['inputName'];
            $supplier->phone = $data['inputPhone'];
            $supplier->email = $data['inputEmail'] ?? '';
            $supplier->address = $data['inputAddress'] ?? '';
            $supplier->area = $data['inputArea'] ?? '';
            $supplier->upazilla = $data['inputUpazilla'] ?? '';
            $supplier->district = $data['inputDistrict'] ?? '';
            $supplier->details = $data['inputDetails'] ?? '';
            $opb = $data['inputOpeningBalance'] ?? 0;
            $opb = $opb;

            $supplier->user = Auth::id();
            $supplier->save();

            $sid = (DB::table('suppliers')->max('id'));

            $head = AccHead::create([
                'cid' => 'sid' . " " . $sid,
                'parent_head' => 'Liabilities',
                'sub_head' => 'Suppliers Payable',
                'head' => $data['inputName'] . " " . $data['inputPhone'],
                // 'user' => Auth::id(),
            ]);


            addOrUpdateOpeningBalance($head->id, $head->head, $opb, 'Cr', `sid {$sid}`);


            return redirect('/dashboard/suppliers')->with('flash_message_success', 'Supplier Added Successfully!');
        }
        $suppliers = Supplier::where('client_id', auth()->user()->client_id)->orderBy('id', 'DESC')->get();
//        $supplier_groups = DB::table('supplier_groups')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get();

        foreach ($suppliers as $index => $row) {
            $get_head = DB::table('acc_heads')->where('cid', "sid " . $row->id)->first();
            $head = $get_head->head ?? '';
            $debit = DB::table('acc_transactions')->where('head', $head)->sum('debit');
            $credit = DB::table('acc_transactions')->where('head', $head)->sum('credit');
            $row->balance = $credit - $debit;
        }
//        dd($suppliers->toArray());

        $mobile_banks = BankInfo::where('type', 'mobile_bank')->where('client_id', auth()->user()->client_id)->get();
        $bank_infos = array();
        foreach ($mobile_banks as $bank) {
            $bank_infos[] = $bank->name;
            $bank_infos[] = $bank->id;
            $bank_infos[] = $bank->account->id ?? '';
            $bank_infos[] = $bank->account->acc_no ?? '';
        }
        $getbanks = BankInfo::where('type', 'general_bank')->where('client_id', auth()->user()->client_id)->get();
        $banks = array();
        foreach ($mobile_banks as $bank) {
            $banks[] = $bank->name;
            $banks[] = $bank->id;
            $banks[] = $bank->account->id ?? '';
            $banks[] = $bank->account->acc_no ?? '';
        }
        return view('admin.pos.suppliers.suppliers')->with(compact('suppliers', 'bank_infos', 'getbanks'));
    }

    public function updateSuppGroup(Request $request)
    {
        $id = $request->id;
        $name = $request->name;

        DB::table('supplier_groups')
            ->where(['id' => $id])->update(['supplier_group' => $name]);

        echo 'Supplier Group Updated Successfully!';
    }

    public function deleteSuppGroup($id)
    {
        $delete = SupplierGroup::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Supplier Group Deleted Successfully!";
        } else {
            $success = true;
            $message = "No Group Found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $get_data = Supplier::where('client_id', auth()->user()->client_id)->where('id', $id)->first();

        $head = AccHead::query()->where('cid', 'sid ' . $get_data->id)->first();

        $opb = AccTransaction::query()->where('type', AccHead::class)
            ->where('type_id', $head->id)
            ->where('description', "Opening Balance")->sum('credit');

        $data = array(
            'id' => $get_data->id,
            'name' => $get_data->name,
            'phone' => $get_data->phone,
            'address' => $get_data->address,
            'email' => $get_data->email,
            'area' => $get_data->area,
            'upazilla' => $get_data->upazilla,
            'district' => $get_data->district,
            'details' => $get_data->details,
            'supplier_group_id' => $get_data->supplier_group,

            'opb' => $opb,
        );

        return json_encode($data);
    }

    public function updateSupp(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $address = $request->address;
        $email = $request->email;
        $area = $request->area;
        $upazilla = $request->upazilla;
        $district = $request->district;
        $details = $request->details;

        $opb = $request->inputOpeningBalance ?? 0;

        $prev_supplier = Supplier::find($id);


        DB::table('suppliers')
            ->where('client_id', auth()->user()->client_id)
            ->where(['id' => $id])
            ->update(['name' => $name, 'phone' => $phone, 'address' => $address, 'email' => $email,
                'area' => $area, 'upazilla' => $upazilla, 'district' => $district, 'details' => $details]);

        $sid = "sid " . $id;
        $head = $name . " " . $phone;

        AccHead::where('client_id', auth()->user()->client_id)
            ->where('cid', $sid)
            ->update([
                'head' => $head,
                'cid' => $sid
            ]);
        $head = AccHead::where('client_id', auth()->user()->client_id)
            ->where('cid', $sid)
            ->where('head', $head)->first();

        $previousHead = $prev_supplier->name . ' ' . $prev_supplier->phone;

        AccTransaction::where('client_id', auth()->user()->client_id)->where('sort_by', $sid)->where('head', $previousHead)->update(['head' => $head->head]);


        addOrUpdateOpeningBalance($head->id, $head->head, $opb, 'Cr', $sid);


        echo 'Supplier Updated!';
    }

    public function deleteSupp($id)
    {
        $supplier = Supplier::find($id);
        $head = $supplier->name . " " . $supplier->phone;

        $acc_count = AccTransaction::where('head', $head)->count();

        if ($acc_count <= 1) {
            $deleteTrans = AccTransaction::where('head', $head)->where('description', "Opening Balance")->delete();
            AccHead::where('head', $head)->delete();
        }


        // $deleteAcc = AccHead::where('head', $head)->delete();
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

    public function suppDetails(Request $request)
    {
        $id = $request->id;
        $get_data = DB::table('suppliers')
            ->where('client_id', auth()->user()->client_id)
            ->select('suppliers.name', 'suppliers.phone', 'suppliers.address')->where('id', $id)->first();
        $get_head = DB::table('acc_heads')
            ->where('client_id', auth()->user()->client_id)
            ->where('cid', "sid " . $id)->first();
        $head = $get_head->head;
        $debit = DB::table('acc_transactions')
            ->where('client_id', auth()->user()->client_id)
            ->where('head', $head)->sum('debit');
        $credit = DB::table('acc_transactions')
            ->where('client_id', auth()->user()->client_id)
            ->where('head', $head)->sum('credit');
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

    public function addPayment(Request $request)
    {

        $fieldValues = json_decode($request['fieldValues'], true);

        $supp_id = $fieldValues['supp_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $amount = $fieldValues['amount'];
        //dd(json_encode($fieldValues));
        $paytype = $fieldValues['paytype'];
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();
        $cardtype = $fieldValues['cardtype'];
        //$cardbank = $fieldValues['cardbank'];
        //$card_bank_id = $fieldValues['card_bank_id'];
        $card_bank_account = $fieldValues['card_bank_account'];
        $card_bank_name = $fieldValues['card_bank_name'];
        //$card_bank_acc_id = $fieldValues['card_bank_acc_id'];

        $clients_bank = $fieldValues['clientsbank'];
        $clientsbacc = $fieldValues['clientsbacc'];
        $checkno = $fieldValues['checkno'];
        $checktype = $fieldValues['checktype'];
        $checkdate = $fieldValues['checkdate'];
        //$shopbank = $fieldValues['shopbank'];
        //$bank_id = $fieldValues['bank_id'];
        $shops_bank_account = $fieldValues['checksbacc'];
        $shops_bank_name = $fieldValues['shops_bank_name'];
        //$account_id = $fieldValues['account_id'];

        $btcbank = $fieldValues['btcbank'];
        //$btcbankacc = $fieldValues['btcbankacc'];
        $bankaacno = $fieldValues['btcbankacc'];
        //$mobile_bank = $fieldValues['bt_shops_bank'];
        $btbank_account = $fieldValues['bt_shops_bank_acc'];
        $mobile_bank = $fieldValues['mobile_bank'];
        $mclients_bank = $fieldValues['mclients_bank'];
        $maccount_number = $fieldValues['mclientsaccount_number'];
        $mtranxid = $fieldValues['mtranxid'];;
        $tranxid = $fieldValues['tranxid'];

        $method = '';
        $desc = '';
        if ($paytype == 'cash') {
            $desc = "Cash Payment";
            $method = "Cash";
        }
        if ($paytype == 'card') {
            $desc = "Card Payment";
            if ($cardtype == 'visa') {
                $method = "Visa Card";
            } else if ($cardtype == 'master') {
                $method = "Master Card";
            } else if ($cardtype == 'credit') {
                $method = "Credit Card";
            } else if ($cardtype == 'debit') {
                $method = "Debit Card";
            }
        }
        if ($paytype == 'cheque') {
            $desc = "Checque Payment";
            if ($checktype == 'pay_cash') {
                $method = "Cash";
            } else {
                $method = "Account Payee";
            }
        }
        if ($paytype == 'bank_transfer') {
            $desc = "Bank Transfer";
            $method = "Bank Transfer";
        }
        if ($paytype == 'mobile_banking') {
            $desc = "Mobile Banking";
            $method = "Mobile Banking";
        }

        $maxid = (DB::table('payment_invoice')->max('id') + 1);
        $invoice = "SPAYM-" . $maxid;

        PaymentInvoice::create([

            'invoice_no' => $invoice,
            'sid' => $supp_id,
            'amount' => $amount,
            'method' => $method,
            'description' => $desc,
            'remarks' => $remarks,
            'date' => $date,
            'user' => $user,
            'user_type' => "supplier",
        ]);

        if ($paytype == 'cash') {

            $vno = time();

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'user_id' => Auth::id(),
                'client_id' => auth()->user()->client_id,
                'sort_by' => "sid" . " " . $supp_id,
                'vno' => $vno,
                'head' => $head,
                'description' => $description,
                'note' => "Paid in Cash",
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);

            $head = "Cash In Hand";
            $description = "Cash Payment Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'note' => "Cash Payment",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);
        }

        ///// Card transaction

        if ($paytype == 'card') {

            // $str_arr = explode (",", $card_bank_account);
            // $str_arr2 = explode (",", $card_bank_name);

            BankTransaction::create([

                'seller_bank_id' => $card_bank_account,
                'seller_bank_acc_id' => $card_bank_account,
                'clients_bank' => $method,
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

            $vno = time();

            $head = $cust_name . " " . $cust_phone;
            $description = "Pay Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);

            $getname = DB::table("bank_info")->select('name')
                ->where('id', $card_bank_account)->first();
            $getAcc = DB::table("bank_acc")->select('acc_no')
                ->where('bank_id', $card_bank_account)->first();

            $head = $getname->name . " " . $getAcc->acc_no;
            $description = "Card Payment Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);
        }

        ///// Check transaction

        if ($paytype == 'cheque') {
            // $str_arr = explode (",", $shops_bank_account);
            BankTransaction::create([

                'seller_bank_id' => $shops_bank_account,
                'seller_bank_acc_id' => $shops_bank_account,
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

        ///// Bank Transfer transaction

        if ($paytype == 'bank_transfer') {
            // $str_arr = explode (",", $mobile_bank_account);
            // $str_arr2 = explode (",", $mobile_bank_name);
            BankTransaction::create([

                'seller_bank_id' => $btbank_account,
                'seller_bank_acc_id' => $btbank_account,
                'clients_bank' => $btcbank,
                'clients_bank_acc' => $bankaacno,
                'date' => $date,
                'sid' => $supp_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'bank',
                'status' => 'paid',
                'tranxid' => $tranxid,
                'remarks' => $remarks,
                'user' => $user,

            ]);

            /////Insert into Accounts For Bank Transaction

            $vno = time();

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);

            $getname = DB::table("bank_info")->select('name')
                ->where('id', $btbank_account)->first();
            $getAcc = DB::table("bank_acc")->select('acc_no')
                ->where('bank_id', $btbank_account)->first();

            $head = $getname->name . " " . $getAcc->acc_no;
            $description = "Bank Transfer Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);
        }

        ///// Mobile Banking transaction

        if ($paytype == 'mobile_banking') {
            // $str_arr = explode (",", $mobile_bank_account);
            // $str_arr2 = explode (",", $mobile_bank_name);
            BankTransaction::create([

                'seller_bank_id' => $mobile_bank,
                'seller_bank_acc_id' => $mobile_bank,
                'clients_bank' => $mclients_bank,
                'clients_bank_acc' => $maccount_number,
                'date' => $date,
                'sid' => $supp_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'mobile',
                'status' => 'paid',
                'tranxid' => $mtranxid,
                'remarks' => $remarks,
                'user' => $user,

            ]);

            /////Insert into Accounts For Mobile Transaction

            $vno = time();

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);

            $getname = DB::table("bank_info")->select('name')
                ->where('id', $mobile_bank)->first();
            $getAcc = DB::table("bank_acc")->select('acc_no')
                ->where('bank_id', $mobile_bank)->first();

            $head = $getname->name . " " . $getAcc->acc_no;
            $description = "Mobile Banking Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid" . " " . $supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,

            ]);
        }

        return $invoice;

    }

    public function getSupplier($id)
    {

        $supplier = Supplier::where(['id' => $id])->first();

        $get_head = DB::table('acc_heads')->where('cid', "sid " . $id)->first();
        $group = "Null";
        $head = $get_head->head;
        $ledgers = AccTransaction::where(['head' => $head])->get();

        $total_purchase = DB::table('purchase_primary')
            ->where('client_id', auth()->user()->client_id)
            ->where('sid', $id)->sum('amount');

        $purchase = DB::table('purchase_primary')
            ->where('client_id', auth()->user()->client_id)
            ->where('sid', $id)->get();

        $fromPurchase = DB::table('purchase_primary')
            ->where('client_id', auth()->user()->client_id)
            ->where('sid', $id)->sum('payment');

        $fromPayment = DB::table('payment_invoice')
            ->where('client_id', auth()->user()->client_id)
            ->where('sid', $id)->sum('amount');
//        $fromPayment = 0;

        $sumDiscount = DB::table('purchase_primary')
            ->where('client_id', auth()->user()->client_id)
            ->where("sid", $id)->sum('discount');
        $total_purchase_paid = $fromPurchase + $fromPayment;
        $purchase_due = $total_purchase - $total_purchase_paid - $sumDiscount;

        return view('admin.pos.suppliers.supplier_details')
            ->with(compact('supplier', 'get_head', 'total_purchase', 'total_purchase_paid',
                'purchase_due', 'sumDiscount', 'fromPayment', 'ledgers', 'purchase', 'group'));


    }


    public function getPreviousBalance(Request $request)
    {
        $start_date = $request->from_date;
        $end_date = $request->to_date;
        $sid = $request->sid;
        $id = explode(' ', $sid)[1];
        $supplier = Supplier::find($id);
        $head = $supplier->name . ' ' . $supplier->phone;


        $previousBalance = 0;
        $previousTxn = DB::table('acc_transactions')
            ->where('client_id', auth()->user()->client_id)
            ->where('head', $head)
            ->where('description', 'OpeningBalance');

        $credit = $previousTxn->sum('credit');

        return ['p_balance' => $credit];
    }

    public function getPreviousBalanceBank(Request $request)
    {
        $start_date = $request->from_date;
        $end_date = $request->to_date;
        $sid = $request->sid;
//        return $sid;
        $id = explode(' ', $sid)[1];
        $supplier = Supplier::find($id);
//        return $supplier;
        $head = $supplier->name . ' ' . $supplier->phone;


        $previousBalance = 0;
        $previousTxn = DB::table('acc_transactions')
            ->where('client_id', auth()->user()->client_id)
            ->where('head', $head)->whereDate('date', '<', $start_date);
        $debit = $previousTxn->sum('debit');
        $credit = $previousTxn->sum('credit');

        $previousBalance = $debit - $credit;
        return ['p_balance' => $previousBalance];
//        return $previousTxn->get();
    }

    public function filter_data(Request $request)
    {
        if (request()->ajax()) {
            $previous_balance = 0;
            $sid = $request->sid;
            $sid = $request->sid;
            $supp = Supplier::find($request->supplier_id);
            $head = $supp->name . " " . $supp->phone;

            if (!empty($request->from_date)) {
                $data = DB::table('acc_transactions')
                    ->where('client_id', auth()->user()->client_id)
                    ->where('head', $head)
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get();


                $previousTxn = DB::table('acc_transactions')
                    ->where('client_id', auth()->user()->client_id)
                    ->where('head', $head)
                    ->whereDate('date', '<', $request->from_date);
                $previous_balance = $previousTxn->sum('credit') - $previousTxn->sum('debit');
            }
            else {
                $data = DB::table('acc_transactions')
                    ->where('client_id', auth()->user()->client_id)
                    ->where('sort_by', $sid)
                    ->get();
            }


//            dd($data);

            if (count($data)) {
                $prependTxn = clone $data[0];
            } else {
                $prependTxn = clone AccTransaction::query()->first();

            }

            $prependTxn->description = "Previous Balance";
            $prependTxn->vno = "-";
            $prependTxn->balance = $previous_balance;
            $prependTxn->debit = 0;
            $prependTxn->credit = $previous_balance;
            $data = $data->prepend($prependTxn);


            $balance = 0;

            foreach ($data as $index => $d) {
                if ($d->debit > 0) {
                    $balance = ($balance + $d->debit);
                } else {
                    $balance = ($balance - $d->credit);
                }

                $d->balance = ($balance);
            }
            return datatables()->of($data)->make(true);
        }
    }

    public function purchaseinvoice($id)
    {
        $invoiceno = $id;
        $get_supplier = DB::table('purchase_primary')
            ->where('pur_inv', $id)->first();
        $sid = $get_supplier->sid;
        $supp_details = Supplier::where(['id' => $sid])->get();

        $details = DB::table('purchase_details')
            ->select('products.product_name as name', 'products.product_img as image',
                'purchase_details.qnt as qnt', 'purchase_details.price as price', 'purchase_details.total as total', 'purchase_details.vat')
            ->join('products', 'purchase_details.pid', 'products.id', 'products.name')
            ->where('purchase_details.pur_inv', $invoiceno)->get();

        $settings = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.suppliers.payinvoice')->with(compact('supp_details', 'get_supplier', 'details', 'settings'));
    }
}
