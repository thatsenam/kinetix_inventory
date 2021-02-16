<div>
    @section('title', 'Send To Supplier')

    <div class="content-wrapper">
        <h3 class="ml-3">Send To Supplier</h3>
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
                                <select wire:model="supplier_id" id="SelectSupplier" class="custom-select w-100 @error('supplier_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }} {{ $supplier->phone }}</option>
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
                                <th>Supplier</th>
                                <th>Remarks / Problem</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product_lists as $i => $list)
                                <tr>
                                    <td>{{ $list['product'] }}</td>
                                    <td>{{ $list['serial'] }}</td>
                                    <td>{{ $list['supplier'] }}</td>
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
                <div class="form-group">
                    <div class="form-check">
                        <input wire:model="reprint_check" type="checkbox" class="form-check-input" id="reprintCheck">
                        <label class="form-check-label" for="reprintCheck">Reprint?</label>
                    </div>
                </div>

                @if($rePrint)
                    <div class="form-group col-md-6 row">
                        <label for="SelectReceipt" class="col-sm-4 font-weight-bold text-dark text-uppercase">Enter Receipt No</label>
                        <div class="col-sm-8">
                            <select wire:model="receipt" id="SelectReceipt" class="custom-select @error('receipt') is-invalid @enderror">
                                <option></option>
                                @foreach($receipts as $receipt)
                                    <option value="{{ $receipt->vno }}">{{ $receipt->vno }}</option>
                                @endforeach
                            </select>
                            <div class="mt-3 text-center">
                                <button wire:click.prevent="Print" class="btn btn-info">Print</button>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="printSection">
                    <div class="text-center mb-2" id="heading" style="display: none">
                        <h1>{{ $setting->site_name ?? ''  }}</h1>
                        <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                        <h4>Chalan Of Warranty Product</h4>

                        <table class="table table-bordered mb-2">
                            <tbody>
                                <tr>
                                    <th>Voucher No</th>
                                    <td>{{ $printVNO }}</td>
                                    <th>Supplier Name</th>
                                    <td>{{ $printSupplierName }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $printDate }}</td>
                                    <th>Phone</th>
                                    <td>{{ $printSupplierPhone }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-sm table-bordered my-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Serial</th>
                                    <th>Remarks / Problem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($printArray as $i => $array)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $array['product'] }}</td>
                                        <td>{{ $array['printSerial'] }}</td>
                                        <td>{{ $array['remarks'] }}</td>
                                    </tr>
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
        });

        $('#SelectReceipt').select2({
            placeholder: 'Select Receipt',
        });
        $(document).on('change', '#SelectReceipt', function (e) {
            @this.set('receipt', e.target.value);
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

<script>
    window.addEventListener("printReceipt", function (event) {
       
        var head = document.getElementById("heading");
        head.style.display = "block";
        head.style.color = "black";
        var printContents = document.getElementById("printSection").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();

    });
</script>