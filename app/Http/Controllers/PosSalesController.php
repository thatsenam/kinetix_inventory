<?php

namespace App\Http\Controllers;

use App\AccHead;
use App\AccTransaction;
use App\BankAcc;
use App\BankInfo;
use App\BankTransaction;
use App\Customer;
use App\SalesInvoice;
use App\SalesInvoiceDetails;
use App\SalesReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\ProductImage;
use App\Company;
use App\Manufacturer;
use App\Category;
use App\Filter;
use App\Seller;
use Image;
use Auth;
use DataTables;
use Yajra\DataTables\Contracts\DataTable;

class PosSalesController extends Controller
{

    public function index(){
        return view('admin.pos.index');
    }

    public function sales_invoice(){
        return view('admin.pos.sales.sales_invoice');
    }

    public function get_products(Request $req){

        $s_text = $req['s_text'];

        $products = DB::table('products')->where('client_id',auth()->user()->client_id)
            ->where('product_name', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='products-list sugg-list' style='width:100%;'>

        <?php $i = 1;

        foreach($products as $row){

            $id = $row->id;
            $name = $row->product_name;
            $price = $row->after_pprice;
            if(empty($price)){
                $price = $row->before_price;
            }
            $image = $row->product_img;

            $url = config('global.url'); ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectProducts("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $price; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-price='<?php echo $price; ?>'>
            <img src= "<?php echo $url;?>/images/products/<?php echo $image;?>" style="width:60px; height:60px;"> &nbsp; <?php echo $name; ?> | <?php echo $price; ?>
            </li>

            <?php

            $i = $i + 1;
        } ?>

        </ul>

<?php    }


    public function get_barcode(Request $req){
        $s_text = $req['s_text'];
        $products = DB::table('products')
            ->where('barcode', '=', $s_text)->first();
        $data = array(
            'id' => $products->id,
            'name' => $products->product_name,
            'price' => $products->after_pprice
        );
        return json_encode($data);
    }


    public function get_invoice(Request $req){

        $s_text = $req['s_text'];

        $get_invoice = DB::table('sales_invoice')
            ->where('invoice_no', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='invoice-list sugg-list'>

        <?php $i = 1;

        foreach($get_invoice as $row){

            $invoice = $row->invoice_no;

            $i = $i + 1; ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectInvoice("<?php echo $invoice; ?>");' data-invoice='<?php echo $invoice; ?>'><?php echo $invoice; ?></li>

        <?php } ?>

        </ul>

        <?php

    }


    public function get_invoice_details(Request $req){

        $s_text = $req['s_text'];

        $get_invoice = DB::table('sales_invoice_details')
            ->join('products', 'sales_invoice_details.pid', 'products.id')->where('sales_invoice_details.invoice_no', '=', $s_text)->get();

        $trow = "";

        foreach($get_invoice as $row){

            $trow .= "<tr><td>".$row->product_name."</td><td>".$row->price."</td><td>".$row->qnt."</td><td>".$row->total."</td></tr>";

        }

        $get_invoice = DB::table('sales_invoice')->where('invoice_no', '=', $s_text)->first();

        if($get_invoice->cid > 0){

            $get_cname = DB::table('customers')->where('id', '=', $get_invoice->cid)->first();

            $cust_name = $get_cname->name;
            $cust_phone = $get_cname->phone;

        }else{

            $cust_name = "";
            $cust_phone = "";
        }

        $vat = $get_invoice->vat;
        $scharge = $get_invoice->scharge;
        $discount = $get_invoice->discount;
        $amount = $get_invoice->amount;
        $gtotal = $get_invoice->gtotal;
        $payment = $get_invoice->payment;
        $due = $get_invoice->due;
        $date = $get_invoice->date;

        $user_id = Auth::id();

        $settings = DB::table('general_settings')->first();

        $company = $settings->site_name;

        $company_add = $settings->site_address;

        $data = array(

            "invoice" => $s_text,
            "trow" => $trow,
            "company" => $company,
            "company_add" => $company_add,
            "vat" => $vat,
            "scharge" => $scharge,
            "discount" => $discount,
            "amount" => $amount,
            "gtotal" => $gtotal,
            "payment" => $payment,
            "due" => $due,
            "date" => $date,
            "cust_name" => $cust_name,
            "cust_phone" => $cust_phone,
        );

        return json_encode($data);
    }


    public function get_supplier(Request $req){

        $s_text = $req['s_text'];

        $supplier = DB::table('suppliers')->where('name', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='supplier-list sugg-list'>

        <?php $i = 1;

        foreach($supplier as $row){

            $id = $row->id;
            $name = $row->name;
            $phone = $row->phone;

            $i = $i + 1; ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectSupplier("<?php echo $name; ?>", "<?php echo $id; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>'> <?php echo $name; ?></li>

        <?php } ?>

        </ul>

        <?php

    }

    public function sales_invoice_save(Request $req){

        $fieldValues = json_decode($req['fieldValues'], true);

        $cust_id = $fieldValues['cust_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $vat = $fieldValues['vat'];
        $scharge = $fieldValues['scharge'];
        $discount = $fieldValues['discount'];
        $amount = $fieldValues['amount'];
        $gtotal = (($amount + $vat + $scharge) - $discount);
        $paytype = $fieldValues['paytype'];
        $payment = $fieldValues['payment'];
        $due = $fieldValues['total'];
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();

        $clients_bank = $fieldValues['clients_bank'];
        $clients_bank_acc = $fieldValues['clients_bank_acc'];
        $check_amount = $fieldValues['check_amount'];
        $check_cash = $fieldValues['check_cash'];
        $check_date = $fieldValues['check_date'];
        $check_type = $fieldValues['check_type'];
        $shops_bank = $fieldValues['shops_bank'];
        $bank_id = $fieldValues['bank_id'];
        $shops_bank_account = $fieldValues['shops_bank_account'];
        $account_id = $fieldValues['account_id'];
        $check_remarks = $fieldValues['check_remarks'];

        $mobile_bank = $fieldValues['mobile_bank'];
        $mobile_bank_id = $fieldValues['mobile_bank_id'];
        $mobile_bank_acc_cust = $fieldValues['mobile_bank_acc_cust'];
        $mobile_bank_account = $fieldValues['mobile_bank_account'];
        $mobile_bank_acc_id = $fieldValues['mobile_bank_acc_id'];
        $mobile_amount = $fieldValues['mobile_amount'];
        $mobile_cash = $fieldValues['mobile_cash'];
        $tranxid = $fieldValues['tranxid'];
        $mobile_remarks = $fieldValues['mobile_remarks'];

        $card_type = $fieldValues['card_type'];
        $card_bank = $fieldValues['card_bank'];
        $card_bank_id = $fieldValues['card_bank_id'];
        $card_bank_account = $fieldValues['card_bank_account'];
        $card_bank_acc_id = $fieldValues['card_bank_acc_id'];
        $card_amount = $fieldValues['card_amount'];
        $card_cash = $fieldValues['card_cash'];
        $card_remarks = $fieldValues['card_remarks'];

        if($card_type == 'visa'){
            $card = "Visa Card";
        }else if($card_type == 'master'){
            $card = "Master Card";
        }else if($card_type == 'dbbl'){
            $card = "DBBL Nexus Card";
        }



        ////// into Customers Table////////////

        if(($cust_id == 0 || $cust_id == '') && ($cust_name != '' && $cust_phone != '')){

            $cust_id = (DB::table('customers')->max('id') + 1);

            Customer::create([
                'id' => $cust_id,
                'name' => $cust_name,
                'phone' => $cust_phone,
                'user' => $user,
            ]);

            AccHead::create([
                // 'cid' => $cust_id,
                'head' => $cust_name." ".$cust_phone,
                'user' => $user,

            ]);
        }


        if($due < 0){
            $payment = ($payment + $due);
            $due = 0;
        }


        $maxid = (DB::table('sales_invoice')->max('id') + 1);

        $invoice = "Inv-".$maxid;

        if($card_amount > 0 || $mobile_amount > 0 ){

            if($card_amount == ''){$card_amount = 0;}
            if($mobile_amount == ''){$mobile_amount = 0;}
            if($card_cash == ''){$card_cash = 0;}
            if($mobile_cash == ''){$mobile_cash = 0;}
            if($payment == ''){$payment = 0;}

            $payment = ($payment + $card_amount + $mobile_amount + $card_cash + $mobile_cash);
        }

        SalesInvoice::create([
            'invoice_no' => $invoice,
            'cid' => $cust_id,
            'vat' => $vat,
            'scharge' => $scharge,
            'discount' => $discount,
            'amount' => $amount,
            'gtotal' => $gtotal,
            'payment' => $payment,
            'due' => $due,
            'remarks' => $remarks,
            'date' => $date,
            'user' => $user,
        ]);

        $take_cart_items = json_decode($req['cartData'], true);

        $count = count($take_cart_items);

        for($i = 0; $i < $count;){

            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;

             ///////Adjust Stock/////////

            $get_stock = DB::table('products')->select('stock')->where('id', $take_cart_items[$j])->first();

            $stock = ($get_stock->stock - $take_cart_items[$j2]);

            DB::table('products')->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

            SalesInvoiceDetails::create ([
                'invoice_no' => $invoice,
                'pid' => $take_cart_items[$j],
                'qnt' => $take_cart_items[$j2],
                'price' => $take_cart_items[$j1],
                'total' => $take_cart_items[$j3],
                'user' => $user,
            ]);

            $i = $i + 4;
        }


        //////Save to Accounts//////

        if($paytype == 'cash'){

            if($due > 0 && $payment == 0){

                $vno = (DB::table('acc_transactions')->max('id') + 1);

                $head = "Sales";
                $description = "Due Sale Invoice ".$invoice;
                $debit = 0;
                $credit = $gtotal;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = $cust_name." ".$cust_phone;
                $description = "Due Sale Invoice ".$invoice;
                $debit = $gtotal;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

            }

            if($payment > 0 && $due == 0){

                $vno = (DB::table('acc_transactions')->max('id') + 1);

                $head = "Sales";
                $description = "Sale Invoice ".$invoice;
                $debit = 0;
                $credit = $payment;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = "Cash In Hand";
                $description = "Sale Invoice ".$invoice;
                $debit = $payment;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

            }

            if($payment > 0 && $due > 0){

                $vno = (DB::table('acc_transactions')->max('id') + 1);

                $head = "Sales";
                $description = "Due Sale Invoice ".$invoice;
                $debit = 0;
                $credit = $gtotal;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = "Cash In Hand";
                $description = "Sale from Invoice ".$invoice;
                $debit = $payment;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = $cust_name." ".$cust_phone;
                $description = "Due Sale from Invoice ".$invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

            }

        }

        ///// mobile transaction

        if($paytype == 'mobile'){

            if($mobile_bank_id != 0 && $mobile_bank_acc_id == 0){

                $maxmobid = (DB::table('bank_acc')->max('id')+1);

                BankAcc::Create(['id' => $maxmobid, 'bank_id' => $mobile_bank_id, 'acc_name' => $mobile_bank_account, 'user' => $user]);

                $mobile_bank_acc_id = $maxmobid;
            }

            if($mobile_bank_id == 0){

                $maxbankid = (DB::table('bank_info')->max('id')+1);

                BankInfo::Create(['id' => $maxbankid, 'name' => $mobile_bank, 'user' => $user]);

                $maxmobid = (DB::table('bank_acc')->max('id')+1);

                BankAcc::Create(['id' => $maxmobid, 'bank_id' => $maxbankid, 'acc_name' => $mobile_bank_account, 'user' => $user]);

                $mobile_bank_id = $maxbankid;

                $mobile_bank_acc_id = $maxmobid;
            }


             BankTransaction::Create([

                'seller_bank_id' => $mobile_bank_id,
                'seller_bank_acc_id' => $mobile_bank_acc_id,
                'clients_bank_acc' => $mobile_bank_acc_cust,
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'deposit' => $mobile_amount,
                'type' => 'mobile',
                'status' => 'paid',
                'tranxid' => $tranxid,
                'remarks' => $mobile_remarks,
                'user' => $user,

            ]);

            ///// into Accounts For Mobile Transaction

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Sales";
            $description = "Sale Invoice ".$invoice;
            $debit = 0;
            $credit = $gtotal;

//            DB::table('acc_transactions')->([
                AccTransaction::Create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = $mobile_bank." ".$mobile_bank_account;
            $description = "Sale Invoice ".$invoice;
            $debit = $mobile_amount;
            $credit = 0;

//            DB::table('acc_transactions')->([
            AccTransaction::Create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'notes' => "Mobile",
                'date' => $date,
                'user' => $user,

            ]);

            if($mobile_cash > 0){

                $head = "Cash in Hand";
                $description = "Sale Invoice ".$invoice;
                $debit = $mobile_cash;
                $credit = 0;

//                DB::table('acc_transactions')->([
                    AccTransaction::Create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

            }
        }


        ///// Card transaction

        if($paytype == 'card'){

            if($card_bank_id != 0 && $card_bank_acc_id == 0){

                $maxcardid = (DB::table('bank_acc')->max('id')+1);

//                BankAcc::Create
                BankAcc::Create
                (['id' => $maxcardid, 'bank_id' => $card_bank_id, 'acc_name' => $card_bank_account, 'user' => $user]);

                $card_bank_acc_id = $maxcardid;
            }

            if($card_bank_id == 0){

                $maxbankid = (DB::table('bank_info')->max('id')+1);

//                DB::table('bank_info')->
                BankInfo::Create
                (['id' => $maxbankid, 'name' => $card_bank, 'user' => $user]);

                $maxcardid = (DB::table('bank_acc')->max('id')+1);

                BankAcc::Create(['id' => $maxcardid, 'bank_id' => $maxbankid, 'acc_name' => $card_bank_account, 'user' => $user]);

                $card_bank_id = $maxbankid;

                $card_bank_acc_id = $maxcardid;
            }


            BankTransaction::Create([

                'seller_bank_id' => $card_bank_id,
                'seller_bank_acc_id' => $card_bank_acc_id,
                'clients_bank' => $card,
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'deposit' => $card_amount,
                'type' => 'card',
                'status' => 'paid',
                'remarks' => $card_remarks,
                'user' => $user,

            ]);

            ///// into Accounts For Card Transaction

            if($payment > 0 && $due > 0 && $card_cash == 0){

                $vno = (DB::table('acc_transactions')->max('id') + 1);

                $head = "Sales";
                $description = "Sale Invoice ".$invoice;
                $debit = 0;
                $credit = $gtotal;

//                DB::table('acc_transactions')->
                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice ".$invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'notes' => "Card",
                    'user' => $user,

                ]);

                $head = $cust_name." ".$cust_phone;
                $description = "Sale Invoice ".$invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'notes' => "Card",
                    'user' => $user,

                ]);

            }

            if($payment > 0 && $due == 0 && $card_cash == 0){
                $vno = (DB::table('acc_transactions')->max('id') + 1);

                $head = "Sales";
                $description = "Sale Invoice ".$invoice;
                $debit = 0;
                $credit = $gtotal;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice ".$invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'notes' => "Card",
                    'user' => $user,

                ]);

            }

            if($payment > 0 && $due == 0 && $card_cash > 0){
                $vno = (DB::table('acc_transactions')->max('id') + 1);

                $head = "Sales";
                $description = "Sale Invoice ".$invoice;
                $debit = 0;
                $credit = $gtotal;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice ".$invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'notes' => "Card",
                    'user' => $user,

                ]);

                $head = "Cash in Hand";
                $description = "Sale Invoice ".$invoice;
                $debit = $card_cash;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);
            }

        }

        ///// Check transaction

        if($paytype == 'check'){

            if($check_type == 'pay_account'){

                if($bank_id != 0 && $account_id == 0){

                    $maxaccountid = (DB::table('bank_acc')->max('id')+1);

                    BankAcc::Create(['id' => $maxaccountid, 'bank_id' => $bank_id, 'acc_name' => $shops_bank_account, 'user' => $user]);

                    $account_id = $maxaccountid;
                }

                if($bank_id == 0){

                    $maxbankid = (DB::table('bank_info')->max('id')+1);

                    BankInfo::Create(['id' => $maxbankid, 'name' => $shops_bank, 'user' => $user]);

                    $maxaccountid = (DB::table('bank_acc')->max('id')+1);

//                    DB::table('bank_acc')->
                    BankAcc::Create
                    (['id' => $maxaccountid, 'bank_id' => $maxbankid, 'acc_name' => $shops_bank_account, 'user' => $user]);

                    $bank_id = $maxbankid;

                    $account_id = $maxaccountid;
                }
            }


//            DB::table('bank_transactions')->

            BankTransaction::Create([

                'seller_bank_id' => $bank_id,
                'seller_bank_acc_id' => $account_id,
                'clients_bank' => $clients_bank,
                'clients_bank_acc' => $clients_bank_acc,
                'date' => $date,
                'cid' => $cust_id,
                'invoice_no' => $invoice,
                'deposit' => $check_amount,
                'type' => 'check',
                'status' => 'pending',
                'check_date' => $check_date,
                'remarks' => $check_remarks,
                'user' => $user,

            ]);

            ///// into Accounts For Check Transaction

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Sales";
            $description = "Sale Invoice ".$invoice;
            $debit = 0;
            $credit = $gtotal;

//            AccTransaction::Create

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = $cust_name." ".$cust_phone;
            $description = "Sale Invoice ".$invoice;
            $debit = $check_amount;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'notes' => "Check",
                'date' => $date,
                'user' => $user,

            ]);


            if($check_cash > 0){

                $head = "Cash in Hand";
                $description = "Sale Invoice ".$invoice;
                $debit = $check_cash;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid"." ".$cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user' => $user,

                ]);

            }

        }


        return $invoice;

    }

    public function delete_sales_invoice(Request $req){

        $invoice = $req['invoice'];

        DB::table('sales_invoice')->where('invoice_no', $invoice)->delete();

        $get_sales_details = DB::table('sales_invoice_details')->where('invoice_no', $invoice)->get();

        foreach($get_sales_details as $row){

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock + $qnt);

            DB::table('products')->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('sales_invoice_details')->where('invoice_no', $invoice)->delete();

        DB::table('bank_transactions')->where('invoice_no', $invoice)->delete();


        $get_accounts = DB::table('acc_transactions')->where('description', 'like', '%'.$invoice)->get();

        foreach($get_accounts as $row){

            $vno = $row->vno;

            DB::table('acc_transactions')->where('vno', $vno)->delete();
        }
    }

    public function sales_return(){
        return view("admin.pos.sales.sales_return");
    }


    public function sales_return_save(Request $req){

        $fieldValues = json_decode($req['fieldValues'], true);

        $cust_id = $fieldValues['cust_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $sinvoice = $fieldValues['invoice'];
        $due = $fieldValues['total'];
        $hid_total = $fieldValues['hid_total'];
        $payment = $fieldValues['payment'];
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();


        $maxid = (DB::table('sales_return')->max('id') + 1);

        $rinvoice = "Ret-".date('ymd')."-".$maxid;

        $take_cart_items = json_decode($req['cartData'], true);

        $count = count($take_cart_items);

        for($i = 0; $i < $count;){

            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;

             ///////Adjust Stock/////////

            $get_stock = DB::table('products')->select('stock')->where('id', $take_cart_items[$j])->first();

            $stock = ($get_stock->stock + $take_cart_items[$j2]);

            DB::table('products')->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

//            DB::table('sales_return')->insert
            SalesReturn::Create([
                'rinvoice' => $rinvoice,
                'sinvoice' => $sinvoice,
                'cid' => $cust_id,
                'pid' => $take_cart_items[$j],
                'qnt' => $take_cart_items[$j2],
                'uprice' => $take_cart_items[$j1],
                'tprice' => $take_cart_items[$j3],
                'total' => $hid_total,
                'cash_return' => $payment,
                'remarks' => $remarks,
                'date' => $date,
                'user' => $user,
            ]);

            $i = $i + 4;
        }

        //////Save to Accounts//////

        if($due > 0 && $payment == 0){

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Sales Return";
            $description = "Sales Return Invoice ".$rinvoice;
            $debit = $hid_total;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = $cust_name." ".$cust_phone;
            $description = "Sales Return Invoice ".$rinvoice;
            $debit = 0;
            $credit = $hid_total;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

        }

        if($payment > 0 && $due == 0){

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Sales Return";
            $description = "Sale Invoice ".$rinvoice;
            $debit = $payment;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = "Cash In Hand";
            $description = "Sale Return Invoice ".$rinvoice;
            $debit = 0;
            $credit = $payment;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

        }

        if($payment > 0 && $due > 0){

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Sales Return";
            $description = "Sales Return Invoice ".$rinvoice;
            $debit = $hid_total;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = "Cash In Hand";
            $description = "Sale Return Invoice ".$rinvoice;
            $debit = 0;
            $credit = $payment;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

            $head = $cust_name." ".$cust_phone;
            $description = "Sales Return Invoice ".$rinvoice;
            $debit = 0;
            $credit = $due;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid"." ".$cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user' => $user,

            ]);

        }

    }


    public function sales_report_date(){
        return view("admin.pos.sales.sales_report_date");
    }

    public function get_sales_report_date(Request $req){

        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if(!$stdate){
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if(!$enddate){
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        $sales = SalesInvoice::where('client_id',auth()->user()->client_id)
            ->whereBetween('date', [$stdate, $enddate])->get();

        return DataTables()->of($sales)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $action = '<a data-id='.$row->invoice_no.' title="View Details" href="#" class="view mr-2"><span class="btn btn-xs btn-info"><i class="mdi mdi-eye"></i></span></a>
            <a data-id='.$row->invoice_no.' title="Delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
            return $action;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function delete_sales_return(Request $req){

        $invoice = $req['invoice'];


        $get_sales_return = DB::table('sales_return')->where('rinvoice', $invoice)->get();

        foreach($get_sales_return as $row){

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock - $qnt);

            DB::table('products')->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('sales_return')->where('rinvoice', $invoice)->delete();

        $get_accounts = DB::table('acc_transactions')->where('description', 'like', '%'.$invoice)->get();

        foreach($get_accounts as $row){

            $vno = $row->vno;

            DB::table('acc_transactions')->where('vno', $vno)->delete();
        }
    }


    public function sales_return_report_date(){

        return view("admin.pos.sales.sales_return_report_date");
    }

    public function get_sales_return_report_date(Request $req){

        $stdate = $req['stdate'];

        $enddate = $req['enddate'];

        $sales = SalesReturn::where('client_id', auth()->user()->client_id)
//            ->join('products', 'sales_return.pid', 'products.id')
            ->whereBetween('date', [$stdate, $enddate])
            ->get();

        $trow = "";

        foreach($sales as $row){

            if($row->cid > 0){
                $customer = DB::table('customers')->select('name')->where('id', $row->cid)->first();
                $cname = $customer->name;
            }else{
                $cname = '';
            }

            $trow .= "<tr><td>".$row->date."</td><td class='invoice'>".$row->rinvoice."</td><td>".$row->sinvoice."</td><td>".$cname."</td><td>".$row->product_name."</td>
            <td>".$row->qnt."</td>
            <td>".$row->uprice."</td><td>".$row->tprice."</td><td>".$row->total."</td><td>".$row->cash_return."</td><td>".$row->remarks."</td>
            <td><a title='Delete' href='#' class='delete'><span class='btn btn-xs btn-danger'><i class='mdi mdi-delete'></i></span></a></td>
            </tr>";
        }

        return $trow;
    }


    ////////////////////Purchase Part/////////////////////


    public function get_suppmemo(Request $req){

        $s_text = $req['s_text'];

        $suppmemo = DB::table('purchase_primary')->where('supp_inv', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='suppmemo-list sugg-list'>

        <?php $i = 1;

        foreach($suppmemo as $row){

            $supp_inv = $row->supp_inv;

            $i = $i + 1; ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectPurmemo("<?php echo $supp_inv; ?>");' data-suppmemo='<?php echo $supp_inv; ?>'> <?php echo $supp_inv; ?></li>

        <?php } ?>

        </ul>

        <?php
    }



}


