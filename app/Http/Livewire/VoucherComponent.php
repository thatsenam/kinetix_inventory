<?php

namespace App\Http\Livewire;

use App\Http\Livewire\helpers\VoucherHelper;
use Livewire\Component;
use App\AccHead;
use App\AccTransaction;
use Carbon\Carbon;
use Auth;

class VoucherComponent extends Component
{
    public $heads;
    public $voucherType, $head, $vno, $description, $transaction, $amount = 0, $image, $note, $debit = 0, $credit = 0, $date;
    public $transaction_items = [];

    public function mount(){
        $this->heads = AccHead::all();
        $mvno = AccTransaction::max('vno');
        $this->vno = $mvno+1;
        $this->date = Carbon::today()->toDateString();
    }
    public function updatedHeads(){
        $this->heads = AccHead::all();
        $this->head = last($this->heads);
    }

    public function updatedAmount(){
        //  dd($this->transaction);
        if ($this->transaction === "Debit") {
            $this->debit = $this->amount;
            $this->credit = 0;
        } elseif ($this->transaction === "Credit") {
            $this->credit = $this->amount;
            $this->debit = 0;
        } else {
            $this->credit = 0;
            $this->debit = 0;
        }
    }

    public function updatedVoucherType(){
        if ($this->voucherType === 'Journal') {
            $this->transaction = '';
            $this->amount = 0;
        } else {
            $this->transaction = $this->voucherType;
        }
        $this->updatedAmount();
    }

    public function save(){
    }

    public function addItem(){
        $this->validate([
            'description' => 'required',
            'amount' => 'required',
            'head' => 'required',
            'note' => 'required',
        ]);

        $helper = new VoucherHelper();
        $helper->vno = $this->vno;
        $helper->credit = $this->credit;
        $helper->debit = $this->debit;
        $helper->head = $this->head;
        $helper->description = $this->description;
        $helper->user = auth()->user()->id;
        $helper->note = $this->note;
        $helper->date = $this->date;
        $helper->voucherType = $this->voucherType;
        array_push($this->transaction_items, (array)$helper);
        $this->resetForm();
    }

    public function resetForm(){
        $this->voucherType = '';
        $this->head = '';
        $this->description = '';
        $this->note = '';
        $this->amount = '';
    }

    public function processToDatabase(){
        foreach ($this->transaction_items as $i) {
            if ($i['voucherType'] === "Journal") {
                $t1 = new AccTransaction;
                $t1->sort_by = $i['sort_by'];
                $t1->vno = $i['vno'];
                $t1->head = $i['head'];
                $t1->description = $i['description'];
                $t1->notes = $i['note'];
                $t1->user = $i['user'];

                if ($i['credit'] > $i['debit']) {
                    $t1->credit = $i['credit'];
                } else {
                    $t1->debit = $i['debit'];
                }
                $t1->date = $i['date'];
                $t1->save();
            } else {
                if ($i['voucherType'] === "Credit") {
                    $t1 = new AccTransaction;
                    $t1->sort_by = $i['sort_by'];
                    $t1->vno = $i['vno'];
                    $t1->head = $i['head'];
                    $t1->description = $i['description'];
                    $t1->notes = $i['note'];
                    $t1->user = $i['user'];
                    $t1->debit = $i['credit'];
                    $t1->date = $i['date'];
                    $t1->save();
                    $t1 = new AccTransaction;
                    $t1->sort_by = $i['sort_by'];
                    $t1->vno = $i['vno'];
                    $t1->head = 'Cash In Hand';
                    $t1->description = $i['description'];
                    $t1->notes = $i['note'];
                    $t1->user = $i['user'];

                    $t1->credit = $i['credit'];
                    $t1->date = $i['date'];
                    $t1->save();
                } else {
                    $t1 = new AccTransaction;
                    $t1->sort_by = $i['sort_by'];
                    $t1->vno = $i['vno'];
                    $t1->head = $i['head'];
                    $t1->description = $i['description'];
                    $t1->notes = $i['note'];
                    $t1->user = $i['user'];

                    $t1->credit = $i['debit'];
                    $t1->date = $i['date'];
                    $t1->save();
                    $t1 = new AccTransaction;
                    $t1->sort_by = $i['sort_by'];
                    $t1->vno = $i['vno'];
                    $t1->head = 'Cash';
                    $t1->description = $i['description'];
                    $t1->notes = $i['note'];
                    $t1->user = $i['user'];

                    $t1->debit = $i['debit'];
                    $t1->date = $i['date'];
                    $t1->save();
                }


            }
        }
        session()->flash('message', 'Voucher Created Successfully.');
        $this->transaction_items = [];

    }

    public function render(){
        return view('livewire.voucher-component');
    }
}
