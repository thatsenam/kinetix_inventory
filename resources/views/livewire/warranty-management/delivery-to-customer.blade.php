<div>
    @section('title', 'Delivery To Customer')

    <div class="content-wrapper">
        <h3 class="ml-3">Delivery To Customer</h3>
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
                    </div>
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="SelectCustomer" class="col-sm-4 font-weight-bold text-dark text-uppercase">Customer Name</label>
                            <div class="col-sm-8">
                                <select wire:model="customer_id" id="SelectCustomer" class="custom-select w-100 @error('customer_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->phone }}</option>
                                    @endforeach
                                </select>
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
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button wire:click.prevent="addProductList" class="btn btn-primary">Add To List</button>
                    </div>

                    <table class="table table-sm table-bordered my-3">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Serial Number</th>
                                <th>Remarks / Problem</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product_lists as $i => $list)
                                <tr>
                                    <td>{{ $list['product'] }}</td>
                                    <td>{{ $list['serial'] }}</td>
                                    <td>{{ $list['remarks'] }}</td>
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
</div>


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    window.addEventListener("livewire:load", function (event) {

        $('#SelectCustomer').select2({
            placeholder: 'Select Customer',
        });
        $(document).on('change', '#SelectCustomer', function (e) {
            @this.set('customer_id', e.target.value);
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
        });

        if( ! @this.customer_id )
        {
            $('#SelectCustomer').select2('focus');
        }
        else if( ! @this.product_id ){
            $('#SelectProduct').select2('focus');
        }
    });

</script>
