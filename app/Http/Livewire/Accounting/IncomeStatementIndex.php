<?php

namespace App\Http\Livewire\Accounting;

use App\AccHead;
use App\GeneralSetting;
use Livewire\Component;
use App\AccTransaction;

class IncomeStatementIndex extends Component
{
    public $message = 'Works';
    public $transactions;
    public $setting;
    public $t_income, $t_expense, $income, $expense, $i = [], $e = [], $start_date = null, $end_date = null;
    public $profit = 0;

    public function resetFields()
    {

        $this->t_income = 0;
        $this->t_expense = 0;
        $this->income = 0;
        $this->expense = 0;
        $this->i = [];
        $this->e = [];
    }

    public function gatherProfitLoss()
    {
        $this->resetFields();

        $client_id = auth()->user()->client_id;
        $acc_heads = AccHead::where('client_id', $client_id)->get();

        foreach ($acc_heads as $acc_head) {

            $debit = AccTransaction::whereHead($acc_head->head)
                ->where('client_id', $client_id)
                ->whereDate('date', '>=', $this->start_date)
                ->whereDate('date', '<=', $this->end_date)->sum('debit');

            $credit = AccTransaction::whereHead($acc_head->head)
                ->where('client_id', $client_id)
                ->whereDate('date', '>=', $this->start_date)
                ->whereDate('date', '<=', $this->end_date)->sum('credit');

            if ($acc_head->parent_head == 'Income') {
                $this->i[$acc_head->head] = $credit;
            } else if ($acc_head->parent_head == 'Expense') {
                $this->e[$acc_head->head] = $debit;
            }
        }


        $iCount = count($this->i);
        $eCount = count($this->e);

        if ($eCount > $iCount) {
            $length = $eCount - $iCount;
            for ($a = 0; $a < $length; $a++) {
                $this->i[str_repeat(" ", $a)] = 0;
            }
        } else {
            $length = $iCount - $eCount;
            for ($a = 0; $a < $length; $a++) {
                $this->e[str_repeat(" ", $a)] = 0;
            }
        }
        krsort($this->i);
        krsort($this->e);
    }

    public function mount()
    {
        $this->start_date = today()->startOfMonth()->toDateString();
        $this->end_date = today()->endOfMonth()->toDateString();
        $client_id = auth()->user()->client_id;
        $this->setting = GeneralSetting::where('client_id', $client_id)->first();

        $this->gatherProfitLoss();
    }


    public function render()
    {
        $this->t_income = 0;
        $this->t_expense = 0;
        foreach ($this->i as $key => $value) {
            $this->t_income += $value;
        }
        foreach ($this->e as $key => $value) {
            $this->t_expense += $value;
        }

        $this->profit = $this->t_income - $this->t_expense;
        //        dd($this->i,$this->e);
        return view('livewire.accounting.income-statement-index');
    }
}
