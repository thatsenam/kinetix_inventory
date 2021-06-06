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
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Asset',
            'sub_head' => 'Current Asset',
            'head' => "Temporary Investment",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Asset',
            'sub_head' => 'Fixed Asset',
            'head' => "Furniture",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Asset',
            'sub_head' => 'Fixed Asset',
            'head' => "Investment",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => "Owner's Equity",
            'sub_head' => "Capital",
            'head' => "Capital A/C Chairman",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Office Expense",
            'head' => "Electricity Bill",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Office Expense",
            'head' => "House Rent",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Office Expense",
            'head' => "Entertainment",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Operational Expense",
            'head' => "Transport Cost",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Operational Expense",
            'head' => "Labour Cost",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Operational Expense",
            'head' => "Other Cost",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Purchase",
            'head' => "Purchase",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Purchase",
            'head' => "Purchase Return",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Income',
            'sub_head' => "Sales",
            'head' => "Sales",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Income',
            'sub_head' => "Sales",
            'head' => "Sales Return",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Expense',
            'sub_head' => "Purchase",
            'head' => "Purchase Vat",
            'is_default' => '1',
        ];

        $items[] = [
            'user_id' => $user_id,
            'client_id' => $client_id,
            'parent_head' => 'Liabilities',
            'sub_head' => "Accounts Payable",
            'head' => "Sales Vat",
            'is_default' => '1',
        ];

        foreach ($items as $item)
        {
            AccHead::updateOrCreate(
                ['client_id' => $item['client_id'], 'parent_head' => $item['parent_head'], 'sub_head' => $item['sub_head'], 'head' => $item['head'],'is_default' => $item['is_default']],
                [
                    'user_id' => $user_id,
                ]
            );
        }
    }
}
