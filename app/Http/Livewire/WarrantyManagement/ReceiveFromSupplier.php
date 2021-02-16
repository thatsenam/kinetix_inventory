<?php

namespace App\Http\Livewire\WarrantyManagement;

use App\Serial;
use App\Customer;
use App\Products;
use App\Supplier;
use Livewire\Component;
use App\WarrantyManagement;

class ReceiveFromSupplier extends Component
{
    public $vno;
    public $customers;
    public $customer_id;
    public $phone;
    public $date;
    public $old_serial;
    public $new_serial;
    public $serial_id;
    public $serials;
    public $product;
    public $products;
    public $product_id;
    public $supplier;
    public $suppliers;
    public $supplier_id;
    public $remarks;
    public $action_taken;
    public $vno_counting;
    public $newSerialInput = false;
    public $product_lists = [];

    public function mount()
    {
        $this->date = date('Y-m-d');

        $client_id = auth()->user()->client_id;
        
        $this->customers = Customer::where('client_id', $client_id)->get();
        $this->suppliers = Supplier::where('client_id', $client_id)->get();
        $this->serials = Serial::where('client_id', $client_id)->get();
        $this->products = Products::where('client_id', $client_id)->get();
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

    public function openModal()
    {
        $this->dispatchBrowserEvent('showSerialModal');
    }

    public function addNewSerial()
    {
        $this->newSerialInput = true;
        $this->dispatchBrowserEvent('hideSerialModal');
    }

    public function addProductList()
    {
        $this->validate([
            'date' => 'required',
            'vno' => 'required',
            'product_id' => 'required',
            'supplier_id' => 'required',
        ]);

        $warrantyQuery = WarrantyManagement::where('client_id', auth()->user()->client_id)
                            ->where('supplier_id', $this->supplier_id)
                            ->where('product_id', $this->product_id)
                            ->where('status', 'Send To Supplier')
                            ->get();

        foreach($warrantyQuery as $warranty)
        { 
            $old_serial = Serial::where('client_id', auth()->user()->client_id)
                ->find($warranty->serial_id)->serial;

            $this->product_lists[] = [
                'product_id' => $this->product_id,
                'product' => $this->product,
                'serial_id' => $warranty->serial_id,
                'old_serial' => $old_serial,
                'new_serial' => $this->new_serial,
                'action_taken' => $this->action_taken,
                'remarks' => $warranty->remarks,
                'customer_id' => $warranty->customer_id,
                'supplier_id' => $this->supplier_id,
                'supplier' => $this->supplier,
            ];
        }
        
        $this->product_id = '';
        $this->product = '';
        $this->serial_id = '';
        $this->serial = '';
        $this->supplier_id = '';
        $this->supplier = '';
        $this->newSerialInput = false;
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
            foreach ($this->product_lists as $list) 
            {
                if($list['new_serial'])
                {
                    $serial_id = $list['serial_id'];
                    Serial::where('client_id', auth()->user()->client_id)
                            ->find($serial_id)
                            ->update([
                                'serial' => $list['new_serial'],
                            ]);
                }
                $data = [
                    'vno' => $this->vno,
                    'supplier_id' => $list['supplier_id'],
                    'customer_id' => $list['customer_id'],
                    'product_id' => $list['product_id'],
                    // 'serial' => $list['new_serial'],
                    'serial_id' => $list['serial_id'],
                    'receive_from_supp' => $this->date,
                    'status' => 'Receive From Supplier',
                    'remarks' => $this->action_taken,
                    'user_id' => auth()->user()->id,
                    'client_id' => auth()->user()->client_id,
                ];
                WarrantyManagement::create($data);
            }
            session()->flash('success', 'Warranty Product Successfully Inserted.');

            $this->product_lists = [];
            // $this->resetAll();
        }
        else
        {
            session()->flash('danger', 'You Have to Make a List.');
        }
    }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');
        return view('livewire.warranty-management.receive-from-supplier');
    }
}
