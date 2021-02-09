<?php

namespace App\Http\Controllers;

use App\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\AccTransaction;
use App\AccHead;
use App\Product;
use App\ProductImage;
use App\Company;
use App\Manufacturer;
use App\Category;
use App\Filter;
use App\Seller;
use App\Helpers\Functions;
use Image;
use Auth;

class PosController extends Controller
{

    public function index(){

        $client_id = auth()->user()->id;
        $user_id = Auth::id();
        
        $get_license = DB::table('users')
            ->where('client_id',auth()->user()->client_id)
            ->where('id', $user_id)->first();
        $license = '';
        if(!is_null($get_license)){
            $license = $get_license->license;
        }

        if($license != ''){
            $license = str_replace("-","", $license);
            $date_to = substr($license, 16, 26);
            $Functions = new Functions;
            $time = $Functions->makeNumber($date_to);
            $time = Date('Y-m-d', $time);
        }else{
            $time = "";
        }

        $yesterday = date('Y-m-d', strtotime('yesterday'));
        $today = date('Y-m-d');
        $lastday = date('Y-m-d', strtotime('-1 week'));
        $monthDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) );
        //DAILY
        $todaysPurchase = DB::table('purchase_primary')->where('client_id',auth()->user()->client_id)->where('date', $today)->sum('total');
        $todaysSales = DB::table('sales_invoice')->where('client_id',auth()->user()->client_id)->where('date', $today)->sum('gtotal');
        $todaysPurchasePayment = DB::table('purchase_primary')->where('client_id',auth()->user()->client_id)->where('date', $today)->sum('payment');
        $todaysSalesDue = DB::table('sales_invoice')->where('client_id',auth()->user()->client_id)->where('date', $today)->sum('due');
        // WEEKLY
        $weeklyPurchase = DB::table('purchase_primary')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$lastday, $today])->sum('total');
        $weeklySales = DB::table('sales_invoice')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$lastday, $today])->sum('gtotal');
        $weeklyPurchasePayment = DB::table('purchase_primary')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$lastday, $today])->sum('payment');
        $weeklySalesDue = DB::table('sales_invoice')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$lastday, $today])->sum('due');
        // MONTHLY
        $monthlyPurchase = DB::table('purchase_primary')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$monthDate, $today])->sum('total');
        $monthlySales = DB::table('sales_invoice')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$monthDate, $today])->sum('gtotal');
        $monthlyPurchasePayment = DB::table('purchase_primary')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$lastday, $today])->sum('payment');
        $monthlySalesDue = DB::table('sales_invoice')->where('client_id',auth()->user()->client_id)->whereBetween('date', [$monthDate, $today])->sum('due');

        $getSKU = DB::table('purchase_details')
            ->where('purchase_details.client_id', $client_id)
            ->select('purchase_details.pid','purchase_details.price','products.product_name')
            ->join('products','purchase_details.pid', 'products.id')
            ->groupBy('products.id')->get();

        $profitCalculation = DB::table('general_settings')->where('client_id', auth()->user()->client_id)
            ->pluck('profit_clc')->first();

        $Oldsold = 0;
        $oldPurchase = 0;
        $psold = 0;
        $pPurchase = 0;
        $returns = 0;
        $pur_return = 0;
        $sale_return = 0;
        $total_stock = 0;
        $stock_amount = 0;
        
        foreach($getSKU as $index=>$sku){
            // dd($sku);
            $pid = $sku->pid;
            $pPurchase = DB::table('purchase_details')->where('client_id', $client_id)
                        ->where('pid',$pid)->sum('qnt');
            $psold = DB::table('sales_invoice_details')->where('client_id', $client_id)
                        ->where('pid',$pid)->sum('qnt');
            $returns = DB::table('purchase_returns')->where('client_id', $client_id)
                        ->where('pid',$pid)->sum('qnt');
            $sale_return = DB::table('sales_return')->where('client_id', $client_id)
                        ->where('pid',$pid)->sum('qnt');
            $damage = DB::table('damage_products')->where('client_id',auth()->user()->client_id)
                        ->where('pid',$pid)->sum('qnt');
            $qty = $pPurchase - $returns - $psold + $sale_return - $damage;
            $total_stock += $qty;
            
            if($profitCalculation == '2')
            {
                $avg_purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $pid)->latest()->first()->price;
            }else{
                $avg_purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                ->where('pid', $pid)->avg('price');
            }
            $stock_amount += $qty * $avg_purchase_price;
            $stock_amount = round($stock_amount, 2);
        }

        // Weekly Sales 7 Days
        $lastday = date('Y-m-d', strtotime('-6 days'));
        $WeekDayArray = array();
        $SaleByDateArray = array();

        for($i = 0; $i < 7; $i++ )
        {
            $date = $lastday . ' +' . $i . ' days';
            $day = date('Y-m-d', strtotime($date));
            $WeekDayArray[] = date('Y-M-d', strtotime($date));

            $SaleByDateArray[] = DB::table('sales_invoice')->where('client_id', $client_id)
                ->whereDate('date', $day)->sum('gtotal');
        }

        //////Get Monthly Sales//////
        $thisMonth = date('Y-M');
        $monthsArray = array();
        $SaleByMonthArray = array();
        for($i = 11; $i >= 0; $i-- )
        {
            $month = date('Y-M', strtotime($thisMonth . ' -' . $i . ' months'));
            $firstDay = date('Y-m-01', strtotime($month));
            $lastDay = date('Y-m-t', strtotime($month));
            $monthsArray[] = date('M', strtotime($thisMonth . ' -' . $i . ' months'));

            $SaleByMonthArray[] = DB::table('sales_invoice')->where('client_id', $client_id)
                ->whereDate('date', '>=', $firstDay)
                ->whereDate('date', '<=', $lastDay)
                ->sum('gtotal');
        }

        return view('admin.pos.index')->with(compact('todaysPurchase','todaysSales','todaysPurchasePayment','todaysSalesDue','weeklyPurchase','weeklySales','weeklyPurchasePayment','weeklySalesDue','monthlyPurchase','monthlySales','monthlyPurchasePayment','monthlySalesDue', 'total_stock', 'stock_amount', 'WeekDayArray','SaleByDateArray', 'monthsArray','SaleByMonthArray'));
    }

     public function pos_client_registration(){


        return view('admin.pos.settings.pos_client_registration');
    }

    public function get_seller(Request $req){

        $s_text = $req['s_text'];

        $get_pos_client = Sales::where('name', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='client-list sugg-list'>

        <?php $i = 1;

        foreach($get_pos_client as $row){

            $id = $row->id;
            $name = $row->name;
            $shop = $row->shop;
            $address = $row->address;
            $user_id = $row->user_id;

            $get_mobile = DB::table('users')->where('client_id',auth()->user()->client_id)->where('id', $user_id)->first();

            if(!is_null($get_mobile)){
                $mobile = $get_mobile->phone;
            }else{
                $mobile = "";
            }

            $i = $i + 1; ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectClient("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $mobile; ?>", "<?php echo $shop; ?>",
            "<?php echo $address; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-shop='<?php echo $shop; ?>' data-address='<?php echo $address; ?>'
             data-phone='<?php echo $mobile; ?>'><?php echo $name; ?> | <?php echo $shop; ?></li>

        <?php } ?>

        </ul>

        <?php

    }

    public function save_client_registration(Request $req){

        $seller_id = $req['client_id'];
        $name = $req['client_name'];
        $shop = $req['shop_name'];
        $phone = $req['mobile'];
        $address = $req['address'];
        $type = $req['client_type'];


        $get_client = DB::table('pos_client')->where('client_id',auth()->user()->client_id)->where('seller_id', $seller_id)->first();

        if(!is_null($get_client)){
            echo "This Seller is Allready Registered.";
            exit;
        }

        $Functions = new Functions;

        $time = strtotime(date('Y-m-d H:i:s'));

		$db_name = $Functions->makeAlpha($time);


		DB::table("pos_client")->insert([
		    "seller_id" => $seller_id,
		    "name" => $name,
		    "shop" => $shop,
		    "address" => $address,
		    "phone" => $phone,
		    "type" => $type,
		    "db_name" => $db_name,
		]);

    }

    public function pos_license_registration(){


        return view('admin.pos.settings.pos_license_registration');
    }

    public function get_pos_clients(Request $req){

        $s_text = $req['s_text'];

        $get_pos_client = DB::table('pos_client')->where('name', 'like', '%'.$s_text.'%')->limit(9)->get(); ?>

        <ul class='client-list sugg-list'>

        <?php $i = 1;

        foreach($get_pos_client as $row){

            $id = $row->id;
            $name = $row->name;
            $shop = $row->shop;
            $address = $row->address;
            $mobile = $row->phone;
            $db_name = $row->db_name;


            $i = $i + 1; ?>

            <li tabindex='<?php echo $i; ?>' onclick='selectClient("<?php echo $id; ?>", "<?php echo $name; ?>", "<?php echo $mobile; ?>", "<?php echo $shop; ?>",
            "<?php echo $address; ?>", "<?php echo $db_name; ?>");' data-id='<?php echo $id; ?>' data-name='<?php echo $name; ?>' data-shop='<?php echo $shop; ?>'
            data-address='<?php echo $address; ?>'
             data-phone='<?php echo $mobile; ?>' data-dbname='<?php echo $db_name; ?>'><?php echo $name; ?> | <?php echo $shop; ?></li>

        <?php } ?>

        </ul>

        <?php

    }

    public function save_license_registration(Request $req){

        $client_id = $req['client_id'];
        $name = $req['client_name'];
        $shop = $req['shop_name'];
        $phone = $req['mobile'];
        $address = $req['address'];
        $type = $req['client_type'];
        $period = $req['period'];
        $license_from = $req['license_from'];
        $db_name = $req['db_name'];


        $Functions = new Functions;
		$parts_dbname = substr($db_name, 4, 6);

		$date_from = $Functions->makeAlpha(strtotime($license_from));

		$date_to = $Functions->makeAlpha(strtotime(date('Y-m-d', strtotime("+".$period." months", strtotime($license_from)))));

		$get_lkey = $parts_dbname.$date_from.$date_to;

    	$str_to_insert = "-";

    	$lkey1 = substr_replace($get_lkey, $str_to_insert, 4, 0);

    	$lkey2 = substr_replace($lkey1, $str_to_insert, 9, 0);

    	$lkey3 = substr_replace($lkey2, $str_to_insert, 14, 0);

    	$lkey4 = substr_replace($lkey3, $str_to_insert, 19, 0);

    	$lkey5 = substr_replace($lkey4, $str_to_insert, 24, 0);


		DB::table("pos_client")->where('id', $client_id)->update([

		    "name" => $name,
		    "shop" => $shop,
		    "address" => $address,
		    "phone" => $phone,
		    "type" => $type,
		    "license" => $lkey5
		]);

    }

    public function voucherEntry(){
        $vno = AccTransaction::max('vno');
        $subhead = DB::table('acc_heads')->distinct()->get('sub_head');
        $heads = DB::table('acc_heads')->get('head');
        $accheads = AccHead::all();
        return view('admin.pos.voucher-entry')->with(compact('vno','subhead','heads','accheads'));
    }

    public function savevoucher(Request $request){

        $getTableArray = $request->TableArrayHid;

        $TableArray = explode(",", $getTableArray);

        $voucher_type = $request->voucher_type;

        $notes = $request->vnotes;

        $date = $request->vdate;

        $count = count($TableArray);

        $vno = (AccTransaction::max('vno') + 1);

         for($i = 0; $i < $count;){

            $head = $TableArray[$i+1];
            $debit = $TableArray[$i+2];
            $credit = $TableArray[$i+3];
            $description = $TableArray[$i+4];

            if($credit == ''){
                $credit = '0.00';
            }

            if($debit == ''){
                $debit = '0.00';
            }

            if($voucher_type == "Credit"){

                $transaction = new AccTransaction;

                $transaction->vno = $vno;
                $transaction->head = $head;
                $transaction->notes = $notes;
                $transaction->debit = $credit;
                $transaction->description = $description;
                $transaction->date = $date;

                $transaction->save();

                $transaction = new AccTransaction;

                $transaction->vno = $vno;
                $transaction->head = "Cash In Hand";
                $transaction->notes = $notes;
                $transaction->credit = $credit;
                $transaction->description = $description;
                $transaction->date = $date;

                $transaction->save();

            }else if($voucher_type == "Debit"){

                $transaction = new AccTransaction;


                $transaction->vno = $vno;
                $transaction->head = $head;
                $transaction->notes = $notes;
                $transaction->credit = $debit;
                $transaction->description = $description;
                $transaction->date = $date;

                $transaction->save();

                $transaction = new AccTransaction;

                $transaction->vno = $vno;
                $transaction->head = "Cash";
                $transaction->notes = $notes;
                $transaction->debit = $debit;
                $transaction->description = $description;
                $transaction->date = $date;

                $transaction->save();

            }else if($voucher_type == "Journal"){

                if($debit != 0){

                    $transaction = new AccTransaction;

                    $transaction->vno = $vno;
                    $transaction->head = $head;
                    $transaction->notes = $notes;
                    $transaction->debit = $debit;
                    $transaction->description = $description;
                    $transaction->date = $date;

                    $transaction->save();
                }

                if($credit != 0){

                    $transaction = new AccTransaction;

                    $transaction->vno = $vno;
                    $transaction->head = "Cash";
                    $transaction->notes = $notes;
                    $transaction->credit = $credit;
                    $transaction->description = $description;
                    $transaction->date = $date;

                    $transaction->save();
                }

            }

            $i = $i + 6;
        }

        if($request->hasFile('attachment')){
                $image = $request->file('attachment');
                $img = time().'.'.$image->getClientOriginalExtension();
                $location = public_path('images/'.$img);
                Image::make($image)->save($location);

                $transaction->image = $img;
                $transaction->save();
        }

        $vno = (AccTransaction::max('vno')+1);

        return $vno;
    }

    public function savehead(Request $request){

        $acc_head = new AccHead;

        $parent_head = $request['parent_head'];

        $sel_subhead = $request['sel_subhead'];

        $add_subhead = $request['add_subhead'];

        $txt_head = $request['txt_head'];

        $subhead = "";

        if($sel_subhead != ""){
            $subhead = $sel_subhead;
        }else{
            $subhead = $add_subhead;
        }

        $acc_head->parent_head = $parent_head;
        $acc_head->sub_head = $subhead;
        $acc_head->head = $txt_head;

        $acc_head->save();

        return "<option value='$txt_head'>".$txt_head."</option>";

    }

}


