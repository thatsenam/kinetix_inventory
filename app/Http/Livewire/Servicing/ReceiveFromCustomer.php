<?php

namespace App\Http\Livewire\Servicing;

use App\Serial;
use App\Customer;
use App\Products;
use App\Servicing;
use App\SalesInvoice;
use App\GeneralSetting;
use Livewire\Component;

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
    public $searchProduct;
    public $supplier;
    public $suppliers;
    public $supplier_id;
    public $remarks;
    public $vno_counting;
    public $product_lists = [];
    public $delivery_date;   
    public $isNewCustomer;   
    public $showNewCustomer;   
    public $new_customer_name;
    public $new_customer_phone;
    public $problems;
    public $rePrint = false;
    public $reprint_check;
    public $receipt;
    public $printArray = [];
    public $printCustomerName;                            
    public $printCustomerPhone;                            
    public $printDate;                         
    public $printVNO;   
    public $searchProductName;   
    public $searchProductID;   
    public $response = [];   


    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->delivery_date = date('Y-m-d');

        $client_id = auth()->user()->client_id;
        $this->vno_counting = Servicing::where('client_id', $client_id)
                                    ->whereDate('created_at', date('Y-m-d'))
                                    ->distinct()->count('vno');
        $this->vno = 'SRC-' . date('Ymd') . '-' . ($this->vno_counting + 1);

        $this->customers = Customer::where('client_id', $client_id)->get();
        $this->serials = Serial::where('client_id', $client_id)->get();
    }

    public function UpdatedIsNewCustomer()
    {
        $this->showNewCustomer = $this->isNewCustomer ? true : false;
    }

    public function updatedSerialID()
    {
        $serialQuery = Serial::where('client_id', auth()->user()->client_id)
                                ->find($this->serial_id);

        $this->serial = $serialQuery->serial;
        $this->serial_id = $serialQuery->id;
                                
        $this->product = $serialQuery->product->product_name;
        $this->product_id = $serialQuery->product->id;
    }

    public function UpdatedProduct()
    {
        $this->response = [];

        if($this->product == '')
        {
           $productsQuery = Products::where('client_id', auth()->user()->client_id)
                        ->orderby('product_name', 'asc')
                        ->select('id','product_name')->limit(5)->get();
        }
        else
        {
           $productsQuery = Products::where('client_id', auth()->user()->client_id)
                        ->orderby('product_name', 'asc')->select('id','product_name')
                        ->where('product_name', 'like', '%' . $this->searchProductName . '%')->limit(5)->get();
        }

        // $response = array();

        foreach($productsQuery as $product)
        {
           $this->response[] = array(
               'value' => $product->id,
               'label' => $product->product_name,
            );
        }
        // dd($this->product);
        $this->dispatchBrowserEvent('SearchProductLoad');
    }

    public function addProductList()
    {
        if( ! $this->customer_id && $this->showNewCustomer)
        {
            $this->validate([
                'new_customer_name' => 'required',
                // 'new_customer_phone' => 'required',
            ]);
            $newCustomer = Customer::create([
                'name' => $this->new_customer_name,
                'phone' => $this->new_customer_phone,
            ]);
            $this->customer_id = $newCustomer->id;
        }

        $this->validate([
            'vno' => 'required',
            'date' => 'required',
            'customer_id' => 'required',
            'product' => 'required',
            'problems' => 'required',
            // 'serial_id' => 'required',
        ]);

        $this->product_lists[] = [
            'customer_id' => $this->customer_id,
            'product_id' => $this->product_id,
            'product' => $this->product,
            'serial' => $this->serial,
            'serial_id' => $this->serial_id,
            'delivery_date' => $this->delivery_date,
            'problems' => $this->problems,
            'remarks' => $this->remarks,
            // 'supplier_id' => $this->supplier_id,
            // 'supplier' => $this->supplier,
        ];

        $this->product_id = '';
        $this->product = '';
        $this->serial_id = '';
        $this->serial = '';
        $this->problems = '';
        $this->remarks = '';
        // $this->supplier_id = '';
        // $this->supplier = '';
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
                    'customer_id' => $list['customer_id'],
                    'product_id' => $list['product_id'],
                    'product' => $list['product'],
                    'serial_id' => $list['serial_id'],
                    'receive_from_cus' => $this->date,
                    'delivery_date' => $list['delivery_date'],
                    'status' => 'Receive From Customer',
                    'remarks' => $list['remarks'],
                    'problems' => $list['problems'],
                    'user_id' => auth()->user()->id,
                    'client_id' => auth()->user()->client_id,
                ];
                Servicing::create($data);
            }
            session()->flash('success', 'Servicing Product Successfully Inserted.');

            $this->receipt = $this->vno;
            $this->Print();

            // $this->resetAll();
            $this->product_lists = [];
            $this->vno_counting++;
            $this->vno = 'SRC-' . date('Ymd') . '-' . ($this->vno_counting + 1);
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
        $servicingQuery = Servicing::where('client_id', auth()->user()->client_id)->where('vno', $this->receipt)->get();
        
        foreach($servicingQuery as $service)
        {
            $customer = Customer::where('client_id', auth()->user()->client_id)->find($service->customer_id);
            // $product = Products::where('client_id', auth()->user()->client_id)->find($service->product_id);
            $serial = Serial::where('client_id', auth()->user()->client_id)->find($service->serial_id);
            
            if($serial)
            {
                $serial = $serial->serial;
            }

            $this->printArray[] = [
                'delivery_date' => $service->delivery_date,
                'product' => $service->product,
                'serial' => $serial,
                'problems' => $service->problems,
                'remarks' => $service->remarks,
            ];

            $this->printCustomerName = $customer->name;                            
            $this->printCustomerPhone = $customer->phone;                            
            $this->printDate = $service->receive_from_cus;                         
            $this->printVNO = $service->vno;                   
        }

        $this->dispatchBrowserEvent('printReceipt');
        
        // dd($warranty);
    }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');

        $receipts = Servicing::where('client_id', auth()->user()->client_id)
                    ->whereNotNull('vno')
                    ->where('vno', 'like', '%SRC%')
                    ->groupBy('vno')->get();

        $setting = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('livewire.servicing.receive-from-customer', compact('setting', 'receipts'));
    }
}
