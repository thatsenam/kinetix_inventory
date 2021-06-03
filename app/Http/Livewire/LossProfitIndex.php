<?php

namespace App\Http\Livewire;

use App\Customer;
use App\Products;
use App\GeneralSetting;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LossProfitIndex extends Component
{
    public $startDate;
    public $endDate;
    public $radioOption;
    public $product_array = [];
    public $showInvoice = false;
    public $showProduct = false;
    public $showCustomer = false;
    public $invoices;
    public $products;
    public $customers;
    public $selected_invoice;
    public $selected_product;
    public $selected_customer;
    public $access;
    public $profitCalculation;
    public $qnt_footer;
    public $profit_footer;
    public $total_profit_footer;
    public $setting;

    public function render()
    {
        $this->dispatchBrowserEvent('LoadSelect2');

        if($this->access == '1')
        {
            $this->generalReport();
        }
        else if($this->access == '2')
        {
            $this->invoiceReport();
        }
        else if($this->access == '3')
        {
            $this->productReport();
        }
        else if($this->access == '4')
        {
            $this->customerReport();
        }
        $this->setting = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('livewire.loss-profit-index');
    }

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');

        $this->profitCalculation = DB::table('general_settings')->where('client_id', auth()->user()->client_id)
                                    ->pluck('profit_clc')->first();

        $this->invoices = DB::table('sales_invoice')->where('client_id', auth()->user()->client_id)->pluck('invoice_no');

        $this->products = Products::where('client_id', auth()->user()->client_id)->get();

        $this->customers = Customer::where('client_id', auth()->user()->client_id)->get();
    }

    public function updatedRadioOption()
    {
        if($this->radioOption == 'general')
        {
            $this->showInvoice = false;
            $this->showProduct = false;
            $this->showCustomer = false;
        }
        else if($this->radioOption == 'invoice')
        {
            $this->showInvoice = true;
            $this->showProduct = false;
            $this->showCustomer = false;
        }
        else if($this->radioOption == 'product')
        {
            $this->showProduct = true;
            $this->showInvoice = false;
            $this->showCustomer = false;
        }
        else if($this->radioOption == 'customer')
        {
            $this->showCustomer = true;
            $this->showInvoice = false;
            $this->showProduct = false;
        }
    }

    public function submit()
    {
        $this->product_array = [];

        if($this->radioOption == 'general')
        {
            $this->generalReport();
        }
        else if($this->radioOption == 'invoice')
        {
            $this->invoiceReport();
        }
        else if($this->radioOption == 'product')
        {
            $this->productReport();
        }
        else if($this->radioOption == 'customer')
        {
            $this->customerReport();
        }
    }

    public function generalReport()
    {
        $this->product_array = [];
        $this->access = '1';
        $this->qnt_footer = 0;
        $this->profit_footer = 0;
        $this->total_profit_footer = 0;
        $this->dispatchBrowserEvent('LoadDataTable');

        $this->product_array = DB::table('sales_invoice')->where('sales_invoice.client_id', auth()->user()->client_id)
                    ->select('date', 'pid', 'sales_invoice.invoice_no as invoice', 'product_name', 'qnt', 'price', 'total')
                    ->join('sales_invoice_details', 'sales_invoice.invoice_no', 'sales_invoice_details.invoice_no')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->whereDate('date', '>=', $this->startDate)
                    ->whereDate('date', '<=', $this->endDate)
                    ->get();

        $this->product_array->map(function($query){

            $product_id = $query->pid;
            $sale_price = $query->price;
            $qnt = $query->qnt;
            $this->qnt_footer += $qnt;

            if($this->profitCalculation == '2')
            {
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $product_id)->latest()->first()->price;
            }else{
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                ->where('pid', $product_id)->avg('price');
            }

            $profit = $sale_price - $purchase_price;
            $profit = round($profit, 2);
            $this->profit_footer += $profit;

            $total_profit = $profit * $qnt;
            $total_profit = round($total_profit, 2);
            $this->total_profit_footer += $total_profit;

            $query->profit = $profit;
            $query->sale_price = $sale_price;
            $query->purchase_price = $purchase_price;
            $query->total_profit = $total_profit;

            return $query;
        });
    }

    public function invoiceReport()
    {
        $this->product_array = [];
        $this->access = '2';
        $this->qnt_footer = 0;
        $this->profit_footer = 0;
        $this->total_profit_footer = 0;

        $this->product_array = DB::table('sales_invoice')->where('sales_invoice.client_id', auth()->user()->client_id)
                    ->select('date', 'pid', 'sales_invoice.invoice_no as invoice', 'product_name', 'qnt', 'price', 'total')
                    ->join('sales_invoice_details', 'sales_invoice.invoice_no', 'sales_invoice_details.invoice_no')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->whereDate('date', '>=', $this->startDate)
                    ->whereDate('date', '<=', $this->endDate)
                    ->where('sales_invoice.invoice_no', $this->selected_invoice)
                    ->get();

        $this->product_array->map(function($query){

            $product_id = $query->pid;
            $sale_price = $query->price;
            $qnt = $query->qnt;
            $this->qnt_footer += $qnt;

            if($this->profitCalculation == '2')
            {
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $product_id)->latest()->first()->price;
            }else{
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                ->where('pid', $product_id)->avg('price');
            }

            $profit = $sale_price - $purchase_price;
            $profit = round($profit, 2);
            $this->profit_footer += $profit;

            $total_profit = $profit * $qnt;
            $total_profit = round($total_profit, 2);
            $this->total_profit_footer += $total_profit;

            $query->sale_price = $sale_price;
            $query->purchase_price = $purchase_price;
            $query->profit = $profit;
            $query->total_profit = $total_profit;

            return $query;
        });
    }
    public function productReport()
    {
        $this->product_array = [];
        $this->access = '3';
        $this->qnt_footer = 0;
        $this->profit_footer = 0;
        $this->total_profit_footer = 0;

        $this->product_array = DB::table('sales_invoice')->where('sales_invoice.client_id', auth()->user()->client_id)
                    ->select('date', 'pid', 'sales_invoice.invoice_no as invoice', 'product_name', 'qnt', 'price', 'total')
                    ->join('sales_invoice_details', 'sales_invoice.invoice_no', 'sales_invoice_details.invoice_no')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->whereDate('date', '>=', $this->startDate)
                    ->whereDate('date', '<=', $this->endDate)
                    ->where('pid', $this->selected_product)
                    ->get();

        $this->product_array->map(function($query){

            $product_id = $query->pid;
            $sale_price = $query->price;
            $qnt = $query->qnt;
            $this->qnt_footer += $qnt;

            if($this->profitCalculation == '2')
            {
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $product_id)->latest()->first()->price;
            }else{
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                ->where('pid', $product_id)->avg('price');
            }

            $profit = $sale_price - $purchase_price;
            $profit = round($profit, 2);
            $this->profit_footer += $profit;

            $total_profit = $profit * $qnt;
            $total_profit = round($total_profit, 2);
            $this->total_profit_footer += $total_profit;
            $query->sale_price = $sale_price;
            $query->purchase_price = $purchase_price;
            $query->profit = $profit;
            $query->total_profit = $total_profit;

            return $query;
        });
    }
    public function customerReport()
    {
        $this->product_array = [];
        $this->access = '4';
        $this->qnt_footer = 0;
        $this->profit_footer = 0;
        $this->total_profit_footer = 0;

        $this->product_array = DB::table('sales_invoice')->where('sales_invoice.client_id', auth()->user()->client_id)
                    ->select('sales_invoice.date', 'sales_invoice_details.pid', 'sales_invoice.invoice_no as invoice', 'customers.name as cname', 'product_name',
                        'qnt', 'price', 'total')
                    ->join('sales_invoice_details', 'sales_invoice.invoice_no', 'sales_invoice_details.invoice_no')
                    ->join('products', 'sales_invoice_details.pid', 'products.id')
                    ->join('customers', 'sales_invoice.cid', 'customers.id')
                    ->whereDate('sales_invoice.date', '>=', $this->startDate)
                    ->whereDate('sales_invoice.date', '<=', $this->endDate)
                    ->where('customers.id', $this->selected_customer)
                    ->get();

        $this->product_array->map(function($query){

            $product_id = $query->pid;
            $sale_price = $query->price;
            $qnt = $query->qnt;
            $this->qnt_footer += $qnt;

            if($this->profitCalculation == '2')
            {
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                    ->where('pid', $product_id)->latest()->first()->price;
            }else{
                $purchase_price = DB::table('purchase_details')->where('client_id', auth()->user()->client_id)
                ->where('pid', $product_id)->avg('price');
            }

            $profit = $sale_price - $purchase_price;
            $profit = round($profit, 2);
            $this->profit_footer += $profit;

            $total_profit = $profit * $qnt;
            $total_profit = round($total_profit, 2);
            $this->total_profit_footer += $total_profit;
            $query->sale_price = $sale_price;
            $query->purchase_price = $purchase_price;
            $query->profit = $profit;
            $query->total_profit = $total_profit;

            return $query;
        });
    }
}
