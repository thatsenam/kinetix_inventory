<?php

namespace App\Http\Livewire\Accounting;

use App\GeneralSetting;
use Livewire\Component;
use App\AccTransaction;
use Illuminate\Support\Facades\DB;

class VoucherHistoryIndex extends Component
{
    public $start;
    public $end;
    public $all_vouchers;
    public $voucher_no;
    public $voucher_entries = [];
    public $setting;
    public $singular = false;

    public function mount()
    {
        $client_id = auth()->user()->client_id;
        $this->all_vouchers = AccTransaction::where('client_id', $client_id)->distinct()->pluck('vno');
        $this->setting = GeneralSetting::where('client_id', $client_id)->first();
        $this->start = date('Y-m-d');
        $this->end = date('Y-m-d');
        $this->dateSearch();
    }

    public function dateSearch()
    {
        $this->voucher_entries = [];
        $this->singular = false;

        $client_id = auth()->user()->client_id;

        $vouchers = AccTransaction::where('client_id', $client_id)
            ->whereDate('date', '>=', $this->start)
            ->whereDate('date', '<=', $this->end)->distinct()->pluck('vno');

        foreach ($vouchers as $vn) {
            $query = AccTransaction::where('client_id', $client_id)
                ->where('vno', $vn)
                ->where('head', '!=', 'Cash')->get();
            foreach ($query as $q) {
                $this->voucher_entries[$vn][] = $q;
            }
        }
    }

    public function updatedVoucherNo()
    {
        $this->singular = true;
        $this->voucher_entries = [];

        $client_id = auth()->user()->client_id;

        $query = DB::table('acc_transactions')
            ->where('client_id', $client_id)
            ->where('vno', $this->voucher_no)
            ->where('head', '!=', 'Cash')->get();

        foreach ($query as $q) {
            $this->voucher_entries[$this->voucher_no][] = $q;
        }
    }

    public function destroy($vouch)
    {
        $client_id = auth()->user()->client_id;

        AccTransaction::where('client_id', $client_id)
            ->where('vno', $vouch)
            ->delete();

        session()->flash('danger', 'Voucher Successfully Deleted.');

        if ($this->singular) {
            $this->updatedVoucherNo();
        } else {
            $this->dateSearch();
        }
    }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');

        return view('livewire.accounting.voucher-history-index');
    }
}
