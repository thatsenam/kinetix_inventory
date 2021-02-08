<div>

    @section('title', 'Loss Profit')

    <div class="content-wrapper">
        <h2 class="ml-3">Loss Profit Report</h2>
        <div class="card">
            <div class="card-body mx-auto">
                <div class="row">
                    <div class="form-group row text-right">
                        <label for="startDate" class="col-sm-4 col-form-label">Start Date</label>
                        <div class="col-sm-8">
                          <input wire:model.defer="startDate" type="date" class="form-control" id="startDate" value="{{ date('Y-m-01') }}">
                        </div>
                    </div>
                    <div class="form-group row text-right">
                        <label for="endDate" class="col-sm-4 col-form-label">End Date</label>
                        <div class="col-sm-8">
                          <input wire:model.defer="endDate" type="date" class="form-control" id="endDate" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between border bg-success p-1">
                    <div class="form-check form-check-inline">
                        <input wire:model="radioOption" class="form-check-input" type="radio" id="general" value="general">
                        <label class="form-check-label" for="general">General</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input wire:model="radioOption" class="form-check-input" type="radio" id="invoice" value="invoice">
                        <label class="form-check-label" for="invoice">Invoice</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input wire:model="radioOption" class="form-check-input" type="radio" id="product" value="product">
                        <label class="form-check-label" for="product">Product</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input wire:model="radioOption" class="form-check-input" type="radio" id="customer" value="customer">
                        <label class="form-check-label" for="customer">Customer</label>
                    </div>
                </div>
                <div class="mt-3">
                    @if($showInvoice)
                        <select wire:model.defer="selected_invoice" id="SelectInvoice" style="width: 100%">
                            @foreach($invoices as $invoice)
                                <option></option>
                                <option value="{{ $invoice }}">{{ $invoice }}</option>
                            @endforeach
                        </select>
                    @elseif($showProduct)
                        <select wire:model.defer="selected_product" id="SelectProduct" style="width: 100%">
                            @foreach($products as $product)
                                <option></option>
                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    @elseif($showCustomer)
                        <select wire:model.defer="selected_customer" id="SelectCustomer" style="width: 100%">
                            @foreach($customers as $customer)
                                <option></option>
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="mt-3 text-center">
                    <button wire:click.prevent="submit" class="btn btn-info">Report</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="float-right">
                    <button onclick="makePDF()" class="btn btn-success">Print</button>
                </div>
                <div id="printSection">
                    <div class="text-center mb-2" id="heading" style="display: none">
                        <h1>{{ $setting->site_name ?? ''  }}</h1>
                        <h3>{{ $setting->site_address ?? ''  }}</h3>
                        <h3 class="mb-4">{{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                        <h4>Loss Profit Report</h4>
                    </div>
                    {{-- <h1 class="text-center">Heading</h1> --}}
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Item</th>
                                <th>Qnt</th>
                                <th>Profit</th>
                                <th>Total Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product_array as $product)
                                <tr>
                                    <td>{{ $product->date }}</td>
                                    <td>{{ $product->invoice }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->qnt }}</td>
                                    <td>{{ $product->profit }}</td>
                                    <td>{{ $product->total_profit }}</td>
                                </tr>
                                @if($loop->last)
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <th>{{ $qnt_footer }}</th>
                                        <th>{{ $profit_footer }}</th>
                                        <th>{{ $total_profit_footer }}</th>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center"><span class="badge badge-pill badge-danger">No Data to Show</span></div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($product_array)
                        <table class="table table-bordered w-25 mt-5 mx-auto ">
                            <tr>
                                <th>Returned Profit</th>
                                <td>0</td>
                            </tr>
                            <tr>
                                <th>Deducted Discount</th>
                                <td>0</td>
                            </tr>
                            <tr>
                                <th>Added Discount</th>
                                <td>0</td>
                            </tr>
                            <tr>
                                <th>Supplier Bonus</th>
                                <td>0</td>
                            </tr>
                            <tr>
                                <th>Net Profit</th>
                                <td>{{ $total_profit_footer }}</td>
                            </tr>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready( function () {
        $('#myTable').DataTable();
    });

    window.addEventListener("LoadDataTable", function (){
        // $('#myTable').DataTable().destroy(); 
        $('#myTable').DataTable();
    });
    
    window.addEventListener("LoadSelect2", function () {

        $('#SelectInvoice').select2({
            placeholder: 'Select Invoice',
        });

        $(document).on('change', '#SelectInvoice', function (e) {
            @this.set('selected_invoice', e.target.value);
        });

        $('#SelectProduct').select2({
            placeholder: 'Select Product',
        });

        $(document).on('change', '#SelectProduct', function (e) {
            @this.set('selected_product', e.target.value);
        });

        $('#SelectCustomer').select2({
            placeholder: 'Select Customer',
        });

        $(document).on('change', '#SelectCustomer', function (e) {
            @this.set('selected_customer', e.target.value);
        });
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
