<?php

namespace App\Http\Livewire\Accounting;

use App\AccHead;
use Livewire\Component;
use App\AccTransaction;

class TrialBalanceIndex extends Component
{
    public $startDate, $endDate;
    public $trails = [];

    public function submit(): void
    {
        $this->validate([
            'startDate' => 'required',
            'endDate' => 'required'
        ]);
    }


    public function render()
    {
        $client_id = auth()->user()->client_id;
        $acc_heads = AccHead::where('client_id', $client_id)->get();

        foreach ($acc_heads as $head) {
            $ts = AccTransaction::where('client_id', $client_id)
                ->where('head', $head->head)->get();
            $debit = 0;
            $credit = 0;
            $bal = 0;
            foreach ($ts as $t) {
                $debit += $t->debit;
                $credit += $t->credit;
            }
            $bal = $debit - $credit;
            $this->trails[] = ['head' => $head->head, 'credit' => $credit, 'debit' => $debit, 'bal' => $bal];
        }
        return view('livewire.accounting.trial-balance-index');
    }
}
