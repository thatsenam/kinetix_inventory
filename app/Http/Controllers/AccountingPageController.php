<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingPageController extends Controller
{
    public function acc_head_index()
    {
        return view('accounting-pages.acc-head-index');
    }
    public function voucher_entry_index()
    {
        return view('accounting-pages.voucher-entry-index');
    }
    public function cost_entry_index()
    {
        return view('accounting-pages.cost-entry-index');
    }
    public function voucher_history_index()
    {
        return view('accounting-pages.voucher-history-index');
    }
    public function income_statement_index()
    {
        return view('accounting-pages.income-statement-index');
    }
    public function balance_sheet_index()
    {
        return view('accounting-pages.balance-sheet-index');
    }
    public function cash_book_index()
    {
        return view('accounting-pages.cash-book-index');
    }
    public function ledger_index()
    {
        return view('accounting-pages.ledger-index');
    }
    public function trial_balance_index()
    {
        return view('accounting-pages.trial-balance-index');
    }
}
