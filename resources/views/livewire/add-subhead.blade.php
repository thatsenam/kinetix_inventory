<form>
    <div class="row">
        <div class="col-md-5">
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
        <div class="col-md-5">
            <div class="form-group">
                <label>Sub Head</label>
                <input type="text" wire:model="sub_head" class="form-control" required>
            </div>
            @error('sub_head') <span class="text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="col-md-2">
            <label>Create</label> <br>
            <button wire:click.prevent="createsub()" class="btn btn-success">Save Subhead</button>
        </div>
    </div>
</form>