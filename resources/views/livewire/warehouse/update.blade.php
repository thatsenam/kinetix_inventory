<form>
    <div class="form-group">
        <input type="hidden" wire:model="id">
        <label for="exampleFormControlInput1">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" wire:model="name">
        @error('name') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput2">Address</label>
        <input type="text" class="form-control" id="exampleFormControlInput2" wire:model="address" placeholder="Address">
        @error('address') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput3">Phone</label>
        <input type="phone" class="form-control" id="exampleFormControlInput3" wire:model="phone" placeholder="Phone">
        @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
    </div>
    <!-- <div class="form-group">
        <label for="exampleFormControlInput4">City</label>
        <input type="text" class="form-control" id="exampleFormControlInput4" wire:model="city" placeholder="City">
        @error('city') <span class="text-danger">{{ $message }}</span>@enderror
    </div> -->
    <button wire:click.prevent="update()" class="btn btn-dark">UPDATE</button>
    <button wire:click.prevent="cancel()" class="btn btn-danger">CANCEL</button>
</form>