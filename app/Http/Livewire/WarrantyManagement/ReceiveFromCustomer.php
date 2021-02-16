<?php

namespace App\Http\Livewire\WarrantyManagement;

use App\Serial;
use App\Customer;
use App\Products;
use App\Supplier;
use App\SalesInvoice;
use App\GeneralSetting;
use Livewire\Component;
use App\WarrantyManagement;

class ReceiveFromCustomer extends Component
{
    public $vno;
    public $customers;
    public $customer_id;
    public $phone;
    public $date;
    public $serial;
    public $serial_id;
    public $serials;
    public $product;
    public $products;
    public $product_id;
    public $supplier;
    public $suppliers;
    public $supplier_id;
    public $remarks;
    public $vno_counting;
    public $product_lists = [];
    public $rePrint = false;
    public $reprint_check;
    public $receipt;
    public $printArray = [];
    public $printCustomerName;                            
    public $printCustomerPhone;                            
    public $printDate;                         
    public $printVNO;   

    public function mount()
    {
        $this->date = date('Y-m-d');

        $client_id = auth()->user()->client_id;
        $this->vno_counting = WarrantyManagement::where('client_id', $client_id)
                                    ->whereDate('created_at', date('Y-m-d'))
                                    ->distinct()->count('vno');
        $this->vno = 'WRC-' . date('Ymd') . '-' . ($this->vno_counting + 1);

        $this->customers = Customer::where('client_id', $client_id)->get();
        $this->suppliers = Supplier::where('client_id', $client_id)->get();
        $this->serials = Serial::where('client_id', $client_id)->get();
        $this->products = Products::where('client_id', $client_id)->get();
    }

    public function updatedSerialID()
    {
        $serialQuery = Serial::where('client_id', auth()->user()->client_id)
                                ->find($this->serial_id);

        $this->serial = $serialQuery->serial;
        $this->serial_id = $serialQuery->id;
                                
        $this->product = $serialQuery->product->product_name;
        $this->product_id = $serialQuery->product->id;

        $this->supplier = $serialQuery->supplier->name;
        $this->supplier_id = $serialQuery->supplier->id;
    }

    public function updatedSupplierID()
    {   
        $supplierQuery = Supplier::where('client_id', auth()->user()->client_id)
                                ->find($this->supplier_id);
        $this->supplier = $supplierQuery->name;
    }

    public function updatedProductID()
    {
        $productQuery = Products::where('client_id', auth()->user()->client_id)
                                ->find($this->product_id);
        $this->product = $productQuery->product_name;
    }

    public function addProductList()
    {
        $this->validate([
            'vno' => 'required',
            'date' => 'required',
            'customer_id' => 'required',
            // 'serial_id' => 'required',
        ]);

        $this->product_lists[] = [
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'product' => $this->product,
            'serial' => $this->serial,
            'serial_id' => $this->serial_id,
            'supplier_id' => $this->supplier_id,
            'supplier' => $this->supplier,
        ];

        $this->product_id = '';
        $this->product = '';
        $this->serial_id = '';
        $this->serial = '';
        $this->supplier_id = '';
        $this->supplier = '';
    }

    public function removeProductList($index)
    {
        unset($this->product_lists[$index]);
        array_values($this->product_lists);
    }

    public function store()
    {
        if (count($this->product_lists) > 0) 
        {
            foreach ($this->product_lists as $list) {
                $data = [
                    'vno' => $this->vno,
                    'supplier_id' => $list['supplier_id'],
                    'customer_id' => $list['customer_id'],
                    'product_id' => $list['product_id'],
                    // 'serial' => $list['serial'],
                    'serial_id' => $list['serial_id'],
                    'receive_from_cus' => $this->date,
                    'status' => 'Receive From Customer',
                    'remarks' => $this->remarks,
                    'user_id' => auth()->user()->id,
                    'client_id' => auth()->user()->client_id,
                ];
                WarrantyManagement::create($data);
            }
            session()->flash('success', 'Warranty Product Successfully Inserted.');

            $this->receipt = $this->vno;
            $this->Print();

            // $this->resetAll();
            $this->product_lists = [];
            $this->vno_counting++;
            $this->vno = 'WRC-' . date('Ymd') . '-' . ($this->vno_counting + 1);
        }
        else
        {
            session()->flash('danger', 'You Have to Make a List.');
        }
    }

    public function updatedReprintCheck()
    {
        if($this->reprint_check)
        {
            $this->rePrint = true;
        }
        else{
            $this->rePrint = false;
        }
    }

    public function Print()
    {
        $this->printArray = [];
        $warrantyQuery = WarrantyManagement::where('client_id', auth()->user()->client_id)->where('vno', $this->receipt)->get();

        foreach($warrantyQuery as $warranty)
        {
            $sales_inv = '';
            $sales_date = '';
            $product = '';
            $tillWarranty = '';
            
            $customer = Customer::where('client_id', auth()->user()->client_id)->find($warranty->customer_id);
            $product = Products::where('client_id', auth()->user()->client_id)->find($warranty->product_id);
            $serial = Serial::where('client_id', auth()->user()->client_id)->find($warranty->serial_id);
            
            if($serial)
            {
                $sales_inv = $serial->sale_inv;
                if($sales_inv)
                {
                    $sales_inv_query = SalesInvoice::where('client_id', auth()->user()->client_id)
                                    ->where('invoice_no', $sales_inv)->first();
                    $sales_date = date('Y-m-d', strtotime($sales_inv_query->date));
                    $warranty_month = $product->warranty;
                    $tillPeriod = $sales_date . ' + ' . $warranty_month . ' month';
                    $tillWarranty = date('Y-M-d', strtotime($tillPeriod));
                }
            }

            $this->printArray[] = [
                'sales_invoice' => $sales_inv,
                'sales_date' => $sales_date,
                'product' => $product->product_name,
                'tillwarranty' => $tillWarranty,
                'remarks' => $warranty->remarks,
            ];

            $this->printCustomerName = $customer->name;                            
            $this->printCustomerPhone = $customer->phone;                            
            $this->printDate = $warranty->receive_from_cus;                         
            $this->printVNO = $warranty->vno;                   
        }

        $this->dispatchBrowserEvent('printReceipt');
        
        // dd($warranty);
    }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');

        $receipts = WarrantyManagement::where('client_id', auth()->user()->client_id)
                    ->whereNotNull('vno')
                    ->where('vno', 'like', '%WRC%')
                    ->groupBy('vno')->get();

        $setting = GeneralSetting::where('client_id', auth()->user()->client_id)->first(); 

        return view('livewire.warranty-management.receive-from-customer', compact('receipts', 'setting'));
    }
}
