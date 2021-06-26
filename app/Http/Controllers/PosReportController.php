<?php

namespace App\Http\Controllers;

use App\AccTransaction;
use App\AdjustmentDetail;
use App\Category;
use App\Customer;
use App\DamageProduct;
use App\Products;
use App\PurchaseDetails;
use App\PurchasePrimary;
use App\PurchaseReturns;
use App\SalesInvoice;
use App\SalesInvoiceDetails;
use App\SalesReturn;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosReportController extends Controller
{
    public function generalLedger()
    {
        $heads = DB::table('acc_heads')->where('client_id', auth()->user()->client_id)->get();
        return view('admin.pos.general_ledger')->with(compact('heads'));
    }

    public function filter_data(Request $request)
    {
        if (request()->ajax()) {
            $id = $request->hid;
            if (!empty($request->from_date)) {
                $data = DB::table('acc_transactions')
                    ->where('client_id', auth()->user()->client_id)
                    ->where('sort_by', $id)->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get()->toArray();
            } else {
                $data = DB::table('acc_transactions')
                    ->where('client_id', auth()->user()->client_id)
                    ->where('sort_by', $id)->get()->toArray();
            }

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

    public function trials()
    {
        return view('admin.pos.trial_balance');
    }

    public function trialBalance(Request $request)
    {
        if (request()->ajax()) {
            if (!empty($request->from_date)) {
                $data = DB::table('acc_transactions')->select('head')
                    ->where('client_id', auth()->user()->client_id)
                    ->selectRaw('sum(debit) as debit')
                    ->selectRaw('sum(credit) as credit')
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->groupBy('head')->get();
            } else {
                $data = DB::table('acc_transactions')->select('head')
                    ->where('client_id', auth()->user()->client_id)
                    ->selectRaw('sum(debit) as debit')
                    ->selectRaw('sum(credit) as credit')
                    ->groupBy('head')->get();
            }
            $balance = 0;

            foreach ($data as $index => $d) {

                $balance = ($d->debit - $d->credit);

                $d->balance = ($balance);
            }
            return datatables()->of($data)->make(true);
        }
    }

    public function prevBalance(Request $request)
    {
        if (request()->ajax()) {
            $id = $request->hid;
            $debit = DB::table('acc_transactions')
                ->where('client_id', auth()->user()->client_id)
                ->where('sort_by', $id)->whereDate('date', '<=', $request->from_date)->sum('debit');
            $credit = DB::table('acc_transactions')
                ->where('client_id', auth()->user()->client_id)
                ->where('sort_by', $id)->whereDate('date', '<=', $request->from_date)->sum('credit');
            $prev_balance = $debit - $credit;
        }

        return $prev_balance;
    }


    public function lossProfit()
    {
        $lastTime = date('Y-m-d');
        $newtime = date('Y-m-d', strtotime('-1 month'));
        $beforeTime = date('Y-m-d', strtotime("$newtime -1 month"));


        $total_purchase = DB::table('purchase_primary')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('amount');

        $get_sales = DB::table('sales_invoice')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('gtotal');

        $totalSell_discount = DB::table('sales_invoice')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('discount');

        $totalSell_return = DB::table('sales_return')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('cash_return');

        $totalSCharge = DB::table('sales_invoice')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('scharge');

        $purchaseReturn = DB::table('purchase_returns')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('total');

        $purchaseDiscount = DB::table('purchase_primary')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('date', array($newtime, $lastTime))->sum('discount');

        $closing_purchase = DB::table('purchase_details')
            ->where('client_id', auth()->user()->client_id)
            ->whereBetween('created_at', array($beforeTime, $newtime))->sum('total');

        $get_pids = DB::table('sales_invoice_details')
            ->where('client_id', auth()->user()->client_id)
            ->select('pid')->whereBetween('created_at', array($beforeTime, $newtime))->get();

        $closingStock = 0;
        foreach ($get_pids as $pids) {
            $pid = $pids->pid;
            $qnt = DB::table('sales_invoice_details')
                ->where('client_id', auth()->user()->client_id)
                ->select('qnt')->where('pid', $pid)->whereBetween('created_at', array($beforeTime, $newtime))->sum('qnt');
            $latestPrice = DB::table('purchase_details')
                ->where('client_id', auth()->user()->client_id)
                ->orderBy('price', 'desc')->where('pid', $pid)->first();
            if (!$latestPrice) {
                continue;
            }

            $p = $latestPrice->price;
            // $q = floatval($qnt->qnt);
            $totalPrice = $p * $qnt;
            $closingStock += $totalPrice;
        }
        $getStoProfit = DB::table('sales_invoice_details')
            ->where('client_id', auth()->user()->client_id)
            ->select('pid')->groupBy('pid')->whereBetween('created_at', array($newtime, $lastTime))->get();
        // echo "<pre>"; print_r($getStoProfit);die;
        $purchaseTotal = 0;
        foreach ($getStoProfit as $sprofit) {
            $spid = $sprofit->pid;
            $sqnt = DB::table('sales_invoice_details')
                ->where('client_id', auth()->user()->client_id)
                ->select('qnt')->where('pid', $spid)->whereBetween('created_at', array($newtime, $lastTime))->sum('qnt');

            $slatestPrice = DB::table('purchase_details')
                ->where('client_id', auth()->user()->client_id)
                ->orderBy('price', 'desc')->where('pid', $spid)->first();
            $sp = $slatestPrice->price;
            $stotalp = $sp * $sqnt;
            $purchaseTotal += $stotalp;
        }
        // dd($purchaseTotal);

        $get_products = DB::table('sales_invoice_details')
            ->where('sales_invoice_details.client_id', auth()->user()->client_id)
            ->select('sales_invoice_details.pid', 'products.product_name', 'sales_invoice_details.price')
            ->join('products', 'sales_invoice_details.pid', 'products.id')
            ->groupBy('pid')
            ->whereBetween('sales_invoice_details.created_at', array($newtime, $lastTime))
            ->get();
        // dd($get_products);
        $grossProfitProduct = 0;
        foreach ($get_products as $index => $products) {
            $product_id = $products->pid;
            $saleprice = $products->price;
            $total_sold = DB::table('sales_invoice_details')
                ->where('client_id', auth()->user()->client_id)
                ->where('pid', $product_id)->whereBetween('created_at', array($newtime, $lastTime))->sum('qnt');
            $sold_total = $total_sold * $saleprice;
            $get_Pprice = DB::table('purchase_details')
//                ->where('client_id',auth()->user()->client_id)
                ->orderBy('price', 'desc')->where('pid', $product_id)->first();
            // if(!$latestPrice){
            //     continue;
            // }
            $pp = $get_Pprice->price;
            $pur_total = $pp * $total_sold;
            $products->grossProfitProduct = $sold_total - $pur_total;
        }

        $total = $closing_purchase - $closingStock;
        $opening = $total_purchase + $total;

        $get_customers = DB::table('sales_invoice')
            ->select('sales_invoice.cid', 'customers.name', 'sales_invoice_details.price', 'sales_invoice_details.pid')
            ->join('sales_invoice_details', 'sales_invoice_details.pid', 'sales_invoice_details.id')
            ->join('customers', 'sales_invoice.cid', 'customers.id')
            ->groupBy('cid')
            ->whereBetween('sales_invoice.date', array($newtime, $lastTime))
            ->get();

        // echo "<pre>"; print_r($get_customers);die;

        $profitByCustomer = 0;
        $purtotal = 0;
        foreach ($get_customers as $index => $customer) {
            $cpid = $customer->pid;
            $customer_id = $customer->cid;
            $csaletotal = DB::table('sales_invoice')
                ->where('client_id', auth()->user()->client_id)
                ->where('cid', $customer_id)->whereBetween('date', array($newtime, $lastTime))->sum('gtotal');
            $ctotalsold = DB::table('sales_invoice_details')
                ->where('client_id', auth()->user()->client_id)
                ->where('pid', $cpid)->whereBetween('created_at', array($newtime, $lastTime))->sum('qnt');

            $cpurchase_price = DB::table('purchase_details')
                ->orderBy('price', 'desc')->where('pid', $cpid)->first();
            // dd($customer_id,$cpid,$ctotalsold,$newtime,$lastTime);
            $cpp = $cpurchase_price->price ?? 0;
            $cpurtotal = $cpp * $ctotalsold;
            $purtotal += $cpurtotal;
            $customer->profitByCustomer = $csaletotal - $purtotal;
        }

        $getsales = DB::table('sales_invoice_details')
            ->where('sales_invoice_details.client_id', auth()->user()->client_id)
            ->select('sales_invoice_details.pid', 'products.product_name', 'sales_invoice_details.price', 'sales_invoice_details.created_at')
            ->join('products', 'sales_invoice_details.pid', 'products.id')
            ->groupBy('created_at')
            ->whereBetween('sales_invoice_details.created_at', array($newtime, $lastTime))
            ->get();
        $profitbydate = 0;
        $salekk = 0;
        foreach ($getsales as $index => $sales) {
            $id = $sales->pid;
            $date = $sales->created_at;
            $sprice = $sales->price;
            $quantity = DB::table('sales_invoice_details')
                ->where('client_id', auth()->user()->client_id)
                ->select('qnt')
                ->where('pid', $id)
                ->where('created_at', $date)
                ->sum('qnt');
            $ptotal = $quantity * $sprice;
            $sales->salekk = DB::table('sales_invoice_details')
                ->where('client_id', auth()->user()->client_id)
                ->select('total')
                ->where('created_at', $date)
                ->sum('total');

            $ppprice = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)->orderBy('price', 'desc')->where('pid', $id)->first();
            if (!$ppprice) {
                continue;
            }

            $ppp = $ppprice->price;
            $purhtotal = $ppp * $quantity;
            $sales->profitbydate = $ptotal - $purhtotal;
        }

        return view('admin.pos.reports.loss-profit')->with(compact('total_purchase', 'totalSell_discount', 'totalSell_return', 'get_sales', 'totalSCharge', 'purchaseReturn', 'purchaseDiscount', 'total', 'get_products', 'opening', 'get_customers', 'profitByCustomer', 'getsales', 'purchaseTotal'));
    }


    public function getStockOfProduct($product, $start_date, $end_date)
    {
        $totalProductPurchase = PurchaseDetails::query()
            ->where('pid', $product->id)
            ->get()
            ->reject(function ($purchase_details) use ($start_date, $end_date) {
                $p_primary = PurchasePrimary::query()->firstWhere('pur_inv', $purchase_details->pur_inv);
//                dd($p_primary);
                if ($end_date == null) {
                    if ($p_primary->date < $start_date) {
                        return false;
                    } else return true;
                } else {
                    if ($p_primary->date >= $start_date && $p_primary->date <= $end_date) {
                        return false;
                    } else return true;
                }


            })->sum('qnt');

        $totalProductSales = SalesInvoiceDetails::query()
            ->where('pid', $product->id)
            ->get()
            ->reject(function ($sales_details) use ($start_date, $end_date) {
                $p_primary = SalesInvoice::query()->firstWhere('invoice_no', $sales_details->invoice_no);
//                dd($p_primary);
                if ($end_date == null) {
                    if ($p_primary->date < $start_date) {
                        return false;
                    } else return true;
                } else {
                    if ($p_primary->date >= $start_date && $p_primary->date <= $end_date) {
                        return false;
                    } else return true;
                }


            })->sum('qnt');

        $totalPurchaseReturn = PurchaseReturns::query()
            ->where('pid', $product->id)
            ->when(true, function ($query) use ($start_date, $end_date) {
                if ($end_date == null) return $query->where('date', '<', $start_date);
                else return $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('qnt');

        $totalSalesReturn = SalesReturn::query()
            ->where('pid', $product->id)
            ->when(true, function ($query) use ($start_date, $end_date) {
                if ($end_date == null) return $query->where('date', '<', $start_date);
                else return $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('qnt');

        $totalDamage = DamageProduct::query()
            ->where('pid', $product->id)
            ->when(true, function ($query) use ($start_date, $end_date) {
                if ($end_date == null) return $query->where('date', '<', $start_date);
                else return $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('qnt');

        $totalAdjustment = AdjustmentDetail::query()
            ->where('pid', $product->id)
            ->when(true, function ($query) use ($start_date, $end_date) {
                if ($end_date == null) return $query->where('date', '<', $start_date);
                else return $query->whereBetween('date', [$start_date, $end_date]);
            });
        $totalAdjustment = $totalAdjustment->sum('add_qnt') - $totalAdjustment->sum('sub_qnt');


        $settings = DB::table('general_settings')->select('profit_clc')->first();


        $price = 0;
        if ($settings->profit_clc == 1) {
            $price = PurchaseDetails::query()->where('pid', $product->id)->avg('price');
        } else {
            $getsprice = PurchaseDetails::query()->select('price')->where('pid', $product->id)->latest()->first();
            $price = $getsprice->price ?? $product->price;
        }
        if ($product->id == 23) {
//            dd($settings,$price);

        }
        return ['total_purchase' => $totalProductPurchase,
            'total_sales' => $totalProductSales,
            'total_purchase_return' => $totalPurchaseReturn,
            'total_sales_return' => $totalSalesReturn,
            'total_damage' => $totalDamage,
            'total_adjustment' => $totalAdjustment,
            'stock_price' => $price
        ];

    }

    public function stockReport(Request $request)
    {
        $start_date = $request->start_date ?? today()->startOfMonth()->toDateString();
        $end_date = $request->end_date ?? today()->endOfMonth()->toDateString();
        $selected_category = $request->category;
        $selected_brand = $request->brand;

        /*
         * purchase_details [*][date filter]
         * sales_details [*]
         * purchase_return
         * sales_return
         * damage
         * adjustment
         *
         * */

        $products = Products::query()
            ->when($selected_category != null, function ($query) use ($selected_category) {
                return $query->where('cat_id', $selected_category);
            })->when($selected_brand != null, function ($query) use ($selected_brand) {
                return $query->where('brand_id', $selected_brand);
            })->get();
        foreach ($products as $product) {
            $stock = $this->getStockOfProduct($product, $start_date, $end_date); // Selected Date Stock [Start & End]
            $product->total_purchase = $stock['total_purchase'];
            $product->total_sales = $stock['total_sales'];
            $product->total_purchase_return = $stock['total_purchase_return'];
            $product->total_sales_return = $stock['total_sales_return'];
            $product->total_damage = $stock['total_damage'];
            $product->total_adjustment = $stock['total_adjustment'];
            $product->stock_price = $stock['stock_price'];
            $product->current_stock = (((($stock['total_purchase'] - $stock['total_sales']) - $stock['total_purchase_return']) + $stock['total_sales_return']) - $stock['total_damage']) + $stock['total_adjustment'];
            $opening = $this->getStockOfProduct($product, $start_date, null); // Opening Stock
            $product->opening_stock = (((($opening['total_purchase'] - $opening['total_sales']) - $opening['total_purchase_return']) + $opening['total_sales_return']) - $opening['total_damage']) + $opening['total_adjustment'];


        }


        $TotalSold = PurchaseDetails::query()->sum('total');
        $TotalPurchase = SalesInvoiceDetails::query()->sum('total');
        return view('admin.pos.reports.stock-report')->with(compact('products', 'TotalSold', 'TotalPurchase', 'start_date', 'end_date', 'selected_brand', 'selected_category'));
    }


    public function CashFlow(Request $request)
    {

        $cashflow = AccTransaction::where('head', "Cash In Hand")->where('client_id', auth()->user()->client_id)->whereDate('date', Carbon::today())->get();

        if (request()->ajax()) {
            if (!empty($request->from_date)) {
                $data = AccTransaction::where('head', "Cash In Hand")->where('client_id', auth()->user()->client_id)->whereBetween('date', array($request->from_date, $request->to_date))->get();
            } else {
                $data = AccTransaction::where('head', "Cash In Hand")->where('client_id', auth()->user()->client_id)->whereDate('date', Carbon::today())->get();
            }
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
        $balancef = 0;
        foreach ($cashflow as $key => $val) {
            if ($val->debit > 0) {
                $balancef = ($balancef + $val->debit);
            } else {
                $balancef = ($balancef - $val->credit);
            }
            $val->balancef = ($balancef);
        }
        return view('admin.pos.cash-flow')->with(compact('cashflow'));
    }

    public function SalesByCustomer(Request $request)
    {
        $customers = Customer::get();
        if (request()->ajax()) {
            $customer = $request->custID;
            $newtime = $request->from_date;
            $lastTime = $request->to_date;
            if (!empty($request->from_date)) {
                $data = DB::table('sales_invoice')
                    ->select('sales_invoice.invoice_no', 'sales_invoice.cid', 'sales_invoice.gtotal', 'sales_invoice.payment', 'sales_invoice.due', 'sales_invoice.date', 'customers.name')
                    ->join('customers', 'sales_invoice.cid', 'customers.id')
                    ->where('sales_invoice.client_id', auth()->user()->client_id)
                    ->where('cid', $customer)
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get();
            } else {
                $data = DB::table('sales_invoice')
                    ->select('sales_invoice.invoice_no', 'sales_invoice.cid', 'sales_invoice.gtotal', 'sales_invoice.payment', 'sales_invoice.due', 'sales_invoice.date', 'customers.name')
                    ->join('customers', 'sales_invoice.cid', 'customers.id')
                    ->where('sales_invoice.client_id', auth()->user()->client_id)
                    ->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.pos.reports.sales-customer')->with(compact('customers'));
    }

    public function SalesByProduct(Request $request)
    {
        $products = DB::table('sales_invoice_details')
            ->select('sales_invoice_details.pid', 'sales_invoice_details.invoice_no', 'sales_invoice_details.qnt', 'sales_invoice_details.price', 'sales_invoice_details.total', 'sales_invoice_details.vat', 'products.product_name', 'products.client_id')
            ->join('products', 'sales_invoice_details.pid', 'products.id')
            ->where('sales_invoice_details.client_id', auth()->user()->client_id)
            ->groupBy('product_name')
            ->get();


        if (request()->ajax()) {
            $pid = $request->pid;
            $newtime = $request->from_date;
            $lastTime = $request->to_date;
            if (!empty($request->from_date)) {
                $data = DB::table('sales_invoice_details')
                    ->select('sales_invoice_details.pid', 'sales_invoice_details.invoice_no', 'sales_invoice_details.qnt',
                        'sales_invoice_details.price', 'sales_invoice_details.total', 'sales_invoice_details.vat', 'products.product_name')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->where('sales_invoice_details.client_id', auth()->user()->client_id)
                    ->where('pid', $pid)
                    ->whereBetween('sales_invoice_details.created_at', array($request->from_date, $request->to_date))
                    ->get();
            } else {
                $data = DB::table('sales_invoice_details')
                    ->where('sales_invoice_details.client_id', auth()->user()->client_id)
                    ->select('sales_invoice_details.pid', 'sales_invoice_details.invoice_no', 'sales_invoice_details.qnt',
                        'sales_invoice_details.price', 'sales_invoice_details.total', 'sales_invoice_details.vat', 'products.product_name')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.pos.reports.sales-product')->with(compact('products'));
    }

    public function SalesByCategory(Request $request)
    {
        $categories = Category::get();
        $data = array();
        foreach ($categories as $cat) {
            $catid = $cat->id;
            $get_pids = DB::table('products')->where('client_id', auth()->user()->client_id)->where('cat_id', $catid)->get();
            foreach ($get_pids as $pid) {
                $productID = $pid->id;
                $getData = DB::table('sales_invoice_details')
                    ->select('sales_invoice_details.pid', 'sales_invoice_details.invoice_no', 'sales_invoice_details.qnt', 'sales_invoice_details.price', 'sales_invoice_details.total', 'products.product_name')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->where('pid', $productID)->get();
                foreach ($getData as $dx) {
                    $data[] = $dx;
                }
            }
        }
        if (request()->ajax()) {
            $catId = $request->cid;
            $newtime = $request->from_date;
            $lastTime = $request->to_date;
            if (!empty($request->from_date)) {
                $get_pids = DB::table('products')->where('client_id', auth()->user()->client_id)->where('cat_id', $catId)->get();
                $data = array();
                foreach ($get_pids as $pid) {
                    $productID = $pid->id;
                    $getData = DB::table('sales_invoice_details')
                        ->select('sales_invoice_details.pid', 'sales_invoice_details.invoice_no', 'sales_invoice_details.qnt', 'sales_invoice_details.price', 'sales_invoice_details.total', 'products.product_name')
                        ->join('products', 'sales_invoice_details.pid', 'products.id')
                        ->where('pid', $productID)->get();
                    foreach ($getData as $dx) {
                        $data[] = $dx;
                    }
                }
            } else {
                $categories = Category::get();
                $data = array();
                foreach ($categories as $cat) {
                    $catid = $cat->id;
                    $get_pids = DB::table('products')->where('client_id', auth()->user()->client_id)->where('cat_id', $catid)->get();
                    foreach ($get_pids as $pid) {
                        $productID = $pid->id;
                        $getData = DB::table('sales_invoice_details')
                            ->where('client_id', auth()->user()->client_id)
                            ->select('sales_invoice_details.pid', 'sales_invoice_details.invoice_no', 'sales_invoice_details.qnt', 'sales_invoice_details.price', 'sales_invoice_details.total', 'products.product_name')
                            ->join('products', 'sales_invoice_details.pid', 'products.id')
                            ->where('pid', $productID)->get();
                        foreach ($getData as $dx) {
                            $data[] = $dx;
                        }
                    }
                }
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.pos.reports.sales-category', compact('categories', 'data'));
    }

    public function PurchaseByProduct(Request $request)
    {
        $products = DB::table('purchase_details')
            ->where('purchase_details.client_id', auth()->user()->client_id)
            ->select('purchase_details.pid', 'purchase_details.pur_inv', 'purchase_details.qnt', 'purchase_details.price',
                'purchase_details.total', 'purchase_details.vat', 'products.product_name')
            ->join('products', 'purchase_details.pid', 'products.id')
            ->groupBy('product_name')->get();

        if (request()->ajax()) {
            $pid = $request->pid;
            $newtime = $request->from_date;
            $lastTime = $request->to_date;
            if (!empty($request->from_date)) {
                $data = DB::table('purchase_details')
                    ->where('purchase_details.client_id', auth()->user()->client_id)
                    ->select('purchase_details.pid', 'purchase_details.pur_inv', 'purchase_details.qnt', 'purchase_details.price',
                        'purchase_details.vat', 'purchase_details.total', 'products.product_name')
                    ->join('products', 'purchase_details.pid', 'products.id')
                    ->where('pid', $pid)
                    ->whereBetween('purchase_details.created_at', array($request->from_date, $request->to_date))
                    ->get();
            } else {
                $data = DB::table('purchase_details')
                    ->where('purchase_details.client_id', auth()->user()->client_id)
                    ->select('purchase_details.pid', 'purchase_details.pur_inv', 'purchase_details.qnt', 'purchase_details.price',
                        'purchase_details.total', 'purchase_details.vat', 'products.product_name')
                    ->join('products', 'purchase_details.pid', 'products.id')
                    ->get();
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.pos.reports.purchase-products')->with(compact('products'));
    }

    public function PurchaseBySupplier(Request $request)
    {
        $supliers = Supplier::get();
        if (request()->ajax()) {
            $supplier = $request->supID;
            $newtime = $request->from_date;
            $lastTime = $request->to_date;

            if (!empty($request->from_date)) {
                $data = DB::table('purchase_primary')
                    ->where('purchase_primary.client_id', auth()->user()->client_id)
                    ->select('purchase_primary.pur_inv', 'purchase_primary.sid', 'purchase_primary.total', 'purchase_primary.payment', 'purchase_primary.date', 'suppliers.name')
                    ->join('suppliers', 'purchase_primary.sid', 'suppliers.id')
                    ->where('sid', $supplier)
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get();
            } else {
                $data = DB::table('purchase_primary')
                    ->where('purchase_primary.client_id', auth()->user()->client_id)
                    ->select('purchase_primary.pur_inv', 'purchase_primary.sid', 'purchase_primary.total', 'purchase_primary.payment', 'purchase_primary.date', 'suppliers.name')
                    ->join('suppliers', 'purchase_primary.sid', 'suppliers.id')
                    ->get();
            }


//            dd($data);

            $due = 0;
            foreach ($data as $index => $d) {
                $due = ($d->total - $d->payment);
                $d->due = ($due);
            }
            return datatables()->of($data)->make(true);
        }
        return view('admin.pos.reports.purchase-supplier')->with(compact('supliers'));
    }
}
