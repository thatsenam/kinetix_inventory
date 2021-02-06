<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PurchaseProduct extends Component
{
    public $serial_qty = 0;
    public function render()
    {
        return view('livewire.purchase-product');
    }
}
