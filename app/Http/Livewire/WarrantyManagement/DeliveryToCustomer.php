<?php

namespace App\Http\Livewire\WarrantyManagement;

use App\Serial;
use App\Customer;
use App\Products;
use App\Supplier;
use Livewire\Component;
use App\WarrantyManagement;

class DeliveryToCustomer extends Component
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

    public function mount()
    {
        $this->date = date('Y-m-d');

        $client_id = auth()->user()->client_id;
        $this->vno_counting = WarrantyManagement::where('client_id', $client_id)
                                ->whereDate('created_at', date('Y-m-d'))
                                ->distinct()->count('vno');
        $this->vno = 'WDC-' . date('Ymd') . '-' . ($this->vno_counting + 1);

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

        $this->product = $serialQuery->product->product_name;
        $this->product_id = $serialQuery->product->id;

        $this->supplier = $serialQuery->supplier->name;
        $this->supplier_id = $serialQuery->supplier->id;
    }

    public function updatedProductID()
    {
        $productQuery = Products::where('client_id', auth()->user()->client_id)
                                ->find($this->product_id);
        $this->product = $productQuery->product_name;
    }

    public function updatedSupplierID()
    {   
        $supplierQuery = Supplier::where('client_id', auth()->user()->client_id)
                                ->find($this->supplier_id);
        $this->supplier = $supplierQuery->name;
    }

    public function addProductList()
    {
        $this->validate([
            'date' => 'required',
            'customer_id' => 'required',
            'product_id' => 'required',
        ]);

        $warrantyQuery = WarrantyManagement::where('client_id', auth()->user()->client_id)
                            ->where('customer_id', $this->customer_id)
                            ->where('product_id', $this->product_id)
                            ->where('status', 'Receive From Supplier')->get();

        foreach($warrantyQuery as $warranty)
        {
            $serial = Serial::where('client_id', auth()->user()->client_id)
                ->find($warranty->serial_id)->serial;

            $this->product_lists[] = [
                'customer_id' => $this->customer_id,
                'supplier_id' => $warranty->supplier_id,
                'product_id' => $this->product_id,
                'product' => $this->product,
                'serial' => $serial,
                'serial_id' => $warranty->serial_id,
                'remarks' => $warranty->remarks,
            ];
        }              
        
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
                    'customer_id' => $list['customer_id'],
                    'product_id' => $list['product_id'],
                    'serial_id' => $list['serial_id'],
                    'supplier_id' => $list['supplier_id'],
                    'delivery_to_cus' => $this->date,
                    'status' => 'Delivery To Customer',
                    // 'remarks' => $this->remarks,
                    'user_id' => auth()->user()->id,
                    'client_id' => auth()->user()->client_id,
                ];
                WarrantyManagement::create($data);
            }
            session()->flash('success', 'Warranty Product Successfully Inserted.');

            // $this->resetAll();
            $this->product_lists = [];
            $this->vno_counting++;
            $this->vno = 'WDC-' . date('Ymd') . '-' . ($this->vno_counting + 1);
        }
        else
        {
            session()->flash('danger', 'You Have to Make a List.');
        }
    }
    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');
        return view('livewire.warranty-management.delivery-to-customer');
    }
}
