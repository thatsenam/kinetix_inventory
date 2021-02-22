<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\AccTransaction;
use App\BankInfo;
use App\BankTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CostEntryIndex extends Component
{
    use WithFileUploads;

    public $cash=0;
    public $remarks;
    public $pay_amount=0;
    public $card_type='';
    public $tranxid='';
    public $mobile_bank='';
    public $check_date='';
    public $check_type='';
    public $check_no='';
    public $clients_bank_acc='';
    public $clients_bank='';
    public $bank='';
    public $bank_acc='';
    public $voucher_type;
    public $vno;
    public $date;
    public $head;
    public $description;
    public $type='Cash';
    public $amount;
   
    public $note;
    public $isDisable = 'disabled';
    public $voucher_lists = [];
    public $vno_counting;
    public $totalDebit = 0;
    public $totalCredit = 0;
    public $error = null;
    public $bank_infos=[];
    public $banks=[];
    public $test;
    protected $listeners = ['accHeadAdded' => 'render','bank_add'];

    public function mount()
    {
        
        $this->date = date('Y-m-d');
        $client_id = auth()->user()->client_id;
        $this->vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))
            ->where('client_id', $client_id)->distinct()->count('vno');
        $this->vno = date('Ymd') . '-' . ($this->vno_counting + 1);
        $mobile_banks = BankInfo::where('type','mobile_bank')->get();
        foreach($mobile_banks as $bank){
            $this->bank_infos[]=$bank->name;
            $this->bank_infos[]=$bank->id;
            $this->bank_infos[]=$bank->account->id ?? '';
            $this->bank_infos[]=$bank->account->acc_no ?? '';
        }
        $mobile_banks = BankInfo::where('type','general_bank')->get();
        foreach($mobile_banks as $bank){
            $this->banks[]=$bank->name;
            $this->banks[]=$bank->id;
            $this->banks[]=$bank->account->id ?? '';
            $this->banks[]=$bank->account->acc_no ?? '';
        }
    }

    public function addVoucherList()
    {
        $this->validate([
            'voucher_type' => 'required',
            'vno' => 'required',
            'date' => 'required',
            'head' => 'required',
            'description' => '',
            'type' => 'required',
            'amount' => 'required',
        ]);
    }

    public function resetAll()
    {

        $this->head = '';
        $this->voucher_type = '';
        $this->amount = '';
        
        $this->description = '';
        $this->type = '';
        
        $this->note;
        $this->isDisable = 'disabled';
        $this->voucher_lists = [];
    }

    public function bank_add($acc,$name){
        $this->bank=$name;
        $this->bank_acc=$acc;
    }
    public function render()
    {
        $this->amount=$this->amount =='' ? 0 : $this->amount;

        $this->dispatchBrowserEvent('livewire:load');
        
        $client_id = auth()->user()->client_id;
        
        $all_heads = DB::table('acc_heads')->where('client_id', $client_id)->where('parent_head','Expense')->pluck('head');
        
        return view('livewire.accounting.cost-entry-index', compact('all_heads'));
    }
}
