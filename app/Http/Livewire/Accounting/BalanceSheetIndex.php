<?php

namespace App\Http\Livewire\Accounting;

use App\AccHead;
use App\GeneralSetting;
use Livewire\Component;
use App\AccTransaction;

class BalanceSheetIndex extends Component
{
    public $assets = [];
    public $liabilities = [];
    public $equity = [];
    public $netProfit = 0;
    public $setting;

    public function mount()
    {
        $client_id = auth()->user()->client_id;
        $this->setting = GeneralSetting::where('client_id', $client_id)->first();

        $AssetHead = AccHead::where('client_id', $client_id)
            ->where('parent_head', 'Asset')->get();

        foreach ($AssetHead as $Head) {
            $accTrQuery = AccTransaction::where('client_id', $client_id)
                ->where('head', $Head->head);

            if (array_key_exists($Head->sub_head, $this->assets)) {
                $this->assets[$Head->sub_head] += $accTrQuery->sum('debit') - $accTrQuery->sum('credit');
            } else {
                $this->assets[$Head->sub_head] = $accTrQuery->sum('debit') - $accTrQuery->sum('credit');
            }
        }

        $LiabilitiesHead = AccHead::where('client_id', $client_id)
            ->where('parent_head', "Liabilities")->get();
        foreach ($LiabilitiesHead as $Head) {
            $accTrQuery = AccTransaction::where('client_id', $client_id)
                ->where('head', $Head->head);
            if (array_key_exists($Head->sub_head, $this->assets)) {
                $this->liabilities[$Head->sub_head] += $accTrQuery->sum('credit') - $accTrQuery->sum('debit');
            } else {
                $this->liabilities[$Head->sub_head] = $accTrQuery->sum('credit') - $accTrQuery->sum('debit');
            }
        }

        $OEHead = AccHead::where('client_id', $client_id)
            ->where('parent_head', 'Owner Equity')->get();
        foreach ($OEHead as $Head) {
            $accTrQuery = AccTransaction::where('client_id', $client_id)
                ->where('head', $Head->head);

            if (array_key_exists($Head->sub_head, $this->assets)) {
                $this->equity[$Head->sub_head] += $accTrQuery->sum('credit') - $accTrQuery->sum('debit');
            } else {
                $this->equity[$Head->sub_head] = $accTrQuery->sum('credit') - $accTrQuery->sum('debit');
            }
        }

        $totalIncome = 0;
        $totalExpense = 0;
        $IncomeHead = AccHead::where('client_id', $client_id)
            ->where('parent_head', 'Income')->get();
        foreach ($IncomeHead as $Head) {
            $accTrQuery = AccTransaction::where('client_id', $client_id)
                ->where('head', $Head->head);
            $totalIncome += $accTrQuery->sum('credit') - $accTrQuery->sum('debit');
        }

        $ExpenseHead = AccHead::where('client_id', $client_id)
            ->where('parent_head', 'Expense')->get();
        foreach ($ExpenseHead as $Head) {
            $accTrQuery = AccTransaction::where('client_id', $client_id)
                ->where('head', $Head->head);
            $totalExpense += $accTrQuery->sum('debit') - $accTrQuery->sum('credit');
        }
        $this->netProfit = $totalIncome - $totalExpense;
    }

    public function render()
    {
        return view('livewire.accounting.balance-sheet-index');
    }
}
