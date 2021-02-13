@extends('admin.pos.master')

@section('content')

    <div class="content-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Bank Transfer</h3>
                    </div>
                    <div class="card-body custom-table">

                        <div class="row text-dark font-weight-bold text-center">
                            <div class="col-md">
                                <h3>From Bank</h3>
                            </div>
                            <div class="col-md">
                                <h3>To Bank</h3>
                            </div>
                        </div>

                        <form class="row">

                            <div class="col-md">
                                <div class="form-group row">
                                    <label for="tf_bank" class="col-sm-4 col-form-label">Bank Name</label>
                                    <div class="col-sm-8">
                                        <select name="tf_bank" id="tf_bank" class="form-control">
                                            <option>Select Bank</option>
                                            @foreach ($bank_info as $row)
                                                <option value={{ $row->id }}>{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="tf_acc" class="col-sm-4 col-form-label">Account Name</label>
                                    <div class="col-sm-8">
                                        <select name="tf_acc" id="tf_acc" class="form-control">
                                            <option>Select Account</option>
                                        </select>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="balance" class="col-sm-4 col-form-label">Balance</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="balance" id="balance" class="form-control bg-light" placeholder="Balance" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="check_no" class="col-sm-4 col-form-label">Check No.</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="check_no" id="check_no" class="form-control" placeholder="Check No">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md">
                                <div class="form-group row">
                                    <label for="tt_bank" class="col-sm-4 col-form-label">Bank Name</label>
                                    <div class="col-sm-8">
                                        <select name="tt_bank" id="tt_bank" class="form-control">
                                            <option>Select Bank</option>
                                            @foreach ($bank_info as $row)
                                                <option value={{ $row->id }}>{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="tt_acc" class="col-sm-4 col-form-label">Account Name</label>
                                    <div class="col-sm-8">
                                        <select name="tt_acc" id="tt_acc" class="form-control">
                                            <option>Select Account</option>
                                        </select>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label for="amount" class="col-sm-4 col-form-label">Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="date" class="col-sm-4 col-form-label">Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                                        <div class="mt-4">
                                            <input type="button" class="btn btn-success btn-block" id="save" value="Save">
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

    <script type="text/javascript">
        $(document).ready(function() {

            $(function() {
                $("#date").datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });

            $('#tf_bank').change(function() {

                var tf_bank = $(this).val();

                var formData = new FormData();
                formData.append('bank_id', tf_bank);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_acc') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        //$("#wait").show();
                    },
                    error: function(ts) {

                        //alert(ts.responseText)

                        $('#tf_acc option').remove();
                        $('#tf_acc').append(ts.responseText);

                    },
                    success: function(data) {

                        alert(data);
                    }
                });
            });

            $('#tf_acc').change(function() {

                var tf_acc = $(this).val();

                var formData = new FormData();
                formData.append('account_id', tf_acc);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_balance') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        //$("#wait").show();
                    },
                    error: function(ts) {

                        //alert(ts.responseText);
                        //$('#balance').val(ts.responseText);

                    },
                    success: function(data) {

                        //alert(data);
                        $('#balance').val(data);
                    }
                });
            });

            $('#tt_bank').change(function() {

                var tt_bank = $(this).val();

                var formData = new FormData();
                formData.append('bank_id', tt_bank);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_bank_acc') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        //$("#wait").show();
                    },
                    error: function(ts) {
                        //alert(ts.responseText)
                        $('#tt_acc option').remove();
                        $('#tt_acc').append(ts.responseText);

                    },
                    success: function(data) {

                        alert(data);
                    }
                });
            });

            $('#save').click(function() {

                var tf_bank = $('#tf_bank').val();
                var tf_bank_name = $('#tf_bank option:selected').text();
                var tf_acc = $('#tf_acc').val();
                var tf_account_name = $('#tf_acc option:selected').text();

                var tt_bank = $('#tt_bank').val();
                var tt_bank_name = $('#tt_bank option:selected').text();
                var tt_acc = $('#tt_acc').val();
                var tt_account_name = $('#tt_acc option:selected').text();

                var balance = $('#balance').val();
                var check_no = $('#check_no').val();
                var amount = $('#amount').val();
                var date = $('#date').val();
                // var remarks = $('#remarks').val();

                var formData = new FormData();

                formData.append('tf_bank', tf_bank);
                formData.append('tf_bank_name', tf_bank_name);
                formData.append('tf_acc', tf_acc);
                formData.append('tf_account_name', tf_account_name);
                formData.append('tt_bank', tt_bank);
                formData.append('tt_bank_name', tt_bank_name);
                formData.append('tt_acc', tt_acc);
                formData.append('tt_account_name', tt_account_name);
                formData.append('balance', balance);
                formData.append('check_no', check_no);
                formData.append('amount', amount);
                formData.append('date', date);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('save_bank_transfer') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function() {
                        //$("#wait").show();
                    },
                    error: function(ts) {
                        alert(ts.responseText);
                        location.reload();
                    },
                    success: function(data) {
                        alert(data);


                    }
                });
            });

        });

        function Print() {
            //////////////printReceipt///////////

            var prtContent = document.getElementById("printdiv");
            var WinPrint = window.open('', '', 'left=0,top=0, toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }

    </script>

@stop

<style>
    .custom-table {

        width: 100%;
        border-collapse: collapse;
    }

    .custom-table tr th {
        background-color: #1bcfb4;
        padding: 5px;
        color: #FFF;
        text-align: center;
        border: 1px solid #e6e6e6;
    }

    .custom-table tr td {
        padding: 5px;
        border: 1px solid #e6e6e6;
        text-align: center;
        font-size: 14px;
    }

    .col-2 {
        padding-left: 10px !important;
        padding-right: 10px !important;
        text-align: center;
    }

</style>
