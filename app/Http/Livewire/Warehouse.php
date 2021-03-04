<?php

namespace App\Http\Livewire;

use App\Warehouse as ModelsWarehouse;
use Livewire\Component;

class Warehouse extends Component
{
    public $warehouses, $name, $address, $phone, $city, $wid;
    public $updateMode = false;

    public function render()
    {
        $this->warehouses = ModelsWarehouse::all()->where('client_id',auth()->user()->client_id);
        return view('livewire.warehouse');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->address = '';
        $this->phone = '';
        $this->city = '';
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'address' => '',
            'phone' => '',
            // 'city' => 'nullable'
        ]);

        ModelsWarehouse::create($validatedDate);
        session()->flash('message', 'Warehouse Information Successfully Inserted!');
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $warhouse = ModelsWarehouse::where('id',$id)->first();
        $this->wid = $id;
        $this->name = $warhouse->name;
        $this->phone = $warhouse->phone;
        $this->address = $warhouse->address;
        $this->city = $warhouse->city;
    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();

    }

    public function update()
    {
        $validatedDate = $this->validate([
            'name' => 'required',
            'address' => '',
            'phone' => '',
            // 'city' => 'nullable',
        ]);

        if ($this->id) {
            $user = ModelsWarehouse::find($this->wid);
            $user->update([
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                // 'city' => $this->city
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Warehouse Information Successfully Updated!');
            $this->resetInputFields();
        }
    }

    public function delete($id)
    {
        if($id){
            ModelsWarehouse::where('id',$id)->delete();
            session()->flash('message', 'Warehouse Information Successfully Deleted!');
        }
    }
}
