<form>
    <div class="row">
        <input type="hidden" wire:model="head_id">
        <div class="col-md-3">
            <div class="form-group">
                <label>Parent Head</label>
                <select class="form-control" wire:model="parent_head">
                    <option value="Income">Income</option>
                    <option value="Expense">Expense</option>
                    <option value="Asset">Asset</option>
                    <option value="Liabilities">Liabilities</option>
                </select>
            </div>
            @error('parent_head') <span class="text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label>Sub Head</label>
                <select class="form-control" wire:model="sub_head">
                    @foreach($subHeads as $subHead)
                        <option value="{{ $subHead }}" @if ($loop->first) selected @endif>{{ $subHead }}</option>
                    @endforeach
                </select>
            </div>
            <a href="#" wire:click="$set('showDialog',true)"><i class="fa fa-plus"></i>Add Subhead</a>
            @error('sub_head') <span class="text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Head</label>
                <input wire:model="head" type="text" class="form-control" placeholder="Enter head..">
            </div>
            @error('head') <span class="text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-2">
            <label>Update</label> <br>
            <button wire:click.prevent="update()" class="btn btn-success">Update</button>
        </div>
    </div>
</form>