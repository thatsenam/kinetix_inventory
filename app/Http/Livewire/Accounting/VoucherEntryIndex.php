<?php

namespace App\Http\Livewire\Accounting;

use App\AccTransaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class VoucherEntryIndex extends Component
{
    use WithFileUploads;

    public $voucher_type;
    public $vno;
    public $date;
    public $head;
    public $description;
    public $type;
    public $amount;
    public $debit;
    public $credit;
    public $image;
    public $note;
    public $isDisable = 'disabled';
    public $voucher_lists = [];
    public $vno_counting;
    public $totalDebit = 0;
    public $totalCredit = 0;
    public $error = null;

    protected $listeners = ['accHeadAdded' => 'render'];

    public function mount()
    {
        $this->date = date('Y-m-d');
        $client_id = auth()->user()->client_id;
        $this->vno_counting = AccTransaction::whereDate('date', date('Y-m-d'))
            ->where('client_id', $client_id)->distinct()->count('vno');
        $this->vno = date('Ymd') . '-' . ($this->vno_counting + 1);
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

        if ($this->voucher_type === 'Journal') {
            if ($this->type === 'Debit') {
                $this->debit = $this->amount;
                $this->credit = 0;
            } else {
                $this->credit = $this->amount;
                $this->debit = 0;
            }
        } else if ($this->voucher_type === 'Opening') {
            if ($this->type === 'Debit') {
                $this->debit = $this->amount;
                $this->credit = 0;
            } else {
                $this->credit = $this->amount;
                $this->debit = 0;
            }
        } else {
            if ($this->type === 'Debit') {
                $this->debit = 0;
                $this->credit = $this->amount;
            } else {
                $this->credit = 0;
                $this->debit = $this->amount;
            }
        }


        $this->voucher_lists[] = [
            'vno' => $this->vno,
            'head' => $this->head,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'description' => $this->description,
        ];

        $this->totalDebit = $this->totalDebit + $this->debit;
        $this->totalCredit = $this->totalCredit + $this->credit;

        $this->head = '';
        $this->amount = '';
        $this->debit = 0;
        $this->credit = 0;
        $this->description = '';
    }

    public function removeVoucherList($index)
    {
        unset($this->voucher_lists[$index]);
        array_values($this->voucher_lists);
    }

    public function updatedVoucherType()
    {
        if ($this->voucher_type == 'Journal') {
            $this->isDisable = '';
            $this->type = '';
        } else if ($this->voucher_type == 'Opening') {
            $this->isDisable = '';
            $this->type = '';
        } else {
            $this->isDisable = 'disabled';
            $this->type = $this->voucher_type;
        }
    }

    public function store()
    {
        if ($this->voucher_type == 'Journal') {
            if ($this->totalDebit != $this->totalCredit) {
                $this->error = 'Debit & Credit are not Equal(=).';
                return;
            }
        }

        if (count($this->voucher_lists) > 0) {

            foreach ($this->voucher_lists as $voucher_list) {
                $data = [
                    'sort_by' => '1',
                    'vno' => $this->vno,
                    'head' => $voucher_list['head'],
                    'description' => $voucher_list['description'],
                    'debit' => $voucher_list['debit'],
                    'credit' => $voucher_list['credit'],
                    'date' => $this->date,
                    'user_id' => auth()->user()->id,
                    'client_id' => auth()->user()->client_id,
                ];

                if ($this->image) {
                    $imagePath = $this->image->store('Voucher_Attch', 'public');
                    $data['image'] = $imagePath;
                    $this->image = '';
                }
                if ($this->note) {
                    $data['note'] = $this->note;
                    $this->note = '';
                }

                AccTransaction::create($data);

                if ($this->voucher_type != 'Journal'&& $this->voucher_type != 'Opening') {
                    $second = [
                        'sort_by' => '1',
                        'vno' => $this->vno,
                        'head' => 'Cash',
                        'description' => $voucher_list['description'],
                        'debit' => $voucher_list['credit'],
                        'credit' => $voucher_list['debit'],
                        'date' => $this->date,
                        'user_id' => auth()->user()->id,
                        'client_id' => auth()->user()->client_id,
                    ];
                    AccTransaction::create($second);
                }
            }
            session()->flash('success', 'Voucher Successfully Inserted.');

            $this->resetAll();
            $this->vno_counting++;
            $this->vno = date('Ymd') . '-' . ($this->vno_counting + 1);
        }
    }

    public function resetAll()
    {

        $this->head = '';
        $this->voucher_type = '';
        $this->amount = '';
        $this->debit = 0;
        $this->credit = 0;
        $this->totalDebit = 0;
        $this->totalCredit = 0;
        $this->description = '';
        $this->type = '';
        $this->image;
        $this->note;
        $this->isDisable = 'disabled';
        $this->voucher_lists = [];
    }

    public function render()
    {
        $this->dispatchBrowserEvent('livewire:load');
        $client_id = auth()->user()->client_id;

        $all_heads = DB::table('acc_heads')->where('client_id', $client_id)->pluck('head');

        return view('livewire.accounting.voucher-entry-index', compact('all_heads'));
    }
}
