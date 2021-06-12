<div>
    @section('title', 'Voucher Entry')

    <div class="content-wrapper">
        <h1>Voucher Entry</h1>
        @if (session('success'))
            <div class="alert alert-success font-weight-bold">
                {{ session('success') }}
            </div>
        @endif
        @if (session('danger'))
            <div class="alert alert-danger font-weight-bold">
                {{ session('danger') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-4 mx-auto">
                            <div class="form-group text-center">
                                <label for="voucher_type" class="font-weight-bold text-dark text-uppercase">Voucher
                                    Type</label>
                                <select wire:model="voucher_type" id="voucher_type"
                                        class="custom-select @error('voucher_type') is-invalid @enderror">
                                    <option selected>Choose...</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Debit">Debit</option>
                                    <option value="Journal">Journal</option>
                                    <option value="Opening">Opening Balance</option>
                                </select>
                                @error('voucher_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="vno" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Voucher
                                No.</label>
                            <div class="col-sm-8">
                                <input wire:model="vno" type="text"
                                       class="form-control @error('vno') is-invalid @enderror" id="vno"
                                       placeholder="Voucher No">
                                @error('vno')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="date" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Voucher
                                Date</label>
                            <div class="col-sm-8">
                                <input wire:model="date" type="date" class="form-control" id="date"
                                       placeholder="Voucher Date">
                                @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="head" class="col-sm-4 font-weight-bold text-dark text-uppercase">
                                Pick Head
                                <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                        data-target=".bd-example-modal-lg">+
                                </button>
                            </label>
                            <div class="col-sm-8">
                                <select wire:model="head" id="head"
                                        class="custom-select @error('head') is-invalid @enderror">
                                    <option></option>
                                    @foreach($all_heads as $all_head)
                                        <option value="{{ $all_head }}">{{ $all_head }}</option>
                                    @endforeach
                                </select>
                                @error('head')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="description"
                                   class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Description</label>
                            <div class="col-sm-8">
                                <input wire:model="description" type="text"
                                       class="form-control @error('description') is-invalid @enderror" id="description"
                                       placeholder="Description">
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="type" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Trans.
                                Type</label>
                            <div class="col-sm-8">
                                <select wire:model="type"
                                        {{ $isDisable }} class="custom-select @error('type') is-invalid @enderror"
                                        id="type">
                                    <option value="">Choose...</option>
                                    <option value="Credit">Credit</option>
                                    <option value="Debit">Debit</option>
                                </select>
                                @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="amount"
                                   class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Amount</label>
                            <div class="col-sm-8">
                                <input wire:model="amount" wire:keydown.enter.prevent="addVoucherList" type="number"
                                       data-toggle="tooltip" title="Press Enter" data-placement="top"
                                       class="form-control @error('amount') is-invalid @enderror" placeholder="Amount">
                                @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered my-3">
                        <thead>
                        <tr>
                            <th>Voucher No</th>
                            <th>Head</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Descriptions</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($voucher_lists as $i => $voucher_list)
                            <tr>
                                <td>{{ $voucher_list['vno'] }}</td>
                                <td>{{ $voucher_list['head'] }}</td>
                                <td>{{ $voucher_list['debit'] }}</td>
                                <td>{{ $voucher_list['credit'] }}</td>
                                <td>{{ $voucher_list['description'] }}</td>
                                <td>
                                    <button wire:click.prevent="removeVoucherList({{ $i }})"
                                            class="btn btn-xs btn-danger">Delete
                                    </button>
                                </td>
                            </tr>
                            @if($loop->last)
                                <tr>
                                    <td style="border:0px"></td>
                                    <td style="border:0px"></td>
                                    <td class="font-weight-bold">{{ $totalDebit }}</td>
                                    <td class="font-weight-bold">{{ $totalCredit }}</td>
                                    <td style="border:0px"></td>
                                    <td style="border:0px"></td>
                                </tr>
                            @endif
                        @empty

                        @endforelse
                        </tbody>
                    </table>
                    @if($error)
                        <div class="row">
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $error }}</strong>
                        </span>
                        </div>
                    @endif
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="image"
                                   class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Attachment</label>
                            <div class="col-sm-8">
                                <input wire:model.lazy="image" type="file"
                                       class="form-control @error('image') is-invalid @enderror" id="image"
                                       placeholder="image">
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="note" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Voucher
                                Notes</label>
                            <div class="col-sm-8">
                                <input wire:model.lazy="note" type="text"
                                       class="form-control @error('note') is-invalid @enderror" id="note"
                                       placeholder="Note">
                                @error('note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" wire:click.prevent="resetAll" class="btn btn-outline-primary rounded-0">
                            RESET
                        </button>
                        <button type="submit" wire:click.prevent="store" class="btn btn-success rounded-0">SUBMIT
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Account Head Modal --}}
@include('livewire.accounting.voucher-entry.modal')

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    window.addEventListener("livewire:load", function (event) {

        $('#head').select2({
            placeholder: 'Choose Head',
        });
        $(document).on('change', '#head', function (e) {
            @this.
            set('head', e.target.value);
        });

        // if( ! @this.voucher_type )
        // {
        //     $('#voucher_type').focus();
        // }else if( ! @this.head ){
        //     $('#head').select2('focus');
        // }
    });

</script>
