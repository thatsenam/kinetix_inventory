<div class="card text-dark">
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-3">
                    <label for="parent_head" class="font-weight-bold">Parent Head</label>
                    <select wire:model.lazy="parent_head" id="parent_head" class="custom-select @error('parent_head') is-invalid @enderror">
                        <option selected>Choose...</option>
                        <option value="Asset">Asset</option>
                        <option value="Liabilities">Liabilities</option>
                        <option value="Owner Equity">Owner's Equity</option>
                        <option value="Income">Income</option>
                        <option value="Expense">Expense</option>
                    </select>
                    @error('parent_head')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="sub_head" class="font-weight-bold">Sub Head</label>
                    <div class="input-group">
                        <select wire:model.lazy="sub_head" id="sub_head" class="custom-select @error('sub_head') is-invalid @enderror">
                            <option selected>Choose...</option>
                            @foreach ($all_subhead as $subhead)
                                <option value="{{ $subhead }}">{{ $subhead }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button wire:click.prevent="addSubHead" class="btn btn-outline-primary" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('sub_head')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="head" class="font-weight-bold">Head</label>
                    <input type="text" wire:model.lazy="head" id="head"
                        class="form-control @error('head') is-invalid @enderror">
                    @error('head')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click.prevent="store" type="submit" class="btn btn-success rounded-0">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>