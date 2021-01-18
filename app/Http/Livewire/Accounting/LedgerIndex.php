<?php

namespace App\Http\Livewire\Accounting;

use App\AccHead;
use App\GeneralSetting;
use Livewire\Component;
use App\AccTransaction;

class LedgerIndex extends Component
{
    public $startDate, $endDate, $setting, $previousBalance = 0, $selectedHead = "Choose your option";
    public $reports = [];
    public $heads = [];

    function mount()
    {
        $client_id = auth()->user()->client_id;
        $this->setting = GeneralSetting::where('client_id', $client_id)->first();
        $this->heads = AccHead::where('client_id', $client_id)->get();
    }

    public function submit(): void
    {
        if ($this->selectedHead === 'Choose your option') {
            return;
        }

        $this->validate([
            'startDate' => 'required',
            'endDate' => 'required'
        ]);

        $this->resetValues();

        $client_id = auth()->user()->client_id;

        $this->previousBalance = AccTransaction::where('client_id', $client_id)
            ->where('head', $this->selectedHead)
            ->whereDate('date', '<', $this->startDate)
            ->sum('debit') -
            AccTransaction::where('client_id', $client_id)
            ->where('head', $this->selectedHead)
            ->whereDate('date', '<', $this->startDate)
            ->sum('credit');

        $this->reports = AccTransaction::where('client_id', $client_id)
            ->where('head', $this->selectedHead)
            ->whereDate('date', '>=', $this->startDate)
            ->whereDate('date', '<=', $this->endDate)
            ->get();
        
        $this->mount();
    }

    public function resetValues()
    {
        $this->previousBalance = 0;
        $this->reports = [];
    }

    public function render()
    {
        return view('livewire.accounting.ledger-index');
    }
}
