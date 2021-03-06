<?php

namespace App\Http\Controllers;

use App\AccHead;
use App\AccTransaction;
use App\BankAcc;
use App\BankInfo;
use App\BankTransaction;
use App\Company;
use App\Customer;
use App\Filter;
use App\Manufacturer;
use App\Product;
use App\ProductImage;
use App\Products;
use App\PurchaseDetails;
use App\PurchasePrimary;
use App\SalesInvoice;
use App\SalesInvoiceDetails;
use App\SalesReturn;
use App\Seller;
use App\Serial;
use App\Stock;
use App\Warehouse;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

class PosSalesController extends Controller
{

    public function index()
    {
        return view('admin.pos.index');
    }

    public function sales_invoice()
    {


        $warehouses = Warehouse::where('client_id', auth()->user()->client_id)->get();

        $bank = BankAcc::where('client_id', auth()->user()->client_id)->get();
        if ($warehouses->count() < 2) {
            $getW = Warehouse::where('client_id', auth()->user()->client_id)->first();
            $warehouse_id = $getW->id;
        } else {
            $warehouse_id = "";
        }
        return view('admin.pos.sales.sales_invoice', compact('warehouses', 'warehouse_id', 'bank'));
    }

    public function sales_invoice_edit($invoice)
    {


        $warehouses = Warehouse::where('client_id', auth()->user()->client_id)->get();

        $bank = BankAcc::where('client_id', auth()->user()->client_id)->get();
        if ($warehouses->count() < 2) {
            $getW = Warehouse::where('client_id', auth()->user()->client_id)->first();
            $warehouse_id = $getW->id;
        } else {
            $warehouse_id = "";
        }

        $sales_p = SalesInvoice::firstWhere('invoice_no',$invoice);
        $sales_d =SalesInvoiceDetails::where('invoice_no',$invoice)->get();

        $data = Serial::query()->where('sale_inv',$invoice)->pluck('product_id','serial')->toArray();
        $serials = [];

        foreach ($data as $ser=>$product_id){
            $serials[$product_id][] = strval($ser)  ;

        }

        return view('admin.pos.sales.sales_invoice_edit', compact('warehouses', 'warehouse_id', 'bank','sales_p','sales_d','serials'));
    }

    public function get_products(Request $req)
    {

        $s_text = $req['s_text'];
        $products = json_decode($req['products']);

        $products = DB::table('products')->where('products.client_id', auth()->user()->client_id)
//            ->whereIn('products.id', $products)
            ->where('product_name', 'like', '%' . $s_text . '%')
            ->join('categories', 'categories.id', 'products.cat_id')
            ->select('products.id as id', 'products.product_name as product_name', 'products.after_pprice as after_pprice',
                'products.before_price as before_price', 'products.serial as serial', 'products.warranty as warranty',
                'products.product_img as product_img', 'products.sub_unit', 'products.unit', 'products.per_box_qty',
                'categories.vat as vat')->limit(9)->get(); ?>


        <ul class='products-list sugg-list' style='width:100%;'>

            <?php $i = 1;

            foreach ($products as $row) {
                $pid = $row->id;
                $pPurchase = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $psold = DB::table('sales_invoice_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $returns = DB::table('purchase_returns')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $sale_return = DB::table('sales_return')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $damage = DB::table('damage_products')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');

                $stock = $pPurchase - $returns - $psold + $sale_return - $damage;

                $pur_price = PurchaseDetails::Where('pid', $pid)->latest()->take(1)->first()->price ?? 0;

                $id = $row->id;
                $name = $row->product_name;
                $price = $row->after_pprice;
                $serial = $row->serial;
                $warranty = $row->warranty;
                $vat = $row->vat;
                $sub_unit = $row->sub_unit;
                $unit = $row->unit;
                $pbq = $row->per_box_qty;

                if (empty($price)) {
                    $price = $row->before_price;
                }
                $image = $row->product_img;

                $url = config('global.url'); ?>

                <li tabindex='<?php echo $i; ?>'
                    onclick='selectProducts("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $price; ?>", "<?php echo $serial; ?>", "<?php echo $warranty; ?>", "<?php echo $stock; ?>", "<?php echo $vat; ?>", "<?php echo $pbq; ?>", "<?php echo $sub_unit; ?>", "<?php echo $unit; ?>", "<?php echo $pur_price; ?>");'
                    data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-price='<?php echo $price; ?>'
                    data-sub_unit='<?php echo $sub_unit; ?>' data-unit='<?php echo $unit; ?>'
                    data-pbq='<?php echo $pbq; ?>' data-serial='<?php echo $serial; ?>'
                    data-warranty='<?php echo $warranty; ?>' data-stock='<?php echo $stock; ?> '
                    data-pur='<?php echo $pur_price; ?>'
                    data-vat='<?php echo $vat; ?>'>
                    &nbsp; <?php echo $name; ?> | <?php echo $price; ?>
                </li>

                <?php

                $i = $i + 1;
            } ?>

        </ul>

    <?php }

    public function get_return_products(Request $req)
    {

        $s_text = $req['s_text'];
        $products = json_decode($req['products']);

        $products = DB::table('products')->where('products.client_id', auth()->user()->client_id)
            ->whereIn('products.id', $products)
            ->where('product_name', 'like', '%' . $s_text . '%')
            ->join('categories', 'categories.id', 'products.cat_id')
            ->select('products.id as id', 'products.product_name as product_name', 'products.after_pprice as after_pprice', 'products.before_price as before_price', 'products.serial as serial', 'products.warranty as warranty', 'products.product_img as product_img', 'products.sub_unit', 'products.unit', 'products.per_box_qty', 'categories.vat as vat')->limit(9)->get(); ?>


        <ul class='products-list sugg-list' style='width:100%;'>

            <?php $i = 1;

            foreach ($products as $row) {
                $pid = $row->id;
                $pPurchase = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $psold = DB::table('sales_invoice_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $returns = DB::table('purchase_returns')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $sale_return = DB::table('sales_return')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $damage = DB::table('damage_products')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->sum('qnt');
                $stock = $pPurchase - $returns - $psold + $sale_return - $damage;

                $id = $row->id;
                $name = $row->product_name;
                $price = $row->after_pprice;
                $serial = $row->serial;
                $warranty = $row->warranty;
                $vat = $row->vat;
                $sub_unit = $row->sub_unit;
                $unit = $row->unit;
                $pbq = $row->per_box_qty;

                if (empty($price)) {
                    $price = $row->before_price;
                }
                $image = $row->product_img;

                $url = config('global.url'); ?>

                <li tabindex='<?php echo $i; ?>'
                    onclick='selectProducts("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $price; ?>", "<?php echo $serial; ?>", "<?php echo $warranty; ?>", "<?php echo $stock; ?>", "<?php echo $vat; ?>", "<?php echo $pbq; ?>", "<?php echo $sub_unit; ?>", "<?php echo $unit; ?>");'
                    data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-price='<?php echo $price; ?>'
                    data-sub_unit='<?php echo $sub_unit; ?>' data-unit='<?php echo $unit; ?>'
                    data-pbq='<?php echo $pbq; ?>' data-serial='<?php echo $serial; ?>'
                    data-warranty='<?php echo $warranty; ?>' data-stock='<?php echo $stock; ?>'
                    data-vat='<?php echo $vat; ?>'>
                    <img src="<?php echo $url; ?>/images/products/<?php echo $image; ?>"
                         style="width:60px; height:60px;"> &nbsp; <?php echo $name; ?> | <?php echo $price; ?>
                </li>

                <?php

                $i = $i + 1;
            } ?>

        </ul>

    <?php }


    public function get_barcode(Request $req)
    {
        $s_text = $req['s_text'];
        $products = DB::table('products')->where('client_id', auth()->user()->client_id)
            ->where('barcode', '=', $s_text)->first();

        $pid = $products->id;
        $pPurchase = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
            ->where('pid', $pid)->sum('qnt');
        $psold = DB::table('sales_invoice_details')->where('client_id', auth()->user()->client_id)
            ->where('pid', $pid)->sum('qnt');
        $returns = DB::table('purchase_returns')->where('client_id', auth()->user()->client_id)
            ->where('pid', $pid)->sum('qnt');
        $sale_return = DB::table('sales_return')->where('client_id', auth()->user()->client_id)
            ->where('pid', $pid)->sum('qnt');
        $damage = DB::table('damage_products')->where('client_id', auth()->user()->client_id)
            ->where('pid', $pid)->sum('qnt');
        $stock = $pPurchase - $returns - $psold + $sale_return - $damage;

        $data = array(
            'id' => $products->id,
            'name' => $products->product_name,
            'price' => $products->after_pprice,
            'serial' => $products->serial,
            'warranty' => $products->warranty,
            'stock' => $products->stock,
        );
        return json_encode($data);
    }


    public function get_invoice_products($invoice_no)
    {
        $products = [];
        $invoice = SalesInvoice::query()->where('invoice_no', $invoice_no)->first();
        if (!$invoice) {
            return [];
        }
        $products = SalesInvoiceDetails::query()->where('invoice_no', $invoice_no)->pluck('pid')->toArray();
        return $products;

    }

    public function get_purchase_invoice_products($invoice_no)
    {
        $products = [];
        $invoice = PurchasePrimary::query()->where('pur_inv', $invoice_no)->first();
        if (!$invoice) {
            return [];
        }
        $products = PurchaseDetails::query()->where('pur_inv', $invoice_no)->pluck('pid')->toArray();
        return $products;

    }

    public function get_invoice(Request $req)
    {

        $s_text = $req['s_text'];

        $get_invoice = SalesInvoice::query()->where('invoice_no', 'like', '%' . $s_text . '%')->limit(9)->get(); ?>

        <ul class='invoice-list sugg-list'>

            <?php $i = 1;

            foreach ($get_invoice as $row) {

                $invoice = $row->invoice_no;

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectInvoice("<?php echo $invoice; ?>");'
                    data-invoice='<?php echo $invoice; ?>'><?php echo $invoice; ?></li>

            <?php } ?>

        </ul>

        <?php

    }


    public function get_invoice_details(Request $req)
    {

        $s_text = $req['s_text'];

        $get_invoice = DB::table('sales_invoice_details')
            ->join('products', 'sales_invoice_details.pid', 'products.id')->where('sales_invoice_details.invoice_no', '=', $s_text)->get();

        $trow = "";

        foreach ($get_invoice as $row) {
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('sale_inv', $row->invoice_no)
                ->where('product_id', $row->pid)->pluck('serial')->toArray();
            $product_id = $row->pid;
            $product_gtotal = $row->total + $row->vat;
            $warranty = Products::find($product_id)->warranty;
            if ($serials) {
                $serials = implode(", ", $serials);

                if ($warranty) {
                    $trow .= "<tr><td>" . $row->product_name . "<br>Serial: " . $serials . "<br>Warranty: " . $warranty . " Month" . "</td><td>" . $row->price . "</td><td>" . $row->qnt . "</td><td>" . $row->vat . "</td><td>" . $row->total . "</td><td>" . $product_gtotal . "</td></tr>";
                } else {
                    $trow .= "<tr><td>" . $row->product_name . "<br>Serial: " . $serials . "</td><td>" . $row->price . "</td><td>" . $row->qnt . "</td><td>" . $row->vat . "</td><td>" . $row->total . "</td><td>" . $product_gtotal . "</td></tr>";
                }
            } else {
                if ($warranty) {
                    $trow .= "<tr><td>" . $row->product_name . "<br>Warranty: " . $warranty . " Month" . "</td><td>" . $row->price . "</td><td>" . $row->qnt . "</td><td>" . ($row->vat * 100) / $row->total . "</td><td>" . $row->total . "</td><td>" . $product_gtotal . "</td></tr>";
                } else {
                    $trow .= "<tr><td>" . $row->product_name . "</td><td>" . $row->price . "</td><td>" . $row->qnt . "</td><td>" . ($row->vat * 100) / $row->total . "</td><td>" . $row->total . "</td><td>" . $product_gtotal . "</td></tr>";
                }
            }
        }


        $get_invoice = DB::table('sales_invoice')->where('invoice_no', '=', $s_text)->first();

        if ($get_invoice->cid > 0) {

            $get_cname = DB::table('customers')->where('id', '=', $get_invoice->cid)->first();

            $cust_name = $get_cname->name;
            $cust_phone = $get_cname->phone;
            $cust_address = $get_cname->address ?? '';

        } else {

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
            "cust_address" => $cust_address,
        );

        return json_encode($data);
    }


    public function get_supplier(Request $req)
    {

        $s_text = $req['s_text'];

        $supplier = DB::table('suppliers')->where('name', 'like', '%' . $s_text . '%')->limit(9)->get(); ?>

        <ul class='supplier-list sugg-list'>

            <?php $i = 1;

            foreach ($supplier as $row) {

                $id = $row->id;
                $name = $row->name;
                $phone = $row->phone;

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectSupplier("<?php echo $name; ?>", "<?php echo $id; ?>");'
                    data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>'> <?php echo $name; ?></li>

            <?php } ?>

        </ul>

        <?php

    }

    public function get_serial($product)
    {
        $serials = Serial::where('client_id', auth()->user()->client_id)
            ->where('product_id', $product)
            ->whereNull('status')->pluck('serial');
        return $serials;
    }

    public function get_serial_sold($product)
    {
        $serials = Serial::where('client_id', auth()->user()->client_id)
            ->where('product_id', $product)
            ->where('status', 'sold')->pluck('serial');
        return $serials;
    }

    public function get_serial_all($product)
    {
        $serials = Serial::where('client_id', auth()->user()->client_id)
            ->where('product_id', $product)
            ->pluck('serial');
        return $serials;
    }

    public function sales_invoice_save(Request $req)
    {

        $fieldValues = json_decode($req['fieldValues'], true);

        $warehouse = $fieldValues['warehouse_id'];
        $cust_id = $fieldValues['cust_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $cust_address = $fieldValues['cust_address'] ?? '';
        $vat = $fieldValues['vat'];
        $vat = round($vat, 2);
        $scharge = $fieldValues['scharge'];
        $scharge = round($scharge, 2);
        $discount = $fieldValues['discount'];
        $discount = round($discount, 2);
        $amount = $fieldValues['amount'];
        $amount = round($amount, 2);
        $gtotal = (($amount + $vat + $scharge) - $discount);
        $gtotal = round($gtotal, 2);
        $sales_amount = (($amount + $scharge) - $discount);
        $sales_amount = round($sales_amount, 2);
        $paytype = $fieldValues['paytype'];
        $payment = $fieldValues['payment'];
        // $due = $fieldValues['total'];
        $due = $gtotal - $payment;
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();

        $clients_bank = $fieldValues['clients_bank'];
        $clients_bank_acc = $fieldValues['clients_bank_acc'];
        $check_amount = $fieldValues['check_amount'];
        $check_amount = round($check_amount, 2);
        $check_cash = $fieldValues['check_cash'];
        $check_cash = round($check_cash, 2);
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
        $mobile_amount = round($mobile_amount, 2);
        $mobile_cash = $fieldValues['mobile_cash'];
        $mobile_cash = round($mobile_cash, 2);
        $tranxid = $fieldValues['tranxid'];
        $mobile_remarks = $fieldValues['mobile_remarks'];

        $card_type = $fieldValues['card_type'];
        $card_bank = $fieldValues['card_bank'];
        $card_bank_id = $fieldValues['card_bank_id'];
        $card_bank_account = $fieldValues['card_bank_account'];
        $card_bank_acc_id = $fieldValues['card_bank_acc_id'];
        $card_amount = $fieldValues['card_amount'];
        $card_amount = round($card_amount, 2);
        $card_cash = $fieldValues['card_cash'];
        $card_cash = round($card_cash, 2);
        $card_remarks = $fieldValues['card_remarks'];

        if ($card_type == 'visa') {
            $card = "Visa Card";
        } else if ($card_type == 'master') {
            $card = "Master Card";
        } else if ($card_type == 'dbbl') {
            $card = "DBBL Nexus Card";
        }


        ////// into Customers Table////////////

        if (($cust_id == 0 || $cust_id == '') && ($cust_name != '' && $cust_phone != '')) {

            $cust_id = (DB::table('customers')->max('id') + 1);

            Customer::create([
                'id' => $cust_id,
                'name' => $cust_name,
                'phone' => $cust_phone,
                'address' => $cust_address,
                'user_id' => $user,
            ]);

            AccHead::create([
                'cid' => $cust_id,
                'parent_head' => "Asset",
                'sub_head' => "Customers Receivable",
                'head' => $cust_name . " " . $cust_phone,
            ]);
        }

        if ($due < 0) {
            $payment = ($payment + $due);
            $due = 0;
        }

        $inv_counting = SalesInvoice::whereDate('date', date('Y-m-d'))
            ->where('client_id', auth()->user()->client_id)->distinct()->count('invoice_no');
        $invoice = "INV-" . auth()->user()->client_id . date('Ymd') . ($inv_counting + 1);

        // $maxid = (DB::table('sales_invoice')->max('id') + 1);

        // $invoice = "Inv-".$maxid;

        if ($card_amount > 0 || $mobile_amount > 0) {

            if ($card_amount == '') {
                $card_amount = 0;
            }
            if ($mobile_amount == '') {
                $mobile_amount = 0;
            }
            if ($card_cash == '') {
                $card_cash = 0;
            }
            if ($mobile_cash == '') {
                $mobile_cash = 0;
            }
            if ($payment == '') {
                $payment = 0;
            }

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
            'vat_amount' => $vat,
            'due' => $due,
            'remarks' => $remarks,
            'date' => $date,
            'user_id' => $user,
        ]);

        $serials = json_decode($req['serialArray'], true);

        foreach ($serials as $productID => $serial) {
            foreach ($serial as $ser) {
                Serial::where('client_id', auth()->user()->client_id)
                    ->where('product_id', $productID)
                    ->where('serial', $ser)->update([
                        'status' => 'sold',
                        'sale_inv' => $invoice,
                    ]);
            }
        }

        $take_cart_items = json_decode($req['cartData'], true);
//         dd($take_cart_items);
        $count = count($take_cart_items);

        for ($i = 0; $i < $count;) {

            $j = $i;
            $j1 = $i + 1;
            $j2 = $i + 2;
            $j3 = $i + 3;
            $j4 = $i + 4;
            $j5 = $i + 5;


            ///////Adjust Stock/////////

            $get_stock = DB::table('products')->select('stock')->where('id', $take_cart_items[$j])->first();
            if (!$get_stock) {
                $stock = (0 - $take_cart_items[$j2]);

            } else {
                $stock = ($get_stock->stock - $take_cart_items[$j2]);

            }

            DB::table('products')->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

            SalesInvoiceDetails::create([
                'invoice_no' => $invoice,
                'pid' => $take_cart_items[$j],
                'qnt' => $take_cart_items[$j2],
                'box' => $take_cart_items[$j1],
                'price' => $take_cart_items[$j3],
                'vat' => $take_cart_items[$j4],
                'total' => $take_cart_items[$j5],
                'user_id' => $user,
            ]);

            Stock::create([
                'date' => $date,
                'warehouse_id' => $warehouse,
                'product_id' => $take_cart_items[$j],
                'box' => $take_cart_items[$j1],
                'out_qnt' => $take_cart_items[$j2],
                'particulars' => 'Sales',
                'remarks' =>$invoice,
                'user_id' => $user,
                'client_id' => auth()->user()->client_id,
            ]);

            $i = $i + 6;
        }


        //////Save to Accounts//////

        if ($paytype == 'cash') {

            if ($due > 0 && $payment == 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Due Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = "SalesVat ";
                $description = "Due Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user
                ]);

                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale Invoice " . $invoice;
                $debit = $gtotal;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

            if ($payment > 0 && $due == 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = "SalesVat ";
                $description = "Sales Vat from Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user
                ]);

                $head = "Cash In Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $payment;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

            if ($payment > 0 && $due > 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Due Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user

                ]);

                $head = "SalesVat ";
                $description = "Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user
                ]);

                $head = "Cash In Hand";
                $description = "Sale from Invoice " . $invoice;
                $debit = $payment;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale from Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

        }

        ///// mobile transaction

        if ($paytype == 'mobile') {

            if ($mobile_bank_id != 0 && $mobile_bank_acc_id == 0) {

                $maxmobid = (DB::table('bank_acc')->max('id') + 1);

                BankAcc::Create(['id' => $maxmobid, 'bank_id' => $mobile_bank_id, 'acc_name' => $mobile_bank_account, 'user' => $user]);

                $mobile_bank_acc_id = $maxmobid;
            }

            if ($mobile_bank_id == 0) {

                $maxbankid = (DB::table('bank_info')->max('id') + 1);

                BankInfo::Create(['id' => $maxbankid, 'name' => $mobile_bank, 'user' => $user]);

                $maxmobid = (DB::table('bank_acc')->max('id') + 1);

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
                'user_id' => $user,

            ]);

            ///// into Accounts For Mobile Transaction

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales";
            $description = "Sale Invoice " . $invoice;
            $debit = 0;
            $credit = $sales_amount;

            AccTransaction::Create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'note' => $invoice,
                'user_id' => $user,

            ]);

            $head = "Sales Vat ";
            $description = "Sales Vat from Invoice " . $invoice;
            $credit = $vat;
            $debit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                 'note' => $invoice,
                'user_id' => $user
            ]);

            $head = $mobile_bank . " " . $mobile_bank_account;
            $description = "Sale Invoice " . $invoice;
            $debit = $mobile_amount;
            $credit = 0;

            //            DB::table('acc_transactions')->([
            AccTransaction::Create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,

                'date' => $date,
                 'note' => $invoice,
                'user_id' => $user,

            ]);

            if ($mobile_cash > 0) {

                $head = "Cash in Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $mobile_cash;
                $credit = 0;

                //                DB::table('acc_transactions')->([
                AccTransaction::Create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user,

                ]);

            }
            $due = $gtotal - $mobile_amount - $mobile_cash;
            if ($due > 0) {
                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale from Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                     'note' => $invoice,
                    'user_id' => $user,

                ]);

            }
        }


        ///// Card transaction

        if ($paytype == 'card') {

            if ($card_bank_id != 0 && $card_bank_acc_id == 0) {

                $maxcardid = (DB::table('bank_acc')->max('id') + 1);

                //                BankAcc::Create
                BankAcc::Create
                (['id' => $maxcardid, 'bank_id' => $card_bank_id, 'acc_name' => $card_bank_account, 'user' => $user]);

                $card_bank_acc_id = $maxcardid;
            }

            if ($card_bank_id == 0) {

                $maxbankid = (DB::table('bank_info')->max('id') + 1);

                //                DB::table('bank_info')->
                BankInfo::Create
                (['id' => $maxbankid, 'name' => $card_bank, 'user' => $user]);

                $maxcardid = (DB::table('bank_acc')->max('id') + 1);

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
                'user_id' => $user,

            ]);

            ///// into Accounts For Card Transaction

            if ($payment > 0 && $due > 0 && $card_cash == 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = "Sales Vat ";
                $description = "Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user
                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice " . $invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,

                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = $cust_name . " " . $cust_phone;
                $description = "Sale Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

            if ($payment > 0 && $due == 0 && $card_cash == 0) {
                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = "Sales Vat ";
                $description = "Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user
                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice " . $invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

            if ($payment > 0 && $due == 0 && $card_cash > 0) {
                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $gtotal;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = "Sales Vat ";
                $description = "Sales Vat from Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user
                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice " . $invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = "Cash in Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $card_cash;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);
            }

        }

        ///// Check transaction

        if ($paytype == 'check') {

            if ($check_type == 'pay_account') {

                if ($bank_id != 0 && $account_id == 0) {

                    $maxaccountid = (DB::table('bank_acc')->max('id') + 1);

                    BankAcc::Create(['id' => $maxaccountid, 'bank_id' => $bank_id, 'acc_name' => $shops_bank_account, 'user' => $user]);

                    $account_id = $maxaccountid;
                }

                if ($bank_id == 0) {

                    $maxbankid = (DB::table('bank_info')->max('id') + 1);

                    BankInfo::Create(['id' => $maxbankid, 'name' => $shops_bank, 'user' => $user]);

                    $maxaccountid = (DB::table('bank_acc')->max('id') + 1);

//                    DB::table('bank_acc')->
                    BankAcc::Create([
                        'id' => $maxaccountid,
                        'bank_id' => $maxbankid,
                        'acc_name' => $shops_bank_account,
                        'user' => $user
                    ]);

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
                'user_id' => $user,

            ]);

            ///// into Accounts For Check Transaction

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales";
            $description = "Sale Invoice " . $invoice;
            $debit = 0;
            $credit = $sales_amount;

            //AccTransaction::Create

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'note' => $invoice,
                'user_id' => $user,

            ]);

            $head = "Sales Vat ";
            $description = "Sales Vatfrom Invoice " . $invoice;
            $credit = $vat;
            $debit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'note' => $invoice,
                'user_id' => $user
            ]);

            $head = $cust_name . " " . $cust_phone;
            $description = "Sale Invoice " . $invoice;
            $debit = $check_amount;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'note' => $invoice,
                'date' => $date,
                'user_id' => $user,

            ]);


            if ($check_cash > 0) {

                $head = "Cash in Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $check_cash;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

            $due = $gtotal - $check_amount - $check_cash;
            if ($due > 0) {
                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale from Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

        }

        return $invoice;

    }
    public function sales_invoice_save_edit(Request $req)
    {

        $fieldValues = json_decode($req['fieldValues'], true);

        $warehouse = $fieldValues['warehouse_id'];
        $cust_id = $fieldValues['cust_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $cust_address = $fieldValues['cust_address'] ?? '';
        $vat = $fieldValues['vat'];
        $vat = round($vat, 2);
        $scharge = $fieldValues['scharge'];
        $scharge = round($scharge, 2);
        $discount = $fieldValues['discount'];
        $discount = round($discount, 2);
        $amount = $fieldValues['amount'];
        $amount = round($amount, 2);
        $gtotal = (($amount + $vat + $scharge) - $discount);
        $gtotal = round($gtotal, 2);
        $sales_amount = (($amount + $scharge) - $discount);
        $sales_amount = round($sales_amount, 2);
        $paytype = $fieldValues['paytype'];
        $payment = $fieldValues['payment'];
        // $due = $fieldValues['total'];
        $due = $gtotal - $payment;
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();

        $clients_bank = $fieldValues['clients_bank'];
        $clients_bank_acc = $fieldValues['clients_bank_acc'];
        $check_amount = $fieldValues['check_amount'];
        $check_amount = round($check_amount, 2);
        $check_cash = $fieldValues['check_cash'];
        $check_cash = round($check_cash, 2);
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
        $mobile_amount = round($mobile_amount, 2);
        $mobile_cash = $fieldValues['mobile_cash'];
        $mobile_cash = round($mobile_cash, 2);
        $tranxid = $fieldValues['tranxid'];
        $mobile_remarks = $fieldValues['mobile_remarks'];

        $card_type = $fieldValues['card_type'];
        $card_bank = $fieldValues['card_bank'];
        $card_bank_id = $fieldValues['card_bank_id'];
        $card_bank_account = $fieldValues['card_bank_account'];
        $card_bank_acc_id = $fieldValues['card_bank_acc_id'];
        $card_amount = $fieldValues['card_amount'];
        $card_amount = round($card_amount, 2);
        $card_cash = $fieldValues['card_cash'];
        $card_cash = round($card_cash, 2);
        $card_remarks = $fieldValues['card_remarks'];
        $invoice_id = $fieldValues['invoice_id'];


        SalesInvoice::where('invoice_no',$invoice_id)->delete();
        SalesInvoiceDetails::where('invoice_no',$invoice_id)->delete();
        Stock::where('remarks',$invoice_id)->delete();
        AccTransaction::where('note',$invoice_id)->delete();

        if ($card_type == 'visa') {
            $card = "Visa Card";
        } else if ($card_type == 'master') {
            $card = "Master Card";
        } else if ($card_type == 'dbbl') {
            $card = "DBBL Nexus Card";
        }


        ////// into Customers Table////////////

        if (($cust_id == 0 || $cust_id == '') && ($cust_name != '' && $cust_phone != '')) {

            $cust_id = (DB::table('customers')->max('id') + 1);

            Customer::create([
                'id' => $cust_id,
                'name' => $cust_name,
                'phone' => $cust_phone,
                'address' => $cust_address,
                'user_id' => $user,
            ]);

            AccHead::create([
                'cid' => $cust_id,
                'parent_head' => "Asset",
                'sub_head' => "Customers Receivable",
                'head' => $cust_name . " " . $cust_phone,
            ]);
        }

        if ($due < 0) {
            $payment = ($payment + $due);
            $due = 0;
        }

        $inv_counting = SalesInvoice::whereDate('date', date('Y-m-d'))
            ->where('client_id', auth()->user()->client_id)->distinct()->count('invoice_no');
        $invoice = $invoice_id;

        // $maxid = (DB::table('sales_invoice')->max('id') + 1);

        // $invoice = "Inv-".$maxid;

        if ($card_amount > 0 || $mobile_amount > 0) {

            if ($card_amount == '') {
                $card_amount = 0;
            }
            if ($mobile_amount == '') {
                $mobile_amount = 0;
            }
            if ($card_cash == '') {
                $card_cash = 0;
            }
            if ($mobile_cash == '') {
                $mobile_cash = 0;
            }
            if ($payment == '') {
                $payment = 0;
            }

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
            'vat_amount' => $vat,
            'due' => $due,
            'remarks' => $remarks,
            'date' => $date,
            'user_id' => $user,
        ]);

        $serials = json_decode($req['serialArray'], true);

        foreach ($serials as $productID => $serial) {
            foreach ($serial as $ser) {
                Serial::where('client_id', auth()->user()->client_id)
                    ->where('product_id', $productID)
                    ->where('serial', $ser)->update([
                        'status' => 'sold',
                        'sale_inv' => $invoice,
                    ]);
            }
        }

        $take_cart_items = json_decode($req['cartData'], true);
//         dd($take_cart_items);
        $count = count($take_cart_items);

        for ($i = 0; $i < $count;) {

            $j = $i;
            $j1 = $i + 1;
            $j2 = $i + 2;
            $j3 = $i + 3;
            $j4 = $i + 4;
            $j5 = $i + 5;


            ///////Adjust Stock/////////

            $get_stock = DB::table('products')->select('stock')->where('id', $take_cart_items[$j])->first();
            if (!$get_stock) {
                $stock = (0 - $take_cart_items[$j2]);

            } else {
                $stock = ($get_stock->stock - $take_cart_items[$j2]);

            }

            DB::table('products')->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

            SalesInvoiceDetails::create([
                'invoice_no' => $invoice,
                'pid' => $take_cart_items[$j],
                'qnt' => $take_cart_items[$j2],
                'box' => $take_cart_items[$j1],
                'price' => $take_cart_items[$j3],
                'vat' => $take_cart_items[$j4],
                'total' => $take_cart_items[$j5],
                'user_id' => $user,
            ]);

            Stock::create([
                'date' => $date,
                'warehouse_id' => $warehouse,
                'product_id' => $take_cart_items[$j],
                'box' => $take_cart_items[$j1],
                'out_qnt' => $take_cart_items[$j2],
                'particulars' => 'Sales',
                'remarks' =>$invoice,
                'user_id' => $user,
                'client_id' => auth()->user()->client_id,
            ]);

            $i = $i + 6;
        }


        //////Save to Accounts//////

        if ($paytype == 'cash') {

            if ($due > 0 && $payment == 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Due Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

                $head = "Sales Vat";
                $description = "Due Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,
                ]);

                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale Invoice " . $invoice;
                $debit = $gtotal;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }

            if ($payment > 0 && $due == 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,
                ]);

                $head = "Sales Vat";
                $description = "Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,
                ]);

                $head = "Cash In Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $payment;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }

            if ($payment > 0 && $due > 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Due Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

                $head = "Sales Vat";
                $description = "Sales Vatfrom Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,
                ]);

                $head = "Cash In Hand";
                $description = "Sale from Invoice " . $invoice;
                $debit = $payment;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale from Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

        }

        ///// mobile transaction

        if ($paytype == 'mobile') {

            if ($mobile_bank_id != 0 && $mobile_bank_acc_id == 0) {

                $maxmobid = (DB::table('bank_acc')->max('id') + 1);

                BankAcc::Create(['id' => $maxmobid, 'bank_id' => $mobile_bank_id, 'acc_name' => $mobile_bank_account, 'user' => $user]);

                $mobile_bank_acc_id = $maxmobid;
            }

            if ($mobile_bank_id == 0) {

                $maxbankid = (DB::table('bank_info')->max('id') + 1);

                BankInfo::Create(['id' => $maxbankid, 'name' => $mobile_bank, 'user' => $user]);

                $maxmobid = (DB::table('bank_acc')->max('id') + 1);

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
                'user_id' => $user,


            ]);

            ///// into Accounts For Mobile Transaction

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales";
            $description = "Sale Invoice " . $invoice;
            $debit = 0;
            $credit = $sales_amount;

            AccTransaction::Create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
                'note' => $invoice,

            ]);

            $head = "Sales Vat";
            $description = "Sales Vatrom Invoice " . $invoice;
            $credit = $vat;
            $debit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
                'note' => $invoice,
            ]);

            $head = $mobile_bank . " " . $mobile_bank_account;
            $description = "Sale Invoice " . $invoice;
            $debit = $mobile_amount;
            $credit = 0;

            //            DB::table('acc_transactions')->([
            AccTransaction::Create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
                'note' => $invoice,

            ]);

            if ($mobile_cash > 0) {

                $head = "Cash in Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $mobile_cash;
                $credit = 0;

                //                DB::table('acc_transactions')->([
                AccTransaction::Create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }
            $due = $gtotal - $mobile_amount - $mobile_cash;
            if ($due > 0) {
                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale from Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }
        }


        ///// Card transaction

        if ($paytype == 'card') {

            if ($card_bank_id != 0 && $card_bank_acc_id == 0) {

                $maxcardid = (DB::table('bank_acc')->max('id') + 1);

                //                BankAcc::Create
                BankAcc::Create
                (['id' => $maxcardid, 'bank_id' => $card_bank_id, 'acc_name' => $card_bank_account, 'user' => $user]);

                $card_bank_acc_id = $maxcardid;
            }

            if ($card_bank_id == 0) {

                $maxbankid = (DB::table('bank_info')->max('id') + 1);

                //                DB::table('bank_info')->
                BankInfo::Create
                (['id' => $maxbankid, 'name' => $card_bank, 'user' => $user]);

                $maxcardid = (DB::table('bank_acc')->max('id') + 1);

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
                'user_id' => $user,

            ]);

            ///// into Accounts For Card Transaction

            if ($payment > 0 && $due > 0 && $card_cash == 0) {

                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

                $head = "Sales Vat";
                $description = "Sales Vat from Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                     'note' => $invoice,
                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice " . $invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

                $head = $cust_name . " " . $cust_phone;
                $description = "Sale Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'note' => $invoice,
                    'user_id' => $user,

                ]);

            }

            if ($payment > 0 && $due == 0 && $card_cash == 0) {
                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $sales_amount;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,


                ]);

                $head = "Sales Vat";
                $description = "Sales Vat from Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,


                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice " . $invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,

                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }

            if ($payment > 0 && $due == 0 && $card_cash > 0) {
                // $vno = (DB::table('acc_transactions')->max('id') + 1);
                $vno = time();

                $head = "Sales";
                $description = "Sale Invoice " . $invoice;
                $debit = 0;
                $credit = $gtotal;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,


                ]);

                $head = "Sales Vat";
                $description = "Sales Vat from Invoice " . $invoice;
                $credit = $vat;
                $debit = 0;

                AccTransaction::create([
                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,

                    'note' => $invoice,
                ]);

                $head = $card_bank_account;
                $description = "Sale Invoice " . $invoice;
                $debit = $card_amount;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,

                    'user_id' => $user,
                    'note' => $invoice,
                ]);

                $head = "Cash in Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $card_cash;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);
            }

        }

        ///// Check transaction

        if ($paytype == 'check') {

            if ($check_type == 'pay_account') {

                if ($bank_id != 0 && $account_id == 0) {

                    $maxaccountid = (DB::table('bank_acc')->max('id') + 1);

                    BankAcc::Create(['id' => $maxaccountid, 'bank_id' => $bank_id, 'acc_name' => $shops_bank_account, 'user' => $user]);

                    $account_id = $maxaccountid;
                }

                if ($bank_id == 0) {

                    $maxbankid = (DB::table('bank_info')->max('id') + 1);

                    BankInfo::Create(['id' => $maxbankid, 'name' => $shops_bank, 'user' => $user]);

                    $maxaccountid = (DB::table('bank_acc')->max('id') + 1);

//                    DB::table('bank_acc')->
                    BankAcc::Create([
                        'id' => $maxaccountid,
                        'bank_id' => $maxbankid,
                        'acc_name' => $shops_bank_account,
                        'user' => $user
                    ]);

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
                'user_id' => $user,


            ]);

            ///// into Accounts For Check Transaction

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales";
            $description = "Sale Invoice " . $invoice;
            $debit = 0;
            $credit = $sales_amount;

            //AccTransaction::Create

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
                'note' => $invoice,

            ]);

            $head = "Sales Vat";
            $description = "Sales Vat from Invoice " . $invoice;
            $credit = $vat;
            $debit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
                    'note' => $invoice,
            ]);

            $head = $cust_name . " " . $cust_phone;
            $description = "Sale Invoice " . $invoice;
            $debit = $check_amount;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
                'note' => $invoice,

            ]);


            if ($check_cash > 0) {

                $head = "Cash in Hand";
                $description = "Sale Invoice " . $invoice;
                $debit = $check_cash;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }

            $due = $gtotal - $check_amount - $check_cash;
            if ($due > 0) {
                $head = $cust_name . " " . $cust_phone;
                $description = "Due Sale from Invoice " . $invoice;
                $debit = $due;
                $credit = 0;

                AccTransaction::Create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "cid" . " " . $cust_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,
                    'note' => $invoice,

                ]);

            }

        }

        return $invoice;

    }

    public function delete_sales_invoice(Request $req)
    {

        $invoice = $req['invoice'];
        DB::table('sales_invoice')->where('invoice_no', $invoice)->delete();

        $get_sales_details = DB::table('sales_invoice_details')->where('invoice_no', $invoice)->get();

        foreach ($get_sales_details as $row) {

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock + $qnt);

            DB::table('products')->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('sales_invoice_details')->where('invoice_no', $invoice)->delete();

        DB::table('bank_transactions')->where('invoice_no', $invoice)->delete();


        $get_accounts = DB::table('acc_transactions')->where('description', 'like', '%' . $invoice)->get();
        $get_stocks = DB::table('stocks')->where('remarks', 'like', '%' . $invoice)->delete();

        foreach ($get_accounts as $row) {

            $vno = $row->vno;

            DB::table('acc_transactions')->where('vno', $vno)->delete();
        }

        return "OK";
    }

    public function sales_return()
    {
        $warehouses = Warehouse::where('client_id', auth()->user()->client_id)->get();
        if ($warehouses->count() < 2) {
            $getW = Warehouse::where('client_id', auth()->user()->client_id)->first();
            $warehouse_id = $getW->id;
        } else {
            $warehouse_id = "";
        }
        $invoices = SalesInvoice::query()->pluck('invoice_no');

        return view("admin.pos.sales.sales_return", compact('warehouses', 'warehouse_id', 'invoices'));
    }


    public function sales_return_save(Request $req)
    {

        $fieldValues = json_decode($req['fieldValues'], true);

        $warehouse = $fieldValues['warehouse_id'];
        $cust_id = $fieldValues['cust_id'];
        $cust_name = $fieldValues['cust_name'];
        $cust_phone = $fieldValues['cust_phone'];
        $sinvoice = $fieldValues['invoice'];
        //$due = $fieldValues['total'];
        $hid_total = $fieldValues['hid_total'];
        $hid_total = round($hid_total, 2);
        $payment = $fieldValues['payment'];
        $payment = round($payment, 2);
        $total_vat = $fieldValues['total_vat'];
        $total_vat = round($total_vat, 2);
        $remarks = $fieldValues['remarks'];
        $date = $fieldValues['date'];
        $user = Auth::id();
        $due = $hid_total + $total_vat - $payment;


        $inv_counting = SalesReturn::whereDate('date', date('Y-m-d'))
            ->where('client_id', auth()->user()->client_id)->distinct()->count('rinvoice');
        $rinvoice = "SRI-" . date('Ymd') . ($inv_counting + 1);

        // $maxid = (DB::table('sales_return')->max('id') + 1);

        // $rinvoice = "Ret-".date('ymd')."-".$maxid;

        $take_cart_items = json_decode($req['cartData'], true);
        // dd($take_cart_items);
        $count = count($take_cart_items);

        for ($i = 0; $i < $count;) {

            $j = $i;
            $j1 = $i + 1;
            $j2 = $i + 2;
            $j3 = $i + 3;
            $j4 = $i + 4;
            $j5 = $i + 5;

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
//                'box' => $take_cart_items[$j1],
                'uprice' => $take_cart_items[$j3],
                'vat_amount' => $take_cart_items[$j4],
                'tprice' => $take_cart_items[$j4],
                'total' => $hid_total + $total_vat,
                'cash_return' => $payment,
                'remarks' => $remarks,
                'date' => $date,
                'user_id' => $user,
            ]);

            Stock::create([
                'date' => $date,
                'warehouse_id' => $warehouse,
                'product_id' => $take_cart_items[$j],
//                'box' => $take_cart_items[$j1],
                'in_qnt' => $take_cart_items[$j2],
                'particulars' => 'Sales Return',
                'remarks' => 'Sales Return Invoice No-' . $rinvoice,
                'user_id' => $user,
                'client_id' => auth()->user()->client_id,
            ]);

            $i = $i + 6;
        }

        $serials = json_decode($req['serialArray'], true);

        foreach ($serials as $productID => $serial) {
            foreach ($serial as $ser) {
                Serial::where('client_id', auth()->user()->client_id)
                    ->where('product_id', $productID)
                    ->where('serial', $ser)
                    ->update([
                        'status' => 'sale_ret',
                        'sale_ret_inv' => $rinvoice,
                    ]);
            }
        }

        //////Save to Accounts//////

        if ($due > 0 && $payment == 0) {

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales Return";
            $description = "Sales Return Invoice " . $rinvoice;
            $debit = $hid_total;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

            $head = "Sales Vat";
            $description = "Sales Return Vat from Invoice " . $rinvoice;
            $debit = $total_vat;
            $credit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = $cust_name . " " . $cust_phone;
            $description = "Sales Return Invoice " . $rinvoice;
            $debit = 0;
            $credit = $hid_total + $total_vat;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

        }

        if ($payment > 0 && $due == 0) {

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales Return";
            $description = "Sale Invoice " . $rinvoice;
            $debit = $hid_total;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

            $head = "Sales Vat";
            $description = "Sales Return Vat from Invoice " . $rinvoice;
            $debit = $total_vat;
            $credit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = "Cash In Hand";
            $description = "Sale Return Invoice " . $rinvoice;
            $debit = 0;
            $credit = $payment;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

        }

        if ($payment > 0 && $due > 0) {

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Sales Return";
            $description = "Sales Return Invoice " . $rinvoice;
            $debit = $hid_total;
            $credit = 0;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

            $head = "Sales Vat";
            $description = "Sales Return Vat from Invoice " . $rinvoice;
            $debit = $total_vat;
            $credit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = "Cash In Hand";
            $description = "Sale Return Invoice " . $rinvoice;
            $debit = 0;
            $credit = $payment;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

            $head = $cust_name . " " . $cust_phone;
            $description = "Sales Return Invoice " . $rinvoice;
            $debit = 0;
            $credit = $due;

            AccTransaction::Create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "cid" . " " . $cust_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

        }

    }


    public function sales_report_date()
    {
        return view("admin.pos.sales.sales_report_date");
    }

    public function get_sales_report_date(Request $req)
    {

        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if (!$stdate) {
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if (!$enddate) {
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        $profitCalculation = DB::table('general_settings')->where('client_id', auth()->user()->client_id)
            ->pluck('profit_clc')->first();

        $sales = DB::table('sales_invoice')
            ->where('sales_invoice.client_id', auth()->user()->client_id)
            ->select('sales_invoice.id as id', 'sales_invoice.date as date', 'sales_invoice.invoice_no as invoice_no',
                'customers.name as cname', 'sales_invoice.vat as vat', 'sales_invoice.scharge as scharge', 'sales_invoice.discount as discount',
                'sales_invoice.amount as amount', 'sales_invoice.gtotal as gtotal', 'sales_invoice.payment as payment', 'sales_invoice.due as due')
            ->join('customers', 'sales_invoice.cid', 'customers.id')
            ->whereBetween('sales_invoice.date', [$stdate, $enddate])->get();

        foreach ($sales as $sale) {
            $inv = $sale->invoice_no;
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('sale_inv', $inv)->pluck('serial')->toArray();
            if ($serials) {
                $serials = implode(", ", $serials);
                $sale->serial = $serials;
            } else {
                $sale->serial = '';
            }

            $sales_product = SalesInvoiceDetails::where('client_id', auth()->user()->client_id)
                ->where('invoice_no', $inv)->select('price', 'pid', 'qnt')->get();
            $netProfit = 0;

            $sale->profit = round($netProfit, 2);

        }

        return DataTables()->of($sales)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<a data-id=' . $row->invoice_no . ' title="View" href="/dashboard/sales_invoicemain/' . $row->invoice_no . '" class="mr-2"><span class="btn btn-xs btn-info"><i class="mdi mdi-eye"></i></span></a><a data-id=' . $row->invoice_no . ' title="edit" href="#" class="edit"><span class="btn btn-xs btn-info"><i class="mdi mdi-pencil"></i></span></a><a data-id=' . $row->invoice_no . ' title="Delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
                return $action;
            })
            // <a data-id='.$row->invoice_no.' title="View Details" href="#" class="view mr-2"><span class="btn btn-xs btn-info"><i class="mdi mdi-eye"></i></span></a>
            ->rawColumns(['action'])
            ->make(true);
    }

    public function sales_report_brand( Request  $request)
    {
        $brands = DB::table('brands')->where('client_id', auth()->user()->client_id)->get();


        $bid = $request->select_brands;
        $formtime = $request->from_date;
        $lastTime = $request->to_date;


        if (!empty($request->from_date)) {
            $product = Products::query()->where('brand_id',$bid)->pluck('id');

            $data = SalesInvoiceDetails::where('client_id', auth()->user()->client_id)
                ->wherein('pid', $product)
                ->whereBetween('created_at', [$formtime, $lastTime])->get();


        } else {

            $data = SalesInvoiceDetails::where('client_id', auth()->user()->client_id)->get();

        }


        return view("admin.pos.sales.sales_report_brand", compact('brands' ,'formtime' ,'lastTime','data'));
    }

    public function get_sales_report_brand(Request $req)
    {

        $stdate = $req['from_date'];
        $enddate = $req['to_date'];
        $brand_id = $req['brand_id'];
        $brand_product_array = [];

        $products = DB::table('products')->where('client_id', auth()->user()->client_id)
            ->where('brand_id', $brand_id)->get();

        if (!$stdate) {
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if (!$enddate) {
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        if ($brand_id) {
            $brand_product_array = DB::table('sales_invoice')->where('sales_invoice.client_id', auth()->user()->client_id)
                ->join('sales_invoice_details', 'sales_invoice.invoice_no', 'sales_invoice_details.invoice_no')
                ->join('products', 'sales_invoice_details.pid', 'products.id')
                ->join('brands', 'products.brand_id', 'brands.id')
                ->where('brand_id', $brand_id)
                ->get();
            $brand_product_array->map(function ($query, $i) {
                $query->sl = $i + 1;
                $query->gtotal = $query->vat + $query->total;
                return $query;
            });
        }

        return DataTables()->of($brand_product_array)->make(true);
    }

    public function delete_sales_return(Request $req)
    {

        $invoice = $req['invoice'];


        $get_sales_return = DB::table('sales_return')->where('rinvoice', $invoice)->get();

        foreach ($get_sales_return as $row) {

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock - $qnt);

            DB::table('products')->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('sales_return')->where('rinvoice', $invoice)->delete();

        $get_accounts = DB::table('acc_transactions')->where('description', 'like', '%' . $invoice)->get();
        $get_stocks = DB::table('stocks')->where('remarks', 'like', '%' . $invoice)->delete();

        foreach ($get_accounts as $row) {

            $vno = $row->vno;

            DB::table('acc_transactions')->where('vno', $vno)->delete();
        }
    }

    public function get_sales_info($invoice_id)
    {
        $salesInvoice = SalesInvoice::query()->where('client_id',auth()->user()->client_id)->firstWhere('invoice_no', $invoice_id);

        return $salesInvoice->customer;
    }
    public function get_purchase_info($invoice_id)
    {
        $purchaseInvoice = PurchasePrimary::query()->where('client_id',auth()->user()->client_id)->firstWhere('pur_inv', $invoice_id);

        return $purchaseInvoice->supplier;
    }

    public function sales_return_report_date()
    {

        return view("admin.pos.sales.sales_return_report_date");
    }

    public function get_sales_return_report_date(Request $req)
    {

        $stdate = $req['stdate'];

        $enddate = $req['enddate'];

        if (!$stdate) {
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if (!$enddate) {
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        $sales = DB::table('sales_return')->where('sales_return.client_id', auth()->user()->client_id)
            ->select('sales_return.id as retid', 'sales_return.date as date', 'sales_return.rinvoice as rinvoice', 'sales_return.sinvoice as sinvoice', 'products.product_name as pname',
                'customers.name as cname', 'sales_return.qnt as qnt', 'sales_return.uprice as uprice', 'sales_return.tprice as tprice', 'sales_return.vat_amount as vat_amount', 'sales_return.total as total',
                'sales_return.cash_return as cash_return', 'sales_return.remarks as remarks')
            ->join('customers', 'sales_return.cid', 'customers.id')
            ->join('products', 'sales_return.pid', 'products.id')
            ->whereBetween('sales_return.date', [$stdate, $enddate])
            ->get();

        $sales->map(function ($sale) {
            $inv = $sale->rinvoice;
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('sale_ret_inv', $inv)->pluck('serial')->toArray();
            if ($serials) {
                $serials = implode(", ", $serials);
                $sale->serial = $serials;
            } else {
                $sale->serial = '';
            }


            $sale->total = $sale->total;

            return $sale;
        });

//        dd($sales);

        return DataTables()->of($sales)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<a data-invoice=' . $row->rinvoice . ' title="Delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);

        // $trow = "";

        // foreach($sales as $row){

        //     if($row->cid > 0){
        //         $customer = DB::table('customers')->select('name')->where('id', $row->cid)->first();
        //         $cname = $customer->name;
        //     }else{
        //         $cname = '';
        //     }

        //     $trow .= "<tr><td>".$row->date."</td><td class='invoice'>".$row->rinvoice."</td><td>".$row->sinvoice."</td><td>".$cname."</td><td>".$row->product_name."</td>
        //     <td>".$row->qnt."</td>
        //     <td>".$row->uprice."</td><td>".$row->tprice."</td><td>".$row->total."</td><td>".$row->cash_return."</td><td>".$row->remarks."</td>
        //     <td><a title='Delete' href='#' class='delete'><span class='btn btn-xs btn-danger'><i class='mdi mdi-delete'></i></span></a></td>
        //     </tr>";
        // }

        // return $trow;
    }


    ////////////////////Purchase Part/////////////////////


    public function get_suppmemo(Request $req)
    {

        $s_text = $req['s_text'];

        $suppmemo = DB::table('purchase_primary')->where('supp_inv', 'like', '%' . $s_text . '%')->limit(9)->get(); ?>

        <ul class='suppmemo-list sugg-list'>

            <?php $i = 1;

            foreach ($suppmemo as $row) {

                $supp_inv = $row->supp_inv;

                $i = $i + 1; ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectPurmemo("<?php echo $supp_inv; ?>");'
                    data-suppmemo='<?php echo $supp_inv; ?>'> <?php echo $supp_inv; ?></li>

            <?php } ?>

        </ul>

        <?php
    }


}


