<div>
    @section('title', 'Warranty Report')

    <div class="content-wrapper">
        <h3 class="ml-3">Warranty Report</h3>
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
                            <label for="startDate" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">start Date</label>
                            <div class="col-sm-8">
                                <input wire:model="startDate" type="date" class="form-control" id="startDate" placeholder="">
                                @error('startDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md row">
                            <label for="endDate" class="col-sm-4 col-form-label font-weight-bold text-dark text-uppercase">End Date</label>
                            <div class="col-sm-8">
                                <input wire:model="endDate" type="date" class="form-control" id="endDate" placeholder="">
                                @error('endDate')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        {{-- <div class="form-group col-md row">
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
                        </div> --}}

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

                        <div class="form-group col-md row">
                            <label for="SelectSerial" class="col-sm-4 font-weight-bold text-dark text-uppercase">Serial</label>
                            <div class="col-sm-8">
                                <select wire:model="serial_id" id="SelectSerial" class="custom-select @error('serial_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach($serials as $serial)
                                        <option value="{{ $serial->id }}">{{ $serial->serial }}</option>
                                    @endforeach
                                </select>
                                @error('serial_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 mx-auto row">
                            <label for="SelectStatus" class="col-sm-4 font-weight-bold text-dark text-uppercase">Status</label>
                            <div class="col-sm-8">
                                <select wire:model="status" id="SelectStatus" class="custom-select @error('status') is-invalid @enderror">
                                    <option>Select Status</option>
                                    <option value="Receive From Customer">Receive From Customer</option>
                                    <option value="Send To Supplier">Send To Supplier</option>
                                    <option value="Receive From Supplier">Receive From Supplier</option>
                                    <option value="Delivery To Customer">Delivery To Customer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt-2 mx-auto">
                            <button wire:click.prevent="searchReport" class="btn btn-primary">Report</button>
                        </div>
                    </div>
                    <div id="printSection">
                        <div class="text-center mb-2" id="heading" style="display: none">
                            <h1>{{ $setting->site_name ?? ''  }}</h1>
                            <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                            <h4>Warranty Report</h4>
                        </div>
                        <table class="table table-sm table-bordered my-3">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Voucher</th>
                                    <th>Product</th>
                                    <th>Serial</th>
                                    <th>Supplier</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $i => $report)
                                    <tr>
                                        <td>{{ date('d-M-Y', strtotime($report['date'])) }}</td>
                                        <td>{{ $report['vno'] }}</td>
                                        <td>{{ $report['product'] }}</td>
                                        <td>{{ $report['serial'] }}</td>
                                        <td>{{ $report['supplier'] }}</td>
                                        <td>{{ $report['customer'] }}</td>
                                        <td>{{ $report['status'] }}</td>
                                        {{-- <td>
                                            <button wire:click.prevent="removeProductList({{ $i }})" class="btn btn-xs btn-danger">Delete</button>
                                        </td> --}}
                                    </tr>
                                @empty
    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- @if($error)
                        <div class="row">
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $error }}</strong>
                            </span>
                        </div>
                    @endif --}}
                </form>

                <button onclick="makePDF()" class="btn btn-success rounded-0 mb-2">Print</button>
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

        if( ! @this.customer_id )
        {
            $('#SelectCustomer').select2('focus');
        }
        else if( ! @this.serial_id ){
            $('#SelectSerial').select2('focus');
        }
    });

</script>

<script>
    function makePDF() {
        var head = document.getElementById("heading");
        head.style.display = "block";
        head.style.color = "black";
        var printContents = document.getElementById("printSection").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>
