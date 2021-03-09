<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use App\Sales;
use App\Filter;
use App\Seller;
use App\AccHead;
use App\Company;
use App\Product;
use App\Category;
use App\Customer;
use App\Manufacturer;
use App\ProductImage;
use App\SalesInvoice;
use App\AccTransaction;
use App\PaymentInvoice;
use App\BankTransaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\CustomerDueCollection;
use App\GeneralSetting;
use Illuminate\Support\Facades\DB;
// use Response;

class PosCustomerController extends Controller
{

    public function get_customer(Request $req){

        $s_text = $req['s_text'];

        $customer = DB::table('customers')
            ->where('client_id',auth()->user()->client_id)
            ->where('phone', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='customer-list sugg-list'>

        <?php $i = 1;

        foreach($customer as $row){

            $id = $row->id;
            $name = $row->name;
            $phone = $row->phone;
            $address = $row->address ?? '';

            $i = $i + 1; ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectCustomer("<?php echo $id; ?>", "<?php echo $phone; ?>", "<?php echo $name; ?>", "<?php echo $address; ?>");' data-id='<?php echo $id; ?>' data-phone='<?php echo $phone; ?>' data-name='<?php echo $name; ?>' data-address='<?php echo $address; ?>'><?php echo $phone; ?> | <?php echo $name; ?></li>

        <?php } ?>

        </ul>

        <?php

    }

    public function setCustomer(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            $customer = new Customer;
            $customer->name = $data['inputName'];
            $customer->phone = $data['inputPhone'];
            $customer->address = $data['inputAddress'];
            $customer->user = Auth::id();
            $customer->save();

            $cust_id = $customer->id;

            $data = AccHead::create([
                'cid' => "cid ".$cust_id,
                'parent_head' => 'Asset',
                'sub_head' => 'Customers Receivable',
                'head' => $data['inputName']." ".$data['inputPhone'],
            ]);

            return redirect('/dashboard/customers')->with('flash_message_success', 'Customer Created Successfully!');
        }
        $customers = DB::table('customers')
            ->where('client_id',auth()->user()->client_id)
            ->orderBy('id', 'DESC')->get();
        return view('admin.pos.customer.customers')->with(compact('customers'));
    }

    public function custDetals(Request $request){
        $id = $request->id;
        $get_data = DB::table('customers')
            ->where('client_id',auth()->user()->client_id)
            ->select('customers.name','customers.phone','customers.address')->where('id', $id)->first();
        $get_head = DB::table('acc_heads')->where('cid',"cid ".$id)->first();
        $head = $get_head->head;
        $debit = DB::table('acc_transactions')->where('client_id',auth()->user()->client_id)
        ->where('head',$head)->sum('debit');
        $credit = DB::table('acc_transactions')->where('client_id',auth()->user()->client_id)
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

    public function edit(Request $request){
        $id = $request->id;
        $get_data = DB::table('customers')->select('customers.name','customers.phone','customers.address')->where('id', $id)->first();

        $data = array(
            'name' => $get_data->name,
            'phone' => $get_data->phone,
            'address' => $get_data->address
        );
        return json_encode($data);
    }

    public function updateCust(Request $request){
        $id = $request->id;
        $name = $request->name;
        $phone = $request->phone;
        $address = $request->address;

        $prev_customer = Customer::find($id);

        DB::table('customers')->where(['id'=>$id])->update(['name'=>$name,'phone'=>$phone,'address'=>$address]);

        $cid = "cid ".$id;
        $head = $name." ".$phone;
        
        AccHead::where('client_id', auth()->user()->client_id)
                ->where('cid', $cid)->update([
                    'head' => $head,
                ]);

        $prev_cust_name = $prev_customer->name;
        $prev_cust_phone = $prev_customer->phone;
        $cust_head = $prev_cust_name." ".$prev_cust_phone;
        
        AccTransaction::where('client_id', auth()->user()->client_id)
                ->where('sort_by', $cid)
                ->where('head', $cust_head)
                ->update([
                    'head' => $head,
                ]);

        echo 'Customer Data Updated Successfully!';
    }

    public function deleteCust($id){
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

    public function UpCust($id){
        $update = DB::table('customers')->where(['id'=>$id])->update(['status'=>"0"]);
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

    public function getCustomer($id){

        $customer = Customer::where(['id'=>$id])->first();
        $get_head = DB::table('acc_heads')->where('cid',"cid ".$id)->first();
        $head = $get_head->head;
        $ledgers = AccTransaction::where(['head'=>$head])->get();
        $total_sale = DB::table('sales_invoice')->where('cid',$id)->sum('amount');
        $sales = DB::table('sales_invoice')->where('cid',$id)->get();
        $fromSales = DB::table('sales_invoice')->where('cid',$id)->sum('payment');
        $fromPayment = DB::table('payment_invoice')->where('cid',$id)->sum('amount');
        $total_sale_paid = $fromSales + $fromPayment;
        $sale_due = $total_sale - $total_sale_paid;
        $total_return = DB::table('sales_return')->where('cid',$id)->sum('tprice');
        $cash_return = DB::table('sales_return')->where('cid',$id)->sum('cash_return');
        $return_due = $total_return - $cash_return;
        $setting = DB::table('general_settings')->where('client_id', auth()->user()->client_id)->first();
        return view('admin.pos.customer.customer_details')->with(compact('setting', 'customer','get_head','total_sale','total_sale_paid','sale_due','total_return','cash_return','return_due','ledgers','sales'));

        // $get_data = DB::table('acc_transactions')->get();

        //         $data = array();

        //     foreach($get_data as $row){
        //         $data['date'] = $row->date;
        //         // $data['id'] = $row->id;
        //         $data['vno'] = $row->vno;
        //         // $data['head'] = $row->head;
        //         $data['description'] = $row->description;
        //         $data['debit'] = $row->debit;
        //         $data['credit'] = $row->credit;

        //     }

        //     dd($data);

    }

    public function sales_filter(Request $request){
        if(request()->ajax()){
            $head = $request->custhead;
            if(!empty($request->from_date)){
                $data = DB::table('acc_transactions')->where('head',$head)
                ->whereBetween('date', array($request->from_date, $request->to_date))->get();
            }else{
                $data = DB::table('acc_transactions')->where('head',$request->custhead)->get();
            }
            return datatables()->of($data)->make(true);
        }
    }

    public function filter_data(Request $request){
        if(request()->ajax()){
            $head = $request->custhead;
            if(!empty($request->from_date)){
                $data = DB::table('acc_transactions')->where('head',$request->custhead)->whereBetween('date', array($request->from_date, $request->to_date))->get()->toArray();
            }else{
                $data = DB::table('acc_transactions')->where('head',$request->custhead)->get()->toArray();
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

    public function search(Request $request) {
        if($request->ajax()) {
            $output = "";
            $banks = DB::table('bank_info')->where('name', 'LIKE','%'. $request->text .'%')->get();

            if(count($banks)) {
                foreach($banks as $bank){
                    $output .= '<button type="button" id="'.$bank->id.'" class="wow btn btn-sm btn-default mr-2">' . $bank->name . '</button>';
                }
                return response($output);
            } else {
                return response('No Bank Found!');
            }
        }
    }

    public function search_bank_acc(Request $request) {
        if($request->ajax()) {
            $output = "";
            $banks = DB::table('bank_acc')->where('bank_id',$request->text)->get();
            if(count($banks)) {
                foreach($banks as $bank){
                    $output .= '<button type="button" id="'.$bank->bank_id.'" class="bank_acc btn btn-sm btn-default mr-2">' . $bank->acc_name . '</button>';
                }
                return response($output);
            } else {
                return response('No Bank Account Found!');
            }
        }
    }

    public function addPayment(Request $request){

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

        if($paytype == 'cash'){
            $desc = "Paid In Cash";
        }else if($paytype == 'card'){
            $desc = "Card Payment";
        }else if($paytype == 'cheque'){
            $desc = "Cheque Payment";
        }else if($paytype == 'bank_transfer'){
            $desc = "Mobile Banking/Bank Tranfer";
        }

        $maxid = (DB::table('payment_invoice')->max('id') + 1);
        $invoice = "Inv-".$maxid;

//        DB::table('payment_invoice')->insert([
            PaymentInvoice::create([
            'invoice_no' => $invoice,
            'cid' => $cust_id,
            'amount' => $amount,
            'method' => $card,
            'description' => $desc,
            'remarks' => $remarks,
            'date' => $date,
            // 'user' => $user,
        ]);

        if($paytype == 'cash'){
            $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
            $vno = date('Ymd') . '-' . ($vno_counting + 1);

            $head = $cust_name." ".$cust_phone;
            $description = "Payment Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

//            DB::table('acc_transactions')->insert([

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'note' => "Paid In Cash",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                // 'user' => $user,

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
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'note' => "Recieved In Cash",
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                // 'user' => $user,

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
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'card',
                'status' => 'paid',
                'remarks' => $remarks,
                // 'user' => $user,

            ]);

            /////Insert into Accounts For Card Transaction

            $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
            $vno = date('Ymd') . '-' . ($vno_counting + 1);

            $head = $cust_name." ".$cust_phone;
            $description = "Payment Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                // 'user' => $user,

            ]);

            $head = $cardbank." A/C: ".$card_bank_account;
            $description = "Card Payment Invoice ".$invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                // 'user' => $user,

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
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'check',
                'status' => 'pending',
                'remarks' => $remarks,
                // 'user' => $user,

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
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'deposit' => $amount,
                'type' => 'mobile',
                'status' => 'paid',
                'tranxid' => $tranxid,
                'remarks' => $remarks,
                // 'user' => $user,

            ]);

            /////Insert into Accounts For Mobile Transaction

            $vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))->where('client_id', auth()->user()->client_id)->distinct()->count('vno');
            $vno = date('Ymd') . '-' . ($vno_counting + 1);

            $head = $cust_name." ".$cust_phone;
            $description = "Payment Invoice ".$invoice;
            $debit = 0;
            $credit = $amount;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                // 'user' => $user,

            ]);

            $head = $mobile_bank." A/C: ".$mobile_bank_account;
            $description = "Bank Transfer Invoice ".$invoice;
            $debit = $amount;
            $credit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                // 'user' => $user,

            ]);
        }

        echo $invoice;

    }

    public function payinvoice($id){
        $get_customer = DB::table('payment_invoice')->where('invoice_no', $id)->first();
        $custid = $get_customer->cid;
        $cust_details = Customer::where(['id'=>$custid])->get();
        return view('admin.pos.customer.payinvoice')->with(compact('cust_details','get_customer'));
    }

    public function saleinvoice($id){
        $invoiceno = $id;
        $get_customer = DB::table('sales_invoice')->where('invoice_no', $id)->first();
        $custid = $get_customer->cid;
        $cust_details = Customer::where(['id'=>$custid])->get();

        $details = DB::table('sales_invoice_details')
        ->select('products.product_name as name', 'products.product_img as image', 'sales_invoice_details.qnt as qnt',
        'sales_invoice_details.price as price','sales_invoice_details.vat')
        ->join('products', 'sales_invoice_details.pid', 'products.id')
        ->where('sales_invoice_details.invoice_no', $invoiceno)->get();

        $total = 0;
        foreach($details as $row){
            $qnt = $row->qnt;
            $total += $qnt;
        }

        $settings = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.customer.saleinvoice')->with(compact('cust_details','get_customer','details', 'settings','total'));
    }

    public function saleinvoicemain($id){
        $invoiceno = $id;
        $get_customer = DB::table('sales_invoice')->where('invoice_no', $id)->first();
        $custid = $get_customer->cid;
        $cust_details = Customer::where(['id'=>$custid])->get();

        $details = DB::table('sales_invoice_details')
        ->select('products.product_name as name', 'products.product_img as image', 'sales_invoice_details.qnt as qnt','sales_invoice_details.box',
        'sales_invoice_details.price as price','sales_invoice_details.vat')
        ->join('products', 'sales_invoice_details.pid', 'products.id')
        ->where('sales_invoice_details.invoice_no', $invoiceno)->get();

        $settings = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('admin.pos.customer.saleinvoice_old')->with(compact('cust_details','get_customer','details', 'settings'));
    }


    public function customers_due_report()
    {
        $customers = Customer::where('client_id', auth()->user()->client_id)->get();

        $customers_due = [];

        foreach($customers as $customer)
        {
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

        if(!$stdate){
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if(!$enddate){
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        foreach($customers as $customer)
        {
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
            
            foreach($collection as $collect)
            {
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

    public function get_customer_ledger(Request $req){

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

        $trow = "<tr><th>Previous Balance</th><th colspan='4'></th><th>". $previousBalance ."</th></tr>";

        $trow .= "<tr>
                    <th>Date</th>
                    <th>Voucher No</th>
                    <th>Description</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>";
        
        foreach($accounts as $row)
        {
            $Balance = $Balance + $row->debit - $row->credit;

            $date = date('d-M-Y', strtotime($row->date));

            $trow .= "<tr><td>".$date."</td><td>".$row->vno."</td><td>".$row->description."</td><td>".$row->debit."</td><td>".$row->credit."</td><td>". $Balance ."</td></tr>";
        }

        return $trow;
    }

}
