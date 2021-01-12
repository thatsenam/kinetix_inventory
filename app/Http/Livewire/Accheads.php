<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\AccHead;
use App\AccTransaction;
use Illuminate\Support\Facades\DB;
class Accheads extends Component
{
    public $accheads, $parent_head, $sub_head, $head, $head_id;
    public $updateMode = false;
    public $showDialog = false;
    public $subHeads = [];

// <livewirew:acced/>

    public function mount(){
        $headue = AccHead::all(['sub_head'])->toArray();
        foreach ($headue as $h) {
            array_push($this->subHeads, $h['sub_head']);
        }
        $this->subHeads = array_unique($this->subHeads);
        if (count($this->subHeads) > 0) {
            $this->sub_head = $this->subHeads[0];
        }
    }

    public function render(){
        $this->accheads = AccHead::where('cid',null)->latest()->get();
        return view('livewire.accheads');
    }

    private function resetInputFields(){
        $this->parent_head = '';
        $this->sub_head = '';
        $this->head = '';
    }

    public function store(){
        $validatedDate = $this->validate([
            'parent_head' => 'required',
            'sub_head' => 'required',
            'head' => 'required',
        ]);
  
        AccHead::create($validatedDate);
  
        session()->flash('message', 'Head Created Successfully.');
  
        $this->resetInputFields();
    }

    public function click(){
        $this->createMode = true;
    }

    public function refreshHeads(){
        $this->showDialog = true;
    }

    public function createsub(){
        array_push($this->subHeads, $this->sub_head);
        $this->sub_head = '';
        $this->showDialog = false;

        $this->sub_head = last($this->subHeads);
    }

    public function edit($id){
        $head = AccHead::findOrFail($id);
        $this->head_id = $id;
        $this->parent_head = $head->parent_head;
        $this->sub_head = $head->sub_head;
        $this->head = $head->head;
  
        $this->updateMode = true;
    }

    public function cancel(){
        $this->updateMode = false;
        $this->resetInputFields();
    }

    public function update(){
        $validatedDate = $this->validate([
            'parent_head' => 'required',
            'sub_head' => 'required',
            'head' => 'required',
        ]);
  
        $headup = AccHead::find($this->head_id);
        $headup->update([
            'parent_head' => $this->parent_head,
            'sub_head' => $this->sub_head,
            'head' => $this->head,
        ]);
  
        $this->updateMode = false;
  
        session()->flash('message', 'Head Updated Successfully.');
        $this->resetInputFields();
    }

    public function delete($id){
        AccHead::find($id)->delete();
        session()->flash('message', 'Head Deleted Successfully.');
    }
}
