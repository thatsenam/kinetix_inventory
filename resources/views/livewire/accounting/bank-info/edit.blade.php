<div class="card">
    <div class="card-body">
        <form wire:submit.prevent = "update">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="name" class="col-sm-4 col-form-label text-dark">
                            Bank Name 
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input type="name" wire:model.lazy="name" placeholder="Bank Name" id="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="type" class="col-sm-4 col-form-label text-dark">
                            Type
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <select wire:model.lazy="type" class="custom-select @error('type') is-invalid @enderror" id="type">
                                <option selected>Choose...</option>
                                <option value="Mobile">Mobile</option>
                                <option value="Bank">Bank</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="acc_name" class="col-sm-4 col-form-label text-dark">
                            Account Name
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input type="acc_name" wire:model.lazy="acc_name" placeholder="Account Name" id="acc_name"
                                class="form-control @error('acc_name') is-invalid @enderror">
                            @error('acc_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="acc_number" class="col-sm-4 col-form-label text-dark">
                            Account Number
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input type="acc_number" wire:model.lazy="acc_number" placeholder="Account Number" id="acc_number"
                                class="form-control @error('acc_number') is-invalid @enderror">
                            @error('acc_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="card_name" class="col-sm-4 col-form-label text-dark">Card Name</label>
                        <div class="col-md-8">
                            <input type="card_name" wire:model.lazy="card_name" placeholder="Card Name" id="card_name"
                                class="form-control @error('card_name') is-invalid @enderror">
                            @error('card_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="balance" class="col-sm-4 col-form-label text-dark">Balance</label>
                        <div class="col-md-8">
                            <input type="balance" wire:model.lazy="balance" placeholder="Balance" id="balance"
                                class="form-control @error('balance') is-invalid @enderror">
                            @error('balance')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-info">UPDATE</button>
        </form>
    </div>
</div>