<?php

namespace App\Http\Livewire\WarrantyManagement;

use App\Customer;
use App\GeneralSetting;
use App\Serial;
use App\Products;
use App\Supplier;
use Livewire\Component;
use App\WarrantyManagement;

class WarrantyReport extends Component
{
    public $status;
    public $startDate;
    public $endDate;
    public $serial;
    public $serial_id;
    public $serials = [];
    public $products;
    public $product_id;
    public $reports = [];

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');

        $client_id = auth()->user()->client_id;
        
        // $this->serials = Serial::where('client_id', $client_id)->get();
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

        // $this->supplier = $serialQuery->supplier->name;
        // $this->supplier_id = $serialQuery->supplier->id;
    }

    public function updatedProductID()
    {
        $productQuery = Products::where('client_id', auth()->user()->client_id)
                                ->find($this->product_id);
        
        $this->serials = Serial::where('client_id', auth()->user()->client_id)
            ->where('product_id', $this->product_id)->get();
    }

    public function searchReport()
    {
        $this->reports = [];
        $this->validate([
            'startDate' => 'required',
            'endDate' => 'required',
            'product_id' => 'required',
            'serial_id' => 'required',
        ]);

        if($this->status)
        {
            $warrantyQuery = WarrantyManagement::where('client_id', auth()->user()->client_id)
                            ->where('serial_id', $this->serial_id)
                            ->where('product_id', $this->product_id)
                            ->where('status', $this->status)->get();
        }
        else
        {
            $warrantyQuery = WarrantyManagement::where('client_id', auth()->user()->client_id)
                            ->where('serial_id', $this->serial_id)
                            ->where('product_id', $this->product_id)->get();
        }
        foreach($warrantyQuery as $warranty)
        {
            $product = Products::where('client_id', auth()->user()->client_id)->find($warranty->product_id)->product_name;
            $serial = Serial::where('client_id', auth()->user()->client_id)->find($warranty->serial_id)->serial;
            $supplier = Supplier::where('client_id', auth()->user()->client_id)->find($warranty->supplier_id)->name;
            $customer = Customer::where('client_id', auth()->user()->client_id)->find($warranty->customer_id)->name;
            $this->reports[] = [
                'date' => $warranty->created_at,
                'vno' => $warranty->vno,
                'product' => $product,
                'serial' => $serial,
                'supplier' => $supplier,
                'customer' => $customer,
                'status' => $warranty->status,
            ];
        }
    }

    // public function addProductList()
    // {
    //     $this->validate([
    //         'vno' => 'required',
    //         'date' => 'required',
    //         'customer_id' => 'required',
    //         // 'serial_id' => 'required',
    //     ]);

    //     $this->product_lists[] = [
    //         'customer_id' => $this->customer_id,
    //         'product_id' => $this->product_id,
    //         'product' => $this->product,
    //         'serial' => $this->serial,
    //         'serial_id' => $this->serial_id,
    //         'supplier_id' => $this->supplier_id,
    //         'supplier' => $this->supplier,
    //     ];

    //     $this->product_id = '';
    //     $this->product = '';
    //     $this->serial_id = '';
    //     $this->serial = '';
    //     $this->supplier_id = '';
    //     $this->supplier = '';
    // }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');
        $setting = GeneralSetting::where('client_id', auth()->user()->client_id)->first();
        return view('livewire.warranty-management.warranty-report', compact('setting'));
    }
}
