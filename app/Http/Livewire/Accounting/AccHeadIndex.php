<?php

namespace App\Http\Livewire\Accounting;

use App\AccHead;
use Livewire\Component;

class AccHeadIndex extends Component
{
    public $parent_head;
    public $sub_head;
    public $head;
    public $all_subhead = [];
    public $new_subhead;
    public $updateID;
    public $updateAccHead = false;

    public function mount()
    {
        $client_id = auth()->user()->client_id;
        $query = AccHead::where('client_id', $client_id)->distinct()->pluck('sub_head');

        foreach ($query as $q) {
            array_push($this->all_subhead, $q);
        }
    }


    public function store()
    {
        $data = $this->validate([
            'parent_head' => 'required',
            'sub_head' => 'required',
            'head' => 'required',
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['client_id'] = auth()->user()->client_id;

        AccHead::insert($data);

        session()->flash('success', 'Account Head successfully Inserted.');

        $this->emit('accHeadAdded');

        $this->resetInputField();
    }

    public function resetInputField()
    {
        $this->parent_head = '';
        $this->sub_head = '';
        $this->head = '';
    }

    public function edit($acc_head_id)
    {
        $acc_head = AccHead::find($acc_head_id);
        $this->parent_head = $acc_head->parent_head;
        $this->sub_head = $acc_head->sub_head;
        $this->head = $acc_head->head;

        $this->updateID = $acc_head->id;
        $this->updateAccHead = true;
    }

    public function update()
    {
        $data = $this->validate([
            'parent_head' => 'required',
            'sub_head' => 'required',
            'head' => 'required',
        ]);

        AccHead::where('id', $this->updateID)->update($data);

        session()->flash('success', 'Account Head successfully Updated.');

        $this->updateAccHead = false;
        $this->resetInputField();
    }

    public function destroy($acc_head_id)
    {
        AccHead::where('id', $acc_head_id)->delete();

        session()->flash('danger', 'Account Head successfully Deleted.');
    }

    public function addSubHead()
    {
        $this->dispatchBrowserEvent('openSubHeadModal');
    }

    public function newSubhead()
    {
        $this->validate([
            'new_subhead' => 'required'
        ]);

        array_push($this->all_subhead, $this->new_subhead);

        $this->sub_head = $this->new_subhead;
        $this->new_subhead = '';

        $this->dispatchBrowserEvent('closeSubHeadModal');
    }

    public function render()
    {
        $client_id = auth()->user()->client_id;
        $acc_heads = AccHead::where('client_id', $client_id)->get();
        return view('livewire.accounting.acc-head-index', compact('acc_heads'));
    }

    public function fillDemoData()
    {
        $items = [];
        $user_id = auth()->user()->id;
        $client_id = auth()->user()->client_id;

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Asset',
            'sub_head' => 'Current Asset',
            'head' => 'Cash In Hand',
        ];
        
        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Income',
            'sub_head' => 'Direct Income',
            'head' => 'Sales',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => 'Indirect Expense',
            'head' => 'Sales Return',
        ];
        
        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => 'Direct Expense',
            'head' => 'Purchase',
        ];
        
        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Income',
            'sub_head' => 'Indirect Income',
            'head' => 'Purchase Return',
        ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Asset',
        //     'sub_head' => 'Current Asset',
        //     'head' => 'Cash',
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Asset',
        //     'sub_head' => 'Current Asset',
        //     'head' => "Temporary Investment",
        // ];


        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Asset',
        //     'sub_head' => 'Current Asset',
        //     'head' => 'Insurance',
        // ];


        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Income',
        //     'sub_head' => 'Revenue',
        //     'head' => 'Loan Insurance',
        // ];
        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Income',
        //     'sub_head' => 'Revenue',
        //     'head' => 'Service Charge',
        // ];


        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Income',
        //     'sub_head' => 'Revenue',
        //     'head' => 'Loan Interest',
        // ];


        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Income',
        //     'sub_head' => 'Revenue',
        //     'head' => 'Loan Form',
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Expense',
        //     'sub_head' => 'Expense',
        //     'head' => 'DPS Interest',
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Expense',
        //     'sub_head' => 'Expense',
        //     'head' => 'FDR Interest',
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Asset',
        //     'sub_head' => 'FixedAsset',
        //     'head' => "Furniture",
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Asset',
        //     'sub_head' => 'FixedAsset',
        //     'head' => "Investment",
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Asset',
        //     'sub_head' => 'Accounts Receivable',
        //     'head' => "Member Loan",
        // ];

        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Liabilities',
        //     'sub_head' => 'Accounts Payable',
        //     'head' => "FDR",
        // ];
        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Liabilities',
        //     'sub_head' => 'Accounts Payable',
        //     'head' => "Savings",
        // ];
        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Liabilities',
        //     'sub_head' => 'Accounts Payable',
        //     'head' => "DPS",
        // ];
        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Owner Equity',
        //     'sub_head' => "Capital",
        //     'head' => "Capital A/C CEO",
        // ];
        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Owner Equity',
        //     'sub_head' => "Capital",
        //     'head' => "Capital A/C BONY",
        // ];
        // $items[] = [
        //     'user_id' => $user_id,
        //     'client_id' => $client_id,
        //     'parent_head' => 'Owner Equity',
        //     'sub_head' => "Withdrawn",
        //     'head' => "Withdrawn",
        // ];

        // AccHead::where('client_id', auth()->user()->client_id)->delete();

        foreach ($items as $item) 
        {
            AccHead::updateOrCreate(
                ['client_id' => $item['client_id'], 'parent_head' => $item['parent_head'], 'sub_head' => $item['sub_head'], 'head' => $item['head']],
                [
                    'user_id' => $user_id,
                ]
            );
        }
    }
}
