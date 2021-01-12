<?php

namespace App\Http\Controllers;

use App\AccTransaction;
use App\PurchaseDetails;
use App\PurchasePrimary;
use App\PurchaseReturns;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Products;
use App\ProductImage;
use App\Company;
use App\Manufacturer;
use App\Category;
use App\Filter;
use App\Seller;
use Image;
use Auth;

class PosPurchaseController extends Controller
{


    public function purchase_products(){
        return view('admin.pos.purchase.purchase_products');
    }

    public function get_purchase_products(Request $req){

        $s_text = $req['s_text'];

        $products = DB::table('products')
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

            <li tabindex='<?php echo $i; ?>' onclick='selectProducts("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $price; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-price='<?php echo $price; ?>'><?php echo $name; ?> </li>

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
            ->where('supp_inv', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

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

    public function save_purchase_products(Request $req){

        $fieldValues = json_decode($req['fieldValues'], true);

        $supp_name = $fieldValues['supp_name'];
        $supp_id = $fieldValues['supp_id'];
        $supp_memo = $fieldValues['supp_memo'];
        $discount = $fieldValues['discount'];
        $amount = $fieldValues['amount'];
        $payment = $fieldValues['payment'];
        $total = $fieldValues['total'];
        $date = $fieldValues['date'];
        $user = Auth::id();

        if($discount == ''){$discount = "0.00";}
        if($payment == ''){$payment = "0.00";}

        if($supp_id == 0){

            $maxsupid = (DB::table('suppliers')->max('id') + 1);

//            DB::table('suppliers')->insert([
            Supplier::create([
                'name' => $supp_name
            ]);

            $supp_id = $maxsupid;
        }

        $maxid = (DB::table('purchase_primary')->max('id') + 1);

        $pur_inv = "PUR-".$maxid;

//        DB::table('purchase_primary')->insert([
            PurchasePrimary::create([
            'pur_inv' => $pur_inv,
            'sid' => $supp_id,
            'supp_inv' => $supp_memo,
            'discount' => $discount,
            'amount' => $amount,
            'total' => $total,
            'payment' => $payment,
            'date' => $date,
            'user' => $user
        ]);


        $take_cart_items = json_decode($req['cartData'], true);

        $count = count($take_cart_items);

        for($i = 0; $i < $count;){

            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;
            $j4 = $i+4;


            if($take_cart_items[$j] == 0){

                $max_pid = (Products::max('id') + 1);

                $product = new Products;
                $product->id = $max_pid;
                $product->name = $take_cart_items[$j1];
                $product->price = $take_cart_items[$j2];
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

//            DB::table('purchase_details')->insert([
            PurchaseDetails::create([
                'pur_inv' => $pur_inv,
                'pid' => $pid,
                'qnt' => $take_cart_items[$j3],
                'price' => $take_cart_items[$j2],
                'total' => $take_cart_items[$j4],
                'user' => $user
            ]);


            $i = $i + 5;
        }

        //////Save to Accounts//////

        $supplier = DB::table('suppliers')->where('id', $supp_id)->first();


        if($payment > 0){

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Purchase";
            $description = "Purchase from Invoice ".$pur_inv;
            $credit = 0;
            $debit = $payment;

//            DB::table('acc_transactions')->insert([
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
                    'user' => $user,

            ]);

        }else{

            $vno = (DB::table('acc_transactions')->max('id') + 1);

            $head = "Purchase";
            $description = "Purchase from Invoice ".$pur_inv;
            $credit = 0;
            $debit = $total;

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

            $head = $supplier->name;
            $description = "Purchase";
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
                    'user' => $user,

            ]);
        }

    }


    public function purchase_return(){


        return view('admin.pos.purchase.purchase_return');
    }

    public function save_purchase_return (Request $req){

        $fieldValues = json_decode($req['fieldValues'], true);

        $supp_id = $fieldValues['supp_id'];
        $supp_memo = $fieldValues['supp_memo'];
        $total = $fieldValues['total'];
        $date = $fieldValues['date'];
        $user = Auth::id();

        $take_cart_items = json_decode($req['cartData'], true);

        $count = count($take_cart_items);

        for($i = 0; $i < $count;){

            $j = $i;
            $j1 = $i+1;
            $j2 = $i+2;
            $j3 = $i+3;
            $j4 = $i+4;


            $product = DB::table('products')->where('client_id',auth()->user()->client_id)->where('id', $take_cart_items[$j])->first();

            $stock = $product->stock;;
            $stock = ($stock - $take_cart_items[$j3]);

            DB::table('products')
                ->where('client_id',auth()->user()->client_id)
                ->where('id', $take_cart_items[$j])->update(['stock' => $stock]);

            $pid = $take_cart_items[$j];

//            DB::table('purchase_returns')->insert([
                PurchaseReturns::create([

                'date' => $date,
                'pur_inv' => $supp_memo,
                'pid' => $pid,
                'qnt' => $take_cart_items[$j3],
                'price' => $take_cart_items[$j2],
                'total' => $take_cart_items[$j4],
                'sid' => $supp_id,
                'user' => $user
            ]);


            $i = $i + 5;
        }

        //////Save to Accounts//////

        $supplier = DB::table('suppliers')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $supp_id)->first();

            $vno = (DB::table('acc_transactions')->max('id') + 1);

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
                    'user' => $user,

            ]);


            $head = $supplier->name;
            $description = "Purchase Return";
            $credit = 0;
            $debit = $total;

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

        return DataTables()->of($purchase)
        ->addIndexColumn()
        ->addColumn('action', function($row){
            $action = '<a data-id='.$row->pur_inv.' title="View Details" href="#" class="view mr-2"><span class="btn btn-xs btn-info"><i class="mdi mdi-eye"></i></span></a>
            <a data-id='.$row->pur_inv.' title="Delete" href="#" class="delete"><span class="btn btn-xs btn-danger"><i class="mdi mdi-delete"></i></span></a>';
            return $action;
        })
        ->rawColumns(['action'])
        ->make(true);

        // foreach($purchase as $pur){
        //     $trow .= "<tr><td>".$pur->date."</td><td class='purinv'>".$pur->pur_inv."</td><td>".$pur->name."</td><td class='supp_invoice'>".$pur->supp_inv."</td><td>".$pur->discount."</td>
        //     <td>".$pur->amount."</td><td>".$pur->payment."</td><td>".$pur->total."</td>
        //     <td><a title='Details' href='#' class='view mr-2'><span class='btn btn-xs btn-info'><i class='mdi mdi-eye'></i></span></a> <a title='Delete' href='#' class='delete'><span class='btn btn-xs btn-danger'><i class='mdi mdi-delete'></i></span></a></td>
        //     </tr>";
        // }
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

        $purchase = DB::table('purchase_returns')

            ->where('purchase_returns.client_id',auth()->user()->client_id)
        ->select('purchase_returns.id as retid', 'purchase_returns.date as date','purchase_returns.pur_inv as pur_inv','products.product_name as pname',
        'suppliers.name as sname', 'purchase_returns.qnt as qnt', 'purchase_returns.price as price','purchase_returns.total as total')
        ->join('suppliers', 'purchase_returns.sid', 'suppliers.id')
        ->join('products', 'purchase_returns.pid', 'products.id')
        ->whereBetween('date', [$stdate, $enddate])->get();

        $trow = "";

       foreach($purchase as $pur){
            $trow .= "<tr><td class='retid'>".$pur->retid."</td><td>".$pur->date."</td><td class='invoice'>".$pur->pur_inv."</td>
            <td>".$pur->sname."</td><td>".$pur->pname."</td><td>".$pur->qnt."</td><td>".$pur->price."</td><td>".$pur->total."</td>
            <td><a title='Delete' href='#' class='delete'><span class='btn btn-xs btn-danger'><i class='mdi mdi-delete'></i></span></a></td>
            </tr>";
        }

        return $trow;
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

    public function get_purchase_invoice_details(Request $req){

        $s_text = $req['s_text'];

        $get_invoice = DB::table('purchase_details')->join('products', 'purchase_details.pid', 'products.id')
            ->where('purchase_details.client_id',auth()->user()->client_id)

            ->where('purchase_details.pur_inv', '=', $s_text)->get();

        $trow = "";

        foreach($get_invoice as $row){

            $trow .= "<tr><td>".$row->product_name."</td><td>".$row->price."</td><td>".$row->qnt."</td><td>".$row->total."</td></tr>";

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
            "payment" => $payment,
            "date" => $date,
            "supp_name" => $supp_name,
            "supp_phone" => $supp_phone,
        );

        return json_encode($data);
    }

}


