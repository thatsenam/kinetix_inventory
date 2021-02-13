@extends('admin.pos.master')

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>Customer Due Collection</h3>
                    </div>
                    <div class="card-body custom-table">

                        <form method="POST" action="/dashboard/customers/store-due-collection" class="row">

                            @csrf

                            <div class="col-md">
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label">Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="customer_id" class="col-sm-4 col-form-label">Customer Name</label>
                                    <div class="col-sm-8">
                                        <select name="customer_id" id="customer_id" class="form-control">
                                            <option>Select Customer</option>
                                            @foreach ($customers as $customer)
                                                <option value={{ $customer->id }}>{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="total_due" class="col-sm-4 col-form-label">Total Due</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="total_due" id="total_due" class="form-control bg-light" placeholder="Balance" readonly>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md">
                                
                                <div class="form-group row">
                                    <label for="discount" class="col-sm-4 col-form-label">Discount</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="discount" value="0" id="discount" class="form-control" placeholder="Discount">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="payment_type" class="col-sm-4 col-form-label">Payment Type</label>
                                    <div class="col-sm-8">
                                        <select name="payment_type" id="payment_type" class="form-control">
                                            <option>Cash</option>
                                            <option>Mobile</option>
                                            <option>Card</option>
                                            <option>Cheque</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="payment" class="col-sm-4 col-form-label">Payment</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="payment" id="payment" class="form-control" placeholder="Payment">
                                        @error('payment')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-success btn-block">SAVE</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-js-script')

    <script>
        $(document).ready( function () {
            $( "#date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#customer_id').select2({
                placeholder: 'Select Customser',
            });

            $("#customer_id").on("change keyup paste", function() {

                var customer_id = $(this).val();

                $.ajax({
                    url: "/get_customer_due/" + customer_id,
                    method: 'get',
                    data: '',
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        $("#wait").show();
                    },
                    error: function(response) {
                        alert(response.responseText);
                    },
                    success: function(data) {
                        $("#total_due").val(data);
                    }
                });
            });
        });
    </script>
@stop

