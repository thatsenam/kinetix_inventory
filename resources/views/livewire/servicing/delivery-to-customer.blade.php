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
                                <select wire:model="customer_id" id="SelectCustomer" class="custom-select @error('customer_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} {{ $customer->phone }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="searchProductName" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Product</label>
                            <div class="col-sm-8">
                                <input wire:model.debounce.500ms="product" id="searchProductName" class="form-control @error('product') is-invalid @enderror" autocomplete="off">
                                @error('product')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md row">
                            <label for="remarks" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">Remarks</label>
                            <div class="col-sm-8">
                                <textarea wire:model.defer="remarks" type="text" class="form-control @error('remarks') is-invalid @enderror" id="remarks" placeholder="Remarks"></textarea>
                                @error('remarks')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md">
                            <div class="text-center">
                                <button wire:click.prevent="addProductList" class="btn btn-primary">Add To List</button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-sm table-bordered my-3">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Remarks</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product_lists as $i => $list)
                                <tr>
                                    <td>{{ $list['product'] }}</td>
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
                    <div class="custom-control custom-checkbox">
                        <input wire:model="reprint_check" type="checkbox" class="custom-control-input" id="reprintCheck">
                        <label class="custom-control-label" for="reprintCheck">Reprint ?</label>
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
                        <h4>Servicing Delivery Receipt</h4>

                        <table class="table table-bordered mb-2">
                            <tbody>
                                <tr>
                                    <th>Receipt No</th>
                                    <td>{{ $printVNO }}</td>
                                    <th>Customer Name</th>
                                    <td>{{ $printCustomerName }}</td>
                                </tr>
                                <tr>
                                    <th>Delivery Date</th>
                                    <td>{{ date('d-M-Y', strtotime($printDate)) }}</td>
                                    <th>Customer Phone</th>
                                    <td>{{ $printCustomerPhone }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-sm table-bordered my-3">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    {{-- <th>Serial</th> --}}
                                    {{-- <th>Problem</th> --}}
                                    {{-- <th>Delivery Date</th> --}}
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($printArray as $i => $array)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $array['product'] }}</td>
                                        {{-- <td>{{ $array['serial'] }}</td> --}}
                                        {{-- <td>{{ $array['problems'] }}</td> --}}
                                        {{-- <td>{{ date('d-M-Y', strtotime($array['delivery_date'])) }}</td> --}}
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

        $('#SelectSerial').select2({
            placeholder: 'Select Serial',
        });
        $(document).on('change', '#SelectSerial', function (e) {
            @this.set('serial_id', e.target.value);
        });
        
        $('#SelectReceipt').select2({
            placeholder: 'Select Receipt',
        });
        $(document).on('change', '#SelectReceipt', function (e) {
            @this.set('receipt', e.target.value);
        });

        // if( ! @this.customer_id )
        // {
        //     $('#SelectCustomer').select2('focus');
        // }
        // else if( ! @this.serial_id ){
        //     $('#SelectSerial').select2('focus');
        // }
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

<script>
    window.addEventListener("SearchProductLoad", function (event) {

        $("#searchProductName").autocomplete({

            source: @this.response,

            select: function (event, ui) {
                // Set selection
                @this.product_id = ui.item.value; // save selected id to input
                @this.product = ui.item.label; // display the selected text

                return false;
            },
            focus: function (event, ui) {
                $("#searchProductName").val( ui.item.label );
                return false;
            }

        });

    });
</script>
