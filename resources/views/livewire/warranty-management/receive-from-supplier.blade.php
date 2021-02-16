<div>
    @section('title', 'Receive From Supplier')

    <div class="content-wrapper">
        <h3 class="ml-3">Receive From Supplier</h3>
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
                        <div class="form-group col-md row">
                            <label for="date" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Date</label>
                            <div class="col-sm-8">
                                <input wire:model="date" type="date" class="form-control" id="date" placeholder="">
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="vno" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Voucher No.</label>
                            <div class="col-sm-8">
                                <input wire:model="vno" type="text" class="form-control @error('vno') is-invalid @enderror" id="vno" placeholder="Voucher No">
                                @error('vno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="SelectSupplier" class="col-sm-4 font-weight-bold text-dark text-uppercase">Supplier Name</label>
                            <div class="col-sm-8">
                                <select wire:model="supplier_id" id="SelectSupplier" class="custom-select @error('supplier_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }} {{ $supplier->phone }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group col-md row">
                            <label for="SelectProduct" class="col-sm-4 font-weight-bold text-dark text-uppercase">Product</label>
                            <div class="col-sm-8">
                                <select wire:model="product_id" id="SelectProduct" class="custom-select @error('product_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if($newSerialInput)
                            <div class="form-group col-md row">
                                <label for="new_serial" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">New Serial</label>
                                <div class="col-sm-8">
                                    <input wire:model="new_serial" type="text" class="form-control @error('new_serial') is-invalid @enderror" id="new_serial" placeholder="New Product Serial">
                                    @error('new_serial')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="col-md"></div>
                        @endif
                        <div class="form-group col-md row">
                            <label for="action_taken" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Action Taken</label>
                            <div class="col-sm-8">
                                <input wire:model.defer="action_taken" wire:keydown.enter.prevent="addProductList" type="text" 
                                    data-toggle="tooltip" title="Press Enter" data-placement="top"
                                    class="form-control @error('action_taken') is-invalid @enderror" placeholder="Action Taken">
                                @error('action_taken')
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
                                <th>Product</th>
                                <th>New Serial</th>
                                <th>Old Serial</th>
                                <th>Supplier</th>
                                <th>Action Taken</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product_lists as $i => $list)
                                <tr>
                                    <td>{{ $list['product'] }}</td>
                                    <td>{{ $list['new_serial'] }}</td>
                                    <td>{{ $list['old_serial'] }}</td>
                                    <td>{{ $list['supplier'] }}</td>
                                    <td>{{ $list['action_taken'] }}</td>
                                    <td>
                                        <button wire:click.prevent="removeProductList({{ $i }})" class="btn btn-xs btn-danger">Delete</button>
                                    </td>
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>

                    <div class="text-center mt-3">
                        <button type="submit" wire:click.prevent="store" class="btn btn-success rounded-0">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="newSerialModal" tabindex="-1" role="dialog" aria-labelledby="newSerialModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="newSerialModalLabel">New Replacement</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="addNewSerial">Yes</button>
                </div>
            </div>
            <div class="modal-footer">
            
            </div>
        </div>
        </div>
    </div>

</div>


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    window.addEventListener("showSerialModal", function (event) {
        $('#newSerialModal').modal('show');
    });
    window.addEventListener("hideSerialModal", function (event) {
        $('#newSerialModal').modal('hide');
    });

    window.addEventListener("livewire:load", function (event) {

        $('#SelectCustomer').select2({
            placeholder: 'Select Customer',
        });
        $(document).on('change', '#SelectCustomer', function (e) {
            @this.set('customer_id', e.target.value);
        });
        $('#SelectSupplier').select2({
            placeholder: 'Select Supplier',
        });
        $(document).on('change', '#SelectSupplier', function (e) {
            @this.set('supplier_id', e.target.value);
        });

        $('#SelectSerial').select2({
            placeholder: 'Select Serial',
        });
        $(document).on('change', '#SelectSerial', function (e) {
            @this.set('serial_id', e.target.value);
        });

        $('#SelectProduct').select2({
            placeholder: 'Select Product',
        });
        $(document).on('change', '#SelectProduct', function (e) {
            @this.set('product_id', e.target.value);
            @this.openModal();
        });

        if( ! @this.supplier_id )
        {
            $('#SelectSupplier').select2('focus');
        }
        else if( ! @this.product_id ){
            $('#SelectProduct').select2('focus');
        }
    });

</script>
