<?php

namespace App\Http\Livewire\Accounting;

use App\GeneralSetting;
use Livewire\Component;
use App\AccTransaction;

class CashBookIndex extends Component
{
    public $credits = [], $debits = [], $setting, $start_date = null, $end_date = null;

    public function mount()
    {
        $client_id = auth()->user()->client_id;
        $this->setting = GeneralSetting::where('client_id', $client_id)->first();

        $this->start_date = today()->startOfMonth()->toDateString();
        $this->end_date = today()->endOfMonth()->toDateString();
        $this->gatherCashbook();
    }

    public function gatherCashbook()
    {
        $client_id = auth()->user()->client_id;

        $this->debits = AccTransaction::where('client_id', $client_id)
            ->where('credit', '>', 0)
            ->where('head', '!=', 'Cash')
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->get();
        $this->credits = AccTransaction::where('client_id', $client_id)
            ->where('debit', '>', 0)
            ->where('head', '!=', 'Cash')
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->get();
    }

    public function render()
    {
        return view('livewire.accounting.cash-book-index');
    }
}
