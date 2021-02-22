<?php

namespace App\Http\Livewire\Accounting;

use App\AccHead;
use Livewire\Component;

class CostHeadIndex extends Component
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
        $query = AccHead::where('client_id', $client_id)->where('parent_head','Expense')->distinct()->pluck('sub_head');

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

        session()->flash('success', 'ব্যয়ের খাত যুক্ত হয়েছে.');

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

        session()->flash('success', 'খাত আপডেট হয়েছে.');

        $this->updateAccHead = false;
        $this->resetInputField();
    }

    public function destroy($acc_head_id)
    {
        AccHead::where('id', $acc_head_id)->delete();

        session()->flash('danger', 'খাত ডিলিট হয়েছে.');
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
        $acc_heads = AccHead::where('client_id', $client_id)->where('parent_head','Expense')->get();
        return view('livewire.accounting.cost-head-index', compact('acc_heads'));
    }
}
