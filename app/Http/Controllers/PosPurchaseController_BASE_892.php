<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use App\Stock;
use App\Filter;
use App\Seller;
use App\Serial;
use App\Company;
use App\Category;
use App\Products;
use App\Supplier;
use App\Warehouse;
use App\Manufacturer;
use App\ProductImage;
use App\DamageProduct;
use App\AccTransaction;
use App\PurchaseDetails;
use App\PurchasePrimary;
use App\PurchaseReturns;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosPurchaseController extends Controller
{


    public function purchase_products()
    {
        $warehouses = Warehouse::where('client_id', auth()->user()->client_id)->get();
        if($warehouses->count()<2){
            $getW = Warehouse::where('client_id', auth()->user()->client_id)->first();
            $warehouse_id = $getW->id;
        }else{
            $warehouse_id = "";
        }

        return view('admin.pos.purchase.purchase_products', compact('warehouses','warehouse_id'));
    }

    public function get_purchase_products(Request $req){

        $s_text = $req['s_text'];
//        $products = DB::table('products')
//            ->where('client_id',auth()->user()->client_id)
//            ->where('product_name', 'like', '%'.$s_text.'%')
//            ->orWhere('product_name', $s_text)
//            ->where('product_name', '!=', '')
//
//            ->orderByRaw("(product_name = '{$s_text}') desc, length(product_name)")
//            ->limit(9)->get();

        $products = DB::table('products')->where('products.client_id', auth()->user()->client_id)
        //            ->whereIn('products.id', $products)
        ->where('product_name', 'like', '%' . $s_text . '%')
        ->join('categories', 'categories.id', 'products.cat_id')
        ->select('products.id as id', 'products.product_name as product_name', 'products.after_pprice as after_pprice',
        'products.before_price as before_price', 'products.serial as serial', 'products.warranty as warranty',
        'products.product_img as product_img', 'products.sub_unit', 'products.unit', 'products.per_box_qty',
        'categories.vat as vat')->limit(9)->get(); ?>

        <ul class='products-list sugg-list'>

            <?php $i = 1;

            foreach($products as $row){

                $id = $row->id;
                $name = $row->product_name;
                $serial = $row->serial;
                $pbq = $row->per_box_qty;
                $vat = $row->vat ;
                $su = $row->sub_unit;
                $u = $row->unit;

                $products_price = DB::table('purchase_details')
                    ->where('client_id',auth()->user()->client_id)
                    ->where('pid',  $id)->orderBy('id', 'DESC')->limit(1)->get();

                if(count($products_price) > 0){
                    foreach($products_price as $row2){
                        $price = $row2->price;
                    }
                }else{
                    $price = 0;
                }

                $url = config('global.url'); ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectProducts("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $price; ?>", "<?php echo $serial; ?>" , "<?php echo $vat; ?>" , "<?php echo $pbq; ?>" , "<?php echo $su; ?>" , "<?php echo $u; ?>" );' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-price='<?php echo $price; ?>' data-pbq='<?php echo $pbq; ?>' data-serial='<?php echo $serial; ?>' >  <?php echo $name; ?> </li>

                <?php

                $i = $i + 1;
            } ?>

        </ul>

    <?php    }


    public function get_purchase_return_products(Request $req){

        $s_text = $req['s_text'];
        $memo = $req['supp_memo'];
        $products_id = Serial::where('pur_inv',$memo)->pluck('product_id');

        $products = DB::table('products')
            ->whereIn('id', $products_id)
            ->where('client_id',auth()->user()->client_id)
            ->where('product_name', 'like', '%'.$s_text.'%')
            ->orWhere('product_name', $s_text)
            ->where('product_name', '!=', '')

            ->orderByRaw("(product_name = '{$s_text}') desc, length(product_name)")
            ->limit(9)->get(); ?>

        <ul class='products-list sugg-list'>

            <?php $i = 1;

            foreach($products as $row){

                $id = $row->id;
                $name = $row->product_name;
                $serial = $row->serial;

                $products_price = DB::table('purchase_details')
                    ->where('client_id',auth()->user()->client_id)
                    ->where('pid',  $id)->orderBy('id', 'DESC')->limit(1)->get();

                if(count($products_price) > 0){
                    foreach($products_price as $row2){
                        $price = $row2->price;
                    }
                }else{
                    $price = 0;
                }

                $url = config('global.url'); ?>

                <li tabindex='<?php echo $i; ?>' onclick='selectProducts("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $price; ?>", "<?php echo $serial; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-price='<?php echo $price; ?>' data-serial='<?php echo $serial; ?>' ><?php echo $name; ?> </li>

                <?php

                $i = $i + 1;
            } ?>

        </ul>

    <?php    }


    public function get_supplier(Request $req){

        $s_text = $req['s_text'];

        $supplier = DB::table('suppliers')
            ->where('client_id',auth()->user()->client_id)
            ->where('name', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

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

    public function get_suppmemo(Request $req){

        $s_text = $req['s_text'];

        $suppmemo = DB::table('purchase_primary')
            ->where('client_id',auth()->user()->client_id)
            ->where('pur_inv', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

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
    public function get_suppmemo_list(Request $req){

        $s_text = $req['s_text'];


        $suppmemo = PurchasePrimary::all()->pluck('supp_inv');

        return $suppmemo ;
    }

    public function get_serial_purchased($product)
    {
        $serials = Serial::where('client_id', auth()->user()->client_id)
            ->where('product_id', $product)
            ->where(function($query){
                $query->whereNull('status')
                      ->orWhere('status', 'sale_ret');
            })->pluck('serial');
        return $serials;
    }


    public function save_purchase_products(Request $req){

        $fieldValues = json_decode($req['fieldValues'], true);

        $warehouse = $fieldValues['warehouse_id'];
        $supp_name = $fieldValues['supp_name'];
        $supp_id = $fieldValues['supp_id'];
        $supp_memo = $fieldValues['supp_memo'];
        $discount = (double)$fieldValues['discount'];
        $discount = round($discount, 2);
        $amount = (double)$fieldValues['amount'];
        $amount = round($amount, 2);
        $payment = (double)$fieldValues['payment'];
        $payment = round($payment, 2);
        $total = $fieldValues['total'];
        $total = round($total, 2);
        $total_vat = $fieldValues['total_vat'];
        $total_vat = round($total_vat, 2);
        $date = $fieldValues['date'];
        $user = Auth::id();

        $gtotal=$amount+$total_vat-$discount;
        $purchase_total=$amount-$discount;
        if($discount == ''){$discount = "0.00";}
        if($payment == ''){$payment = "0.00";}

        if($supp_id == 0){
            $maxsupid = (DB::table('suppliers')->max('id') + 1);
            Supplier::create([
                'name' => $supp_name
            ]);
            $supp_id = $maxsupid;
        }

        $inv_counting = PurchasePrimary::whereDate('date', date('Y-m-d'))
            ->where('client_id', auth()->user()->client_id)->distinct()->count('pur_inv');
        $pur_inv = "PUR-" . date('Ymd') . ($inv_counting + 1);

        // $maxid = (DB::table('purchase_primary')->max('id') + 1);
        // $pur_inv = "PUR-".$maxid;

        PurchasePrimary::create([
            'pur_inv' => $pur_inv,
            'sid' => $supp_id,
            'supp_inv' => $supp_memo,
            'discount' => $discount,
            'amount' => $amount,
            'total' => $gtotal,
            'vat_amount' => $total_vat,
            'payment' => $payment,
            'date' => $date,
            'user' => $user
        ]);

        $serials = json_decode($req['serialArray'], true);

        foreach($serials as $productID => $serial)
        {
            foreach($serial as $ser)
            {
                Serial::create([
                    'product_id' => $productID,
                    'serial' => $ser,
                    'supplier_id' => $supp_id,
                    'pur_inv' => $pur_inv,
                ]);
            }
        }

        $take_cart_items = json_decode($req['cartData'], true);
        $count = count($take_cart_items);
        // dd($take_cart_items);

        for($i = 0; $i < $count;){

            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;
            $j4 = $i+4;
            $j5 = $i+5;
            $j6 = $i+6;

            if($take_cart_items[$j] == 0){

                $max_pid = (Products::max('id') + 1);

                $product = new Products;
                $product->id = $max_pid;
                $product->name = $take_cart_items[$j1];
                $product->price = $take_cart_items[$j4];
                $product->stock = $take_cart_items[$j3];
                $product->save();

                $pid = $max_pid;

            }else{

                $product = DB::table('products')->where('id', $take_cart_items[$j])->first();

                $stock = $product->stock;
                $stock = ($stock + $take_cart_items[$j3]);

                DB::table('products')->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

                $pid = $take_cart_items[$j];
            }

            PurchaseDetails::create([
                'pur_inv' => $pur_inv,
                'pid' => $pid,
                'qnt' => $take_cart_items[$j3],
                'box' => $take_cart_items[$j2],
                'price' => $take_cart_items[$j4],
                'vat' => $take_cart_items[$j5],
                'total' => $take_cart_items[$j6],
                'user' => $user
            ]);

            Stock::create ([
                'date' => $date,
                'warehouse_id' => $warehouse,
                'product_id' => $pid,
                'box' => $take_cart_items[$j2],
                'in_qnt' => $take_cart_items[$j3],
                'particulars' => 'Purchase',
                'remarks' => 'Purchase Invoice No-'.$pur_inv,
                'user_id' => $user,
                'client_id' => auth()->user()->client_id,
            ]);


            $i = $i + 7;
        }

        //////Save to Accounts//////

        $supplier = DB::table('suppliers')->where('id', $supp_id)->first();

        if($payment>0){

            $vno = time();

            $head = "Purchase";
            $description = "Purchase from Invoice ".$pur_inv;
            $credit = 0;
            $debit = $purchase_total;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,
            ]);

            $head = "Purchase I.V.A";
            $description = "Purchase I.V.A from Invoice ".$pur_inv;
            $credit = 0;
            $debit = $total_vat;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = "Cash In Hand";
            $description = "Purchase";
            $credit = $payment;
            $debit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

            $due=$gtotal-$payment;
            if($due > 0){
                $head = $supplier->name." ".$supplier->phone;
                $description = "Purchase Due From Invoice  ".$pur_inv;
                $credit = $due;
                $debit = 0;

                AccTransaction::create([

                    'vno' => $vno,
                    'head' => $head,
                    'sort_by' => "sid"." ".$supp_id,
                    'description' => $description,
                    'debit' => $debit,
                    'credit' => $credit,
                    'date' => $date,
                    'user_id' => $user,

                ]);
            }

        }else{

            $vno = time();

            $head = "Purchase";
            $description = "Due Purchase from Invoice ".$pur_inv;
            $credit = 0;
            $debit = $purchase_total;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = "Due Purchase I.V.A";
            $description = "Purchase I.V.A from Invoice ".$pur_inv;
            $credit = 0;
            $debit = $total_vat;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = $supplier->name." ".$supplier->phone;
            $description = "Purchase";
            $credit = $gtotal;
            $debit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);
        }

    }


    public function purchase_return()
    {
        $warehouses = Warehouse::where('client_id', auth()->user()->client_id)->get();
        if($warehouses->count()<2){
            $getW = Warehouse::where('client_id', auth()->user()->client_id)->first();
            $warehouse_id = $getW->id;
        }else{
            $warehouse_id = "";
        }



        $pd = PurchasePrimary::query()->pluck('supp_inv','pur_inv')->toArray();




        return view('admin.pos.purchase.purchase_return', compact('warehouses','warehouse_id','pd'));
    }

    public function save_purchase_return (Request $req){

        $fieldValues = json_decode($req['fieldValues'], true);

        $warehouse = $fieldValues['warehouse_id'];
        $supp_id = $fieldValues['supp_id'];
        $supp_memo = $fieldValues['supp_memo'];
        $total = $fieldValues['total'];
        $total = round($total, 2);
        $total_vat = $fieldValues['total_vat'];
        $total_vat = round($total_vat, 2);
        $date = $fieldValues['date'];
        $user = Auth::id();

        $take_cart_items = json_decode($req['cartData'], true);
        // dd($take_cart_items);
        $count = count($take_cart_items);

        for($i = 0; $i < $count;){

            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;
            $j4 = $i+4;
            $j5 = $i+5;
            $j6 = $i+6;

            $product = DB::table('products')->where('client_id',auth()->user()->client_id)
                ->where('id', $take_cart_items[$j])->first();

            $stock = $product->stock;;
            $stock = ($stock - $take_cart_items[$j3]);

            DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

            $pid = $take_cart_items[$j];

//            DB::table('purchase_returns')->insert([
            $pur_ret = PurchaseReturns::create([

                'date' => $date,
                'pur_inv' => $supp_memo,
                'pid' => $pid,
                'qnt' => $take_cart_items[$j3],

                'price' => $take_cart_items[$j4],
                'vat_amount' => $take_cart_items[$j5],
                'total' => $take_cart_items[$j6],
                'sid' => $supp_id,
                'user' => $user
            ]);

            Stock::create ([
                'date' => $date,
                'warehouse_id' => $warehouse,
                'product_id' => $pid,
                'out_qnt' => $take_cart_items[$j3],
                'particulars' => 'Purchase Return',
                'remarks' => 'Purchase Return Invoice No-'.$supp_memo,
                'user_id' => auth()->user()->id,
                'client_id' => auth()->user()->client_id,
            ]);


            $i = $i + 7;
        }

        $serials = json_decode($req['serialArray'], true);



        foreach($serials as $productID => $serial)
        {

            foreach($serial as $ser)
            {


                Serial::where('product_id', $productID)
                    ->where('serial', $ser)
                    ->update([
                        'status' => 'pur_ret',
                        'pur_ret_inv' => $supp_memo ?? " ",
                    ]);
            }
        }

        //////Save to Accounts//////

        $supplier = DB::table('suppliers')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $supp_id)->first();

            // $vno = (DB::table('acc_transactions')->max('id') + 1);
            $vno = time();

            $head = "Purchase Return";
            $description = "Purchase Return from Supplier Memo ".$supp_memo;
            $credit = $total;
            $debit = 0;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

            $head = "Purchase I.V.A";
            $description = "Purchase Return I.V.A from Invoice ".$supp_memo;
            $credit = $total_vat;
            $debit = 0;

            AccTransaction::create([
                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user
            ]);

            $head = $supplier->name?? " "." ".$supplier->phone;;
            $description = "Purchase Return";
            $credit = 0;
            $debit = $total+$total_vat;

            AccTransaction::create([

                'vno' => $vno,
                'head' => $head,
                'sort_by' => "sid"." ".$supp_id,
                'description' => $description,
                'debit' => $debit,
                'credit' => $credit,
                'date' => $date,
                'user_id' => $user,

            ]);

    }

    public function damage_products()
    {
        $dmg_count = DamageProduct::whereDate('date', date('Y-m-d'))
                                ->where('client_id', auth()->user()->client_id)->distinct()->count('dmg_inv');
        $warehouses = Warehouse::where('client_id', auth()->user()->client_id)->get();
        if($warehouses->count()<2){
            $getW = Warehouse::where('client_id', auth()->user()->client_id)->first();
            $warehouse_id = $getW->id;
        }else{
            $warehouse_id = "";
        }

        $dmg_inv = 'DMG-' . ($dmg_count + 1);

        return view('admin.pos.damage-products.damage_products', compact('dmg_inv','warehouses','warehouse_id'));
    }

    public function save_damage_products (Request $req)
    {
        $fieldValues = json_decode($req['fieldValues'], true);

        // $supp_id = $fieldValues['supp_id'];
        // $supp_memo = $fieldValues['supp_memo'];
        $total = $fieldValues['total'];
        $total = round($total, 2);
        $date = $fieldValues['date'];
        $dmg_inv = $fieldValues['dmg_inv'];
        $warehouse = $fieldValues['warehouse_id'];

        $take_cart_items = json_decode($req['cartData'], true);
        // dd($take_cart_items);
        $count = count($take_cart_items);

        for($i = 0; $i < $count;)
        {
            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;
            $j4 = $i+4;
            $j5 = $i+5;

            $product = DB::table('products')->where('client_id',auth()->user()->client_id)
                        ->where('id', $take_cart_items[$j])->first();

            $stock = $product->stock;
            $stock = ($stock - $take_cart_items[$j3]);

            DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

            $pid = $take_cart_items[$j];

            DamageProduct::create([
                'date' => $date,
                'dmg_inv' => $dmg_inv,
                'pid' => $pid,
                'box' => $take_cart_items[$j2],
                'qnt' => $take_cart_items[$j3],
                'price' => $take_cart_items[$j4],
                'total' => $take_cart_items[$j5],
            ]);

            Stock::create ([
                'date' => $date,
                'warehouse_id' => $warehouse,
                'product_id' => $pid,
                'box' => $take_cart_items[$j2],
                'out_qnt' => $take_cart_items[$j3],
                'particulars' => 'Damage',
                'remarks' => 'Damage Invoice No-'.$dmg_inv,
                'user_id' => auth()->user()->id,
                'client_id' => auth()->user()->client_id,
            ]);

            $i = $i + 6;
        }

        $serials = json_decode($req['serialArray'], true);

        foreach($serials as $productID => $serial)
        {
            foreach($serial as $ser)
            {
                Serial::where('client_id', auth()->user()->client_id)
                    ->where('product_id', $productID)
                    ->where('serial', $ser)
                    ->update([
                        'status' => 'damage',
                    ]);
            }
        }

    }


    public function purchase_report_date(){
        return view("admin.pos.purchase.purchase_report_date");
    }

    public function get_purchase_report_date(Request $req){
        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if(!$stdate){
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if(!$enddate){
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        $purchase = DB::table('purchase_primary')
            ->join('suppliers', 'purchase_primary.sid', 'suppliers.id')
            ->where('purchase_primary.client_id',auth()->user()->client_id)
            ->whereBetween('date', [$stdate, $enddate])->get();

        $purchase->map(function($purchase){
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('pur_inv', $purchase->pur_inv)->pluck('serial')->toArray();
            if($serials){
                $serials = implode (", ", $serials);
                $purchase->serial = $serials;
            }else{
                $purchase->serial = '';
            }

            $due = $purchase->total - $purchase->payment;
            $purchase->due = $due;
            return $purchase;
        });

        return DataTables()->of($purchase)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $action = '<a data-id='.$row->pur_inv.' title="View Details" href="#" class="view mr-2"><span class="btn btn-xs btn-info"><i class="mdi mdi-eye"></i></span></a>
            <a data-id='.$row->pur_inv.' title="Delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
            return $action;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function purchase_report_brand()
    {
        $brands = DB::table('brands')->where('client_id', auth()->user()->client_id)->get();
        $suppliers = DB::table('suppliers')->where('client_id', auth()->user()->client_id)->get();
        return view("admin.pos.purchase.purchase_report_brand", compact('brands', 'suppliers'));
    }

    public function get_purchase_report_brand(Request $req)
    {
        $stdate = $req['from_date'];
        $enddate = $req['to_date'];
        $brand_id = $req['brand_id'];
        $supplier_id = $req['supplier_id'];
        $brand_product_array = [];

        if(!$stdate){
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if(!$enddate){
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        if($supplier_id && $brand_id)
        {
            $brand_product_array = DB::table('purchase_primary')->where('purchase_primary.client_id', auth()->user()->client_id)
                        // ->select('purchase_primary.pur_inv', 'purchase_primary.supp_inv', 'products.product_name',
                        // 'purchase_details.qnt', 'purchase_details.price', 'purchase_primary.vat_amount', 'purchase_primary.total')
                        ->join('purchase_details', 'purchase_primary.pur_inv', 'purchase_details.pur_inv')
                            ->join('suppliers', 'purchase_primary.sid', 'suppliers.id','suppliers.name')
                        ->join('products', 'purchase_details.pid', 'products.id')
                        ->join('brands', 'products.brand_id', 'brands.id')
                        ->where('sid', $supplier_id)
                        ->where('brand_id', $brand_id)
                        ->get();
            $brand_product_array->map(function($query, $i){
                $query->sl = $i + 1;

                $query->gtotal = $query->vat + $query->total;

                return $query;
            });
        }
        else if($supplier_id)
        {
            $brand_product_array = DB::table('purchase_primary')->where('purchase_primary.client_id', auth()->user()->client_id)

                        ->join('purchase_details', 'purchase_primary.pur_inv', 'purchase_details.pur_inv')
                        ->join('suppliers', 'purchase_primary.sid', 'suppliers.id')
                        ->join('products', 'purchase_details.pid', 'products.id')
                        ->join('brands', 'products.brand_id', 'brands.id')
                        ->where('sid', $supplier_id)
                        ->get();
            $brand_product_array->map(function($query, $i){
                $query->sl = $i + 1;
                $query->gtotal = $query->vat + $query->total;

                return $query;
            });
        }
        else if($brand_id)
        {
            $brand_product_array = DB::table('purchase_primary')->where('purchase_primary.client_id', auth()->user()->client_id)
                        ->join('purchase_details', 'purchase_primary.pur_inv', 'purchase_details.pur_inv')
                        ->join('suppliers', 'purchase_primary.sid', 'suppliers.id')
                        ->join('products', 'purchase_details.pid', 'products.id')
                        ->join('brands', 'products.brand_id', 'brands.id')
                        ->where('brand_id', $brand_id)
                        ->get();
            $brand_product_array->map(function($query, $i){
                $query->sl = $i + 1;
                $query->gtotal = $query->vat + $query->total;
                return $query;
            });
        }

        return DataTables()->of($brand_product_array)->make(true);
    }

    public function delete_purchase(Request $req){

        $purinv = $req['purinv'];

        DB::table('purchase_primary')
            ->where('client_id',auth()->user()->client_id)
            ->where('pur_inv', $purinv)->delete();

        $get_pur_details = DB::table('purchase_details')
            ->where('client_id',auth()->user()->client_id)
            ->where('pur_inv', $purinv)->get();

        foreach($get_pur_details as $row){

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock - $qnt);

            DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('purchase_details')
            ->where('client_id',auth()->user()->client_id)
            ->where('pur_inv', $purinv)->delete();

        $get_accounts = DB::table('acc_transactions')
            ->where('client_id',auth()->user()->client_id)
            ->where('description', 'like', '%'.$purinv)->get();

        $get_stocks = DB::table('stocks')
            ->where('client_id',auth()->user()->client_id)
            ->where('remarks', 'like', '%'.$purinv)->delete();

        foreach($get_accounts as $row){

            $vno = $row->vno;

            DB::table('acc_transactions')
                ->where('client_id',auth()->user()->client_id)
                ->where('vno', $vno)->delete();
        }
    }

    public function purchase_return_report_date(){
        return view("admin.pos.purchase.purchase_return_report_date");
    }

    public function get_purchase_return_report_date(Request $req){

        $stdate = $req['stdate'];

        $enddate = $req['enddate'];

        if(!$stdate){
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if(!$enddate){
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        $purchase = DB::table('purchase_returns')->where('purchase_returns.client_id',auth()->user()->client_id)
            ->select('purchase_returns.id as retid', 'purchase_returns.date as date','purchase_returns.pur_inv as pur_inv','products.product_name as pname',
            'suppliers.name as sname', 'purchase_returns.qnt as qnt', 'purchase_returns.vat_amount as vat', 'purchase_returns.price as price','purchase_returns.total as total')
            ->join('suppliers', 'purchase_returns.sid', 'suppliers.id')
            ->join('products', 'purchase_returns.pid', 'products.id')
            ->whereBetween('date', [$stdate, $enddate])->get();

        $purchase->map(function($purchase){
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('pur_ret_inv', $purchase->pur_inv)->pluck('serial')->toArray();
            if($serials){
                $serials = implode (", ", $serials);
                $purchase->serial = $serials;
            }else{
                $purchase->serial = '';
            }

            $gtotal = $purchase->total + $purchase->vat;
            $purchase->gtotal = $gtotal;

            return $purchase;
        });

        return DataTables()->of($purchase)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $action = '<a data-id='.$row->retid.' data-invoice='.$row->pur_inv.' title="delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete_purchase_return(Request $req){

        $id = $req['id'];
        $invoice = $req['invoice'];

        $purchase_returns = DB::table('purchase_returns')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $id)->get();

        foreach($purchase_returns as $row){

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('client_id',auth()->user()->client_id)
                ->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock + $qnt);

            DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $pid)->update(['stock' => $stock]);
        }

        DB::table('purchase_returns')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $id)->delete();
        $get_stocks = DB::table('stocks')
            ->where('client_id',auth()->user()->client_id)
            ->where('remarks', 'like', '%Invoice No-%'.$invoice)->delete();

        $get_accounts = DB::table('acc_transactions')
            ->where('client_id',auth()->user()->client_id)
            ->where('description', 'like', '%Memo '.$invoice)->get();

        foreach($get_accounts as $row){

            $vno = $row->vno;

            DB::table('acc_transactions')
                ->where('client_id',auth()->user()->client_id)
                ->where('vno', $vno)->delete();
        }

    }

    public function damage_report_date()
    {
        return view("admin.pos.damage-products.damage_report_date");
    }

    public function get_damage_report_date(Request $req)
    {
        $stdate = $req['stdate'];

        $enddate = $req['enddate'];

        if(!$stdate){
            $stdate = date('Y-m-d', strtotime('-1 day'));
        }
        if(!$enddate){
            $enddate = date('Y-m-d', strtotime('+1 day'));
        }

        $damage = DB::table('damage_products')->where('damage_products.client_id',auth()->user()->client_id)
            ->select('damage_products.id as dmg_id', 'damage_products.date as date','damage_products.dmg_inv as dmg_inv',
            'products.product_name as pname', 'products.id as pid',
            'damage_products.qnt as qnt', 'damage_products.price as price','damage_products.total as total')
            // ->join('suppliers', 'damage_products.sid', 'suppliers.id')
            ->join('products', 'damage_products.pid', 'products.id')
            ->whereBetween('date', [$stdate, $enddate])->get();

        $damage->map(function($damage){
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('dmg_inv', $damage->dmg_inv)
                ->where('product_id', $damage->pid)
                ->pluck('serial')->toArray();
            if($serials){
                $serials = implode (", ", $serials);
                $damage->serial = $serials;
            }else{
                $damage->serial = '';
            }
            return $damage;
        });

        return DataTables()->of($damage)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $action = '<a data-id='.$row->dmg_id.' data-invoice='.$row->dmg_inv.' title="delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete_damage_product(Request $req){

        $id = $req['id'];
        $invoice = $req['invoice'];

        $damage_products = DB::table('damage_products')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $id)->get();

        foreach($damage_products as $row){

            $pid = $row->pid;

            $qnt = $row->qnt;

            $get_products = DB::table('products')->where('client_id',auth()->user()->client_id)
                ->where('id', $pid)->first();

            $stock = $get_products->stock;

            $stock = ($stock + $qnt);

            DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $pid)->update(['stock' => $stock]);
        }

        $abc = DB::table('damage_products')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $id)->delete();

        dd($abc);

    }

    public function get_purchase_invoice_details(Request $req){

        $s_text = $req['s_text'];

        $get_invoice = DB::table('purchase_details')->join('products', 'purchase_details.pid', 'products.id')
            ->where('purchase_details.client_id',auth()->user()->client_id)
            ->where('purchase_details.pur_inv', '=', $s_text)->get();
        $trow = "";

        foreach($get_invoice as $row){
            $gtotal=$row->total+$row->vat;
            $serials = Serial::where('client_id', auth()->user()->client_id)
                ->where('pur_inv', $row->pur_inv)
                ->where('product_id', $row->pid)->pluck('serial')->toArray();
            if($serials)
            {
                $serials = implode (", ", $serials);
                $trow .= '<tr><td>'.$row->product_name .'<br>'. $serials.'</td><td>'.$row->price.'</td><td>'.$row->box.'</td><td>'.$row->qnt.'</td><td>'.$row->vat.'</td><td>'.$row->total.'</td><td>'.$gtotal.'</td></tr>';
            }else{
                $trow .= "<tr><td>".$row->product_name."</td><td>".$row->price."</td><td>".$row->box."</td><td>".$row->qnt."</td><td>".$row->vat."</td><td>".$row->total."</td><td>".$gtotal."</td></tr>";
            }
        }

        $get_invoice = DB::table('purchase_primary')
            ->where('client_id',auth()->user()->client_id)
            ->where('pur_inv', '=', $s_text)->first();

        if($get_invoice->sid > 0){

            $get_cname = DB::table('suppliers')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', '=', $get_invoice->sid)->first();

            $supp_name = $get_cname->name;
            $supp_phone = $get_cname->phone;

        }else{

            $supp_name = "";
            $supp_phone = "";
        }

        $discount = $get_invoice->discount;
        $amount = $get_invoice->amount;
        $total = $get_invoice->total;
        $vat_amount = $get_invoice->vat_amount;
        $payment = $get_invoice->payment;
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
            "discount" => $discount,
            "amount" => $amount,
            "total" => $total,
            "vat_amount" => $vat_amount,
            "payment" => $payment,
            "date" => $date,
            "supp_name" => $supp_name,
            "supp_phone" => $supp_phone,
        );

        return json_encode($data);
    }

}


