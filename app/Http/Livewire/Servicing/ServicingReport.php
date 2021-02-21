<?php

namespace App\Http\Livewire\Servicing;

use App\Serial;
use App\Customer;
use App\Products;
use App\Servicing;
use App\GeneralSetting;
use Livewire\Component;

class ServicingReport extends Component
{
    public $status;
    public $startDate;
    public $endDate;
    public $customers;
    public $customer_id;
    public $serials = [];
    public $product;
    public $products;
    public $product_id;
    public $reports = [];

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');

        $client_id = auth()->user()->client_id;
        
        $this->customers = Customer::where('client_id', $client_id)->get();
        // $this->products = Products::where('client_id', $client_id)->get();
        
    }

    public function searchReport()
    {
        $this->reports = [];

        $this->validate([
            'startDate' => 'required',
            'endDate' => 'required',
            'product' => 'required',
            'customer_id' => 'required',
        ]);

        if($this->status)
        {
            $serviceQuery = Servicing::where('client_id', auth()->user()->client_id)
                            ->where('customer_id', $this->customer_id)
                            ->where('product', $this->product)
                            ->where('status', $this->status)->get();
        }
        else
        {
            $serviceQuery = Servicing::where('client_id', auth()->user()->client_id)
                            ->where('customer_id', $this->customer_id)
                            ->where('product', $this->product)->get();
        }

        foreach($serviceQuery as $service)
        {
            $customer = Customer::where('client_id', auth()->user()->client_id)->find($service->customer_id)->name;

            $this->reports[] = [
                'date' => $service->created_at,
                'vno' => $service->vno,
                'product' => $service->product,
                'customer' => $customer,
                'status' => $service->status,
                'problems' => $service->problems,
                'remarks' => $service->remarks,
            ];
        }
    }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');

        $this->products = Servicing::where('client_id', auth()->user()->client_id)
                        ->orderby('product', 'asc')
                        ->select('product')
                        ->groupBy('product')->get();

        $setting = GeneralSetting::where('client_id', auth()->user()->client_id)->first();

        return view('livewire.servicing.servicing-report', compact('setting'));
    }
}
