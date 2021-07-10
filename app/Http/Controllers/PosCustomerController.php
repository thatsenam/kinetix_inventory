<?php

namespace App\Http\Controllers;

use App\AccHead;
use App\AccTransaction;
use App\BankInfo;
use App\BankTransaction;
use App\Company;
use App\Customer;
use App\Filter;
use App\GeneralSetting;
use App\Manufacturer;
use App\PaymentInvoice;
use App\Product;
use App\ProductImage;
use App\Seller;
use App\Supplier;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

// use Response;

class PosCustomerController extends Controller
{

    public function get_customer(Request $req)
    {

        $s_text = $req['s_text'];

        $customer = DB::table('customers')
            ->where('client_id', auth()->user()->client_id)
            ->where('phone', 'like', '%' . $s_text . '%')
            ->orWhere('name', 'like', '%' . $s_text . '%')
            ->limit(9)->get(); ?>

        <ul class='customer-list sugg-list'>

            <?php $i = 1;

            foreach ($customer as $row) {

                $id = $row->id;
                $name = $row->name;
                $phone = $row->phone;
                $address = $row->address ?? '';

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>'
                    onclick='selectCustomer("<?php echo $id; ?>", "<?php echo $phone; ?>", "<?php echo $name; ?>", "<?php echo $address; ?>");'
                    data-id='<?php echo $id; ?>' data-phone='<?php echo $phone; ?>' data-name='<?php echo $name; ?>'
                    data-address='<?php echo $address; ?>'><?php echo $phone; ?> | <?php echo $name; ?></li>

            <?php } ?>

        </ul>

        <?php

    }

    public function setCustomer(Request $request)
    {

        if ($request->isMethod('post')) {

            $this->validate($request, [
                'inputPhone' => 'unique:customers,phone|required',
            ]);

            $data = $request->all();
            $customer = new Customer;
            $customer->name = $data['inputName'];
            $customer->phone = $data['inputPhone'];
            $customer->address = $data['inputAddress'];
            $opb = $data['inputOpeningBalance'] ?? 0;
            $customer->email = $data['inputEmail'] ?? '';
            $customer->date = $data['inputDate'];
            $customer->user = Auth::id();
            $customer->save();

            $cust_id = $customer->id;

            $head = AccHead::create([
                'cid' => "cid " . $cust_id,
                'parent_head' => 'Asset',
                'sub_head' => 'Customers Receivable',
                'head' => $data['inputName'] . " " . $data['inputPhone'],
            ]);


            addOrUpdateOpeningBalance($head->id, $head->head, $opb, 'Dr', "cid " . $cust_id);


            return redirect('/dashboard/customers')->with('flash_message_success', 'Customer Added Successfully!');
        }
        $customers = DB::table('customers')->where('client_id', auth()->user()->client_id)->get();

        foreach ($customers as $index => $row) {
            $get_head = DB::table('acc_heads')->where('cid', "cid " . $row->id)->first();
            $head = $get_head->head ?? '';
            $debit = DB::table('acc_transactions')->where('head', $head)->sum('debit');
            $credit = DB::table('acc_transactions')->where('head', $head)->sum('credit');
            $row->balance = $debit - $credit;
        }

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
        foreach ($banks as $bank) {
            $banks[] = $bank->name;
            $banks[] = $bank->id;
            $banks[] = $bank->account->id ?? '';
            $banks[] = $bank->account->acc_no ?? '';
        }
        return view('admin.pos.customer.customers')->with(compact('customers', 'bank_infos', 'getbanks'));
    }

    public function customers_phone(Request $request)
    {
        $customer_phone = Customer::all()->pluck('phone');
        return $customer_phone;
    }

    public function supplier_phone(Request $request)
    {
        $supplier = Supplier::all()->pluck('phone');
        return $supplier;
    }


    public function custDetals(Request $request)
    {
        $id = $request->id;
        $get_data = DB::table('customers')
            ->where('client_id', auth()->user()->client_id)
            ->select('customers.name', 'customers.phone', 'customers.address')->where('id', $id)->first();
        $get_head = DB::table('acc_heads')->where('cid', "cid " . $id)->first();
        $head = $get_head->head;
        $debit = DB::table('acc_transactions')->where('head', $head)->sum('debit');
        $credit = DB::table('acc_transactions')->where('head', $head)->sum('credit');
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

    public function edit(Request $request)
    {
        $id = $request->id;
        $get_data = DB::table('customers')->select('customers.id', 'customers.name', 'customers.phone', 'customers.address', 'customers.email', 'customers.date')->where('id', $id)->first();

        $head = AccHead::query()->where('cid', 'cid ' . $get_data->id)->first();
        try {
            $opb = AccTransaction::query()
                ->where('type', AccHead::class)
                ->where('type_id', $head->id)
                ->where('description', "Opening Balance")->sum('debit');
        } catch (\Exception $exception) {
            $opb = 0;
        }

        $data = array(
            'name' => $get_data->name,
            'phone' => $get_data->phone,
            'address' => $get_data->address,
            'email' => $get_data->email,
            'date' => $get_data->date,
            'id' => $get_data->id,
            'opb' => $opb,

        );
        return json_encode($data);
    }

    public function updateCust(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $address = $request->address;
        $email = $request->email ?? '';
        $opb = $request->inputOpeningBalance ?? 0;

        $date = $request->date ?? '';

        $prev_customer = Customer::find($id);

        DB::table('customers')->where(['id' => $id])->update(['name' => $name, 'phone' => $phone, 'address' => $address, 'email' => $email, 'date' => $date]);

        $cid = "cid " . $id;
        $head = $name . " " . $phone;

        AccHead::where('client_id', auth()->user()->client_id)
            ->where('cid', $cid)
            ->update([
                'head' => $head,
            ]);

        $prev_cust_name = $prev_customer->name;
        $prev_cust_phone = $prev_customer->phone;
        $cust_head = $prev_cust_name . " " . $prev_cust_phone;
        $head = AccHead::query()->where('client_id', auth()->user()->client_id)->firstWhere('cid', $cid);

        AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('type', AccHead::class)
            ->where('type_id', $head->id)
            ->where('description', "OpeningBalance")
            ->update([
                'head' => $head->head,
                'sort_by' => $cid
            ]);

        addOrUpdateOpeningBalance($head->id, $head->head, $opb, 'Dr', $cid);

        echo 'Customer Updated Successfully!';
    }

    public function deleteCust($id)
    {
        $delete = Customer::where('id', $id)->delete();
        if ($delete == 1) {
            $success = true;
            $message = "Customer deleted successfully!";
        } else {
            $success = true;
            $message = "Customer not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function UpCust($id)
    {
        $update = DB::table('customers')->where(['id' => $id])->update(['status' => "0"]);
        if ($update == 1) {
            $success = true;
            $message = "Customer Status Updated!";
        } else {
            $success = true;
            $message = "Customer not found";
        }
        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function getCustomer($id)
    {

        $customer = Customer::where(['id' => $id])->first();
        $get_head = DB::table('acc_heads')->where('cid', "cid " . $id)->first();
        $head = $get_head->head ?? "";


        $ledgers = AccTransaction::where(['head' => $head])->get();
        $total_sale = DB::table('sales_invoice')->where('cid', $id)->sum('amount');
        $sales = DB::table('sales_invoice')->where('cid', $id)->get();
        $fromSales = DB::table('sales_invoice')->where('cid', $id)->sum('payment');
        $fromPayment = DB::table('payment_invoice')->where('cid', $id)->sum('amount');
        $total_sale_paid = $fromSales + $fromPayment;
        $sale_due = $total_sale - $total_sale_paid;
        $total_return = DB::table('sales_return')->where('cid', $id)->sum('tprice');
        $cash_return = DB::table('sales_return')->where('cid', $id)->sum('cash_return');
        $return_due = $total_return - $cash_return;

        return view('admin.pos.customer.customer_details')->with(compact('customer', 'get_head', 'total_sale', 'total_sale_paid', 'sale_due', 'total_return', 'cash_return', 'return_due', 'ledgers', 'sales'));


    }

    public function sales_filter(Request $request)
    {
        if (request()->ajax()) {
            $head = $request->custhead;
            if (!empty($request->from_date)) {
                $data = DB::table('acc_transactions')->where('head', $head)
                    ->whereBetween('date', array($request->from_date, $request->to_date))->get();
            } else {
                $data = DB::table('acc_transactions')->where('head', $request->custhead)->get();
            }
            return datatables()->of($data)->make(true);
        }
    }

    public function filter_data(Request $request)
    {
        $previous_balance = 0;

        if (request()->ajax()) {
            $head = $request->custhead;
            if (!empty($request->from_date)) {
                $data = DB::table('acc_transactions')
                    ->where('head', $request->custhead)
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get();
                $previousTxn = DB::table('acc_transactions')
                    ->where('client_id', auth()->user()->client_id)
                    ->where('head', $head)
                    ->whereDate('date', '<', $request->from_date);
                $previous_balance = $previousTxn->sum('credit') - $previousTxn->sum('debit');
            } else {
                $data = DB::table('acc_transactions')
                    ->where('head', $request->custhead)
                    ->get();
            }


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

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $banks = DB::table('bank_info')->where('name', 'LIKE', '%' . $request->text . '%')->get();

            if (count($banks)) {
                foreach ($banks as $bank) {
                    $output .= '<button type="button" id="' . $bank->id . '" class="wow btn btn-sm btn-default mr-2">' . $bank->name . '</button>';
                }
                return response($output);
            } else {
                return response('No Bank Found!');
            }
        }
    }

    public function search_bank_acc(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $banks = DB::table('bank_acc')->where('bank_id', $request->text)->get();
            if (count($banks)) {
                foreach ($banks as $bank) {
                    $output .= '<button type="button" id="' . $bank->bank_id . '" class="bank_acc btn btn-sm btn-default mr-2">' . $bank->acc_name . '</button>';
                }
                return response($output);
            } else {
                return response('No Bank Account Found!');
            }
        }
    }

    public function addPayment(Request $request)
    {

        $fieldValues = json_decode($request['fieldValues'], true);

        $cust_id = $fieldValues['cust_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $amount = $fieldValues['amount'];
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
        $tranxid = $fieldValues['tranxid'];
        $mtranxid = $fieldValues['mtranxid'];

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
                $method = "DEbit Card";
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
        $invoice = "CPAYMEMO-" . $maxid;

        PaymentInvoice::create([
            'invoice_no' => $invoice,
            'cid' => $cust_id,
            'amount' => $amount,
            'method' => $method,
            'description' => $desc,
            'remarks' => $remarks,
            'date' => $date,
            'user' => $user,
            'user_type' => "customer",
        ]);

        $vno = time();

        if ($paytype == 'cash') {

            $vno = time();

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'note' => "Paid In Cash",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);

            // if($request->hasFile('inputImage')){
            //     $file = $request->file('inputImage');
            //     $basename = basename($file);
            //     $img_name = $basename.time().$file->getClientOriginalExtension();
            //     $file->move('images/documents/', $img_name);
            //     $product->product_img = $img_name;
            // }

            $head = "Cash In Hand";
            $description = "Pain in Cash Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'note' => "Recieved in Cash",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);
        }

        ///// Card transaction

        if ($paytype == 'card') {

            BankTransaction::create([
                'seller_bank_id' => $card_bank_account,
                'seller_bank_acc_id' => $card_bank_account,
                'clients_bank' => $method,
                'clients_bank_acc' => "Payment by Card",
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'withdraw' => $amount,
                'type' => 'card',
                'status' => 'paid',
                'remarks' => $remarks,
                'user' => $user,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),
            ]);

            /////Insert into Accounts For Card Transaction

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);

            $getname = DB::table("bank_info")->select('name')
                ->where('id', $card_bank_account)->first();
            $getAcc = DB::table("bank_acc")->select('acc_no')
                ->where('bank_id', $card_bank_account)->first();

            $head = $getname->name . " " . $getAcc->acc_no;
            $description = "Card Payment Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);
        }

        ///// Check transaction

        if ($paytype == 'cheque') {
            BankTransaction::create([
                'seller_bank_id' => $shops_bank_account,
                'seller_bank_acc_id' => $shops_bank_account,
                'clients_bank' => $clients_bank,
                'clients_bank_acc' => $clientsbacc,
                'check_no' => $checkno,
                'check_date' => $checkdate,
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'withdraw' => $amount,
                'type' => 'check',
                'status' => 'pending',
                'remarks' => $remarks,
                'user' => $user,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),
            ]);

        }

        ///// Mobile/Bank Transfer transaction

        if ($paytype == 'bank_transfer') {
            BankTransaction::create([
                'seller_bank_id' => $btbank_account,
                'seller_bank_acc_id' => $btbank_account,
                'clients_bank' => $btcbank,
                'clients_bank_acc' => $bankaacno,
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'withdraw' => $amount,
                'type' => 'bank',
                'status' => 'paid',
                'tranxid' => $tranxid,
                'remarks' => $remarks,
                'user' => $user,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);

            /////Insert into Accounts For Mobile Transaction

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);

            $getname = DB::table("bank_info")->select('name')
                ->where('id', $btbank_account)->first();
            $getAcc = DB::table("bank_acc")->select('acc_no')
                ->where('bank_id', $btbank_account)->first();

            $head = $getname->name . " " . $getAcc->acc_no;
            $description = "Bank Transfer Invoice " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);
        }

        //Mobile Banking

        if ($paytype == 'mobile_banking') {
            BankTransaction::create([

                'seller_bank_id' => $mobile_bank,
                'seller_bank_acc_id' => $mobile_bank,
                'clients_bank' => $mclients_bank,
                'clients_bank_acc' => $maccount_number,
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'withdraw' => $amount,
                'type' => 'mobile',
                'status' => 'paid',
                'tranxid' => $mtranxid,
                'remarks' => $remarks,
                'user' => $user,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);

            /////Insert into Accounts For Mobile Transaction

            $head = $cust_name . " " . $cust_phone;
            $description = "Payment Invoice " . $invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);

            $getname = DB::table("bank_info")->select('name')
                ->where('id', $mobile_bank)->first();
            $getAcc = DB::table("bank_acc")->select('acc_no')
                ->where('bank_id', $mobile_bank)->first();

            $head = $getname->name . " " . $getAcc->acc_no;
            $description = "Mobile Banking Memo " . $invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'client_id' => auth()->user()->client_id,
                'user_id' => Auth::id(),

            ]);
        }

        // $customer = Customer::find($cust_id);

        // $this->sendSms($cust_name, $cust_phone, $amount, optional($customer)->due ?? 0, 0);
        return $invoice;

    }

    public function payinvoice($id)
    {
        $get_customer = DB::table('payment_invoice')->where('invoice_no', $id)->first();
        if (!empty($get_customer->cid)) {
            $custid = $get_customer->cid;
            $cust_details = Customer::where(['id' => $custid])->get();
        }
        if (!empty($get_customer->sid)) {
            $custid = $get_customer->sid;
            $cust_details = Supplier::where(['id' => $custid])->get();
        }

        return view('admin.pos.customer.payinvoice')->with(compact('cust_details', 'get_customer'));
    }

    public function saleinvoice($id)
    {
        $invoiceno = $id;
        $get_customer = DB::table('sales_invoice')->where('invoice_no', $id)->first();
        $custid = $get_customer->cid;
        $cust_details = Customer::where(['id' => $custid])->get();

        $details = DB::table('sales_invoice_details')
            ->select('products.product_name as name', 'products.product_img as image', 'sales_invoice_details.qnt as qnt',
                'sales_invoice_details.price as price', 'sales_invoice_details.vat')
            ->join('products', 'sales_invoice_details.pid', 'products.id')
            ->where('sales_invoice_details.invoice_no', $invoiceno)->get();

        $total = 0;
        foreach ($details as $row) {
            $qnt = $row->qnt;
            $total += $qnt;
        }

        $settings = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.customer.saleinvoice')->with(compact('cust_details', 'get_customer', 'details', 'settings', 'total'));
    }

    public function saleinvoicemain($id)
    {
        $invoiceno = $id;
        $get_customer = DB::table('sales_invoice')->where('invoice_no', $id)->first();
        $custid = $get_customer->cid;
        $cust_details = Customer::where(['id' => $custid])->get();

        $details = DB::table('sales_invoice_details')
            ->select('products.product_name as name', 'products.product_img as image',
                'sales_invoice_details.qnt as qnt', 'sales_invoice_details.box',
                'sales_invoice_details.price as price', 'sales_invoice_details.vat')
            ->join('products', 'sales_invoice_details.pid', 'products.id')
            ->where('sales_invoice_details.invoice_no', $invoiceno)->get();

        $settings = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.customer.saleinvoice_old')->with(compact('cust_details', 'get_customer', 'details', 'settings'));
    }


    public function customers_due_report()
    {
        $customers = Customer::where('client_id', auth()->user()->client_id)->get();

        $customers_due = [];

        foreach ($customers as $customer) {
            $debit = 0;
            $credit = 0;
            $cid = 'cid ' . $customer->id;
            $head = $customer->name . ' ' . $customer->phone;
            $debit = AccTransaction::where('client_id', auth()->user()->client_id)
                ->where('sort_by', $cid)
                ->where('head', $head)
                ->sum('debit');
            $credit = AccTransaction::where('client_id', auth()->user()->client_id)
                ->where('sort_by', $cid)
                ->where('head', $head)
                ->sum('credit');
            $due = $debit - $credit;
            $customers_due [] = [
                'name' => $customer->name,
                'phone' => $customer->phone,
                'due' => $due,
            ];
        }

        return view('admin.pos.customer.customers-due-report', compact('customers_due'));
    }

    public function get_customer_due($customer)
    {
        $customer = Customer::where('client_id', auth()->user()->client_id)->find($customer);

        $cid = 'cid ' . $customer->id;
        $head = $customer->name . ' ' . $customer->phone;
        $debit = AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('sort_by', $cid)
            ->where('head', $head)
            ->sum('debit');
        $credit = AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('sort_by', $cid)
            ->where('head', $head)
            ->sum('credit');
        $due = $debit - $credit;

        return $due;
    }

    public function customers_due_collection_report()
    {
        return view('admin.pos.customer.customers-due-collection-report');
    }

    public function get_customers_due_collection_report(Request $req)
    {
        $customers = Customer::where('client_id', auth()->user()->client_id)->get();

        $collections = [];

        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if (!$stdate) {
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if (!$enddate) {
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        foreach ($customers as $customer) {
            $cid = 'cid ' . $customer->id;
            $head = $customer->name . ' ' . $customer->phone;
            $name = $customer->name;
            $phone = $customer->phone;

            $collection = AccTransaction::where('client_id', auth()->user()->client_id)
                ->where('sort_by', $cid)
                ->where('head', $head)
                ->where('credit', '>', 0)
                ->whereDate('date', '>=', $stdate)
                ->whereDate('date', '<=', $enddate)
                ->get();

            foreach ($collection as $collect) {
                $collections [] = [
                    'date' => $collect->date,
                    'name' => $name,
                    'phone' => $phone,
                    'due' => $collect->credit,
                ];
            }
        }

        // dd($collections);

        return DataTables()->of($collections)->make(true);
    }

    public function customer_ledger()
    {
        $customers = DB::table('customers')->where('client_id', auth()->user()->client_id)->get();
        $setting = DB::table('general_settings')->where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.customer.customer-ledger', compact('customers', 'setting'));
    }

    public function get_customer_ledger(Request $req)
    {

        $stdate = $req['stdate'];
        $enddate = $req['enddate'];

        $customer = Customer::find($req['customer_id']);

        $cid = 'cid ' . $customer->id;
        $head = $customer->name . ' ' . $customer->phone;

        $previousBalance = AccTransaction::where('client_id', auth()->user()->client_id)
                ->where('head', $head)
                ->whereDate('date', '<', $stdate)
                ->sum('debit') -
            AccTransaction::where('client_id', auth()->user()->client_id)
                ->where('head', $head)
                ->whereDate('date', '<', $stdate)
                ->sum('credit');

        $accounts = AccTransaction::where('client_id', auth()->user()->client_id)
            ->where('head', $head)
            ->whereDate('date', '>=', $stdate)
            ->whereDate('date', '<=', $enddate)
            ->get();

        $trow = "";
        $debit = 0;
        $credit = 0;

        $Balance = 0;

        $trow = "<tr><th>Previous Balance</th><th colspan='4'></th><th>" . $previousBalance . "</th></tr>";

        $trow .= "<tr>
                    <th>Date</th>
                    <th>Voucher No</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>";

        foreach ($accounts as $row) {
            $Balance = $Balance + $row->debit - $row->credit;

            $date = date('d-M-Y', strtotime($row->date));

            $trow .= "<tr><td>" . $date . "</td><td>" . $row->vno . "</td><td>" . $row->description . "</td><td>" . $row->debit . "</td><td>" . $row->credit . "</td><td>" . $Balance . "</td></tr>";
        }

        return $trow;
    }

}
