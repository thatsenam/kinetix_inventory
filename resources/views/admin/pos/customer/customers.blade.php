@extends('admin.pos.master')

@section('content')

    <div class="content-wrapper">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Customer List</h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Manage Customers</li>
                </ol>
            </div>
            </div>
        </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                    @if ($success = Session::get('flash_message_success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $success }}</strong>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Customers</h3>
                            <button class="btn btn-info float-right" data-toggle="modal" data-target="#addCust">
                                +New Customer
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="customerss" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Phone</th>
                                    <th style="width: 150px !important;">Address</th>
                                    <th>Due</th>
                                    <th>Joined At</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$customer->name}}</td>
                                        <td>{{$customer->status?"Active":"Inactive"}}</td>
                                        <td>{{$customer->phone}}</td>
                                        <td>{{$customer->address}}</td>
                                        <td>{{ $customer->balance ?? '' }}</td>
                                        <td>{{ $customer->date ?? '' }}</td>
                                        <td class="text-right">
                                            <div class="btn-group">
                                            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Options
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#paymodal" data-id="{{$customer->id}}"
                                                        onClick="open_container3(this);"><i
                                                            class="fa fa-money-bill-alt"></i>Receive</a>
                                                    <a class="dropdown-item"
                                                        href="{{url('/dashboard/customer/'.$customer->id)}}"><i
                                                            class="fa fa-eye" aria-hidden="true"></i> View</a>
                                                    <a class="dropdown-item" href="#" id="abc"
                                                        data-toggle="modal" data-target="#editmodal"
                                                        data-id="{{ $customer->id }}"
                                                        onClick="open_container2(this);"><i
                                                            class="fa fa-edit"></i> Update</a>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="deleteConfirmation({{$customer->id}})"><i
                                                            class="fa fa-trash text-danger"></i> Delete</a>
{{--                                                    <a class="dropdown-item" href="#"--}}
{{--                                                        onclick="DeactiveConfirmation({{$customer->id}})"><i--}}
{{--                                                            class="fas fa-power-off"></i> {{ $customer->status?"Active":"Inactive" }}--}}
{{--                                                    </a>--}}
                                            </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot align="left">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    </div>
                <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- Main content -->
        </section>
        <!-- /.content -->
    </div>
  <!-- /.content-wrapper -->

        <!-- Add Customer Modal -->
        <div class="modal fade" id="addCust" tabindex="-1" role="dialog" aria-labelledby="addCustTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{route('set_customer')}}" accept-charset="UTF-8" id="contact_add_form"
                          novalidate="novalidate">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add New Customer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputName" class="required">Name *</label>
                                        <input class="form-control" required="" placeholder="Name..."
                                               name="inputName" type="text" id="inputName" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputPhone" class="required">Phone *</label>
                                        <input class="form-control @if (count($errors) > 0) is-invalid @endif" required="" placeholder="Phone..."
                                        name="inputPhone" type="text" id="inputPhone" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputEmail">Email:</label>
                                        <input class="form-control" placeholder="Email ..." name="inputEmail"
                                               type="text" id="inputEmail" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputDate" class="required">Date:</label>
                                        <input class="form-control" required=""
                                               value="<?php echo date("Y-m-d");?>"
                                               name="inputDate" type="text" id="inputDate" aria-required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputAddress">Address:</label>
                                        <input class="form-control" placeholder="Adress..." name="inputAddress"
                                               type="text" id="inputAddress">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputAddress">Previous Due</label>
                                        <input class="form-control" name="inputOpeningBalance" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="save_customer" class="btn btn-primary">Save Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pay Modal -->
        <div class="modal fade" id="paymodal" tabindex="-1" role="dialog" aria-labelledby="paymodalTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Receive</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tbody>
                                            <tr>
                                                <th>Name
                                                </td>
                                                <td><input type="text" id="sname" disabled></td>
                                            </tr>
                                            <tr>
                                                <th>Phone
                                                </td>
                                                <td><input type="text" id="sphone" disabled></td>
                                            </tr>
                                            <tr>
                                                <th>Debit
                                                </td>
                                                <td><input type="text" id="sdebit" disabled></td>
                                            </tr>
                                            <tr>
                                                <th>Credit
                                                </td>
                                                <td><input type="text" id="scredit" disabled></td>
                                            </tr>
                                            <tr>
                                                <th>Due
                                                </td>
                                                <td><input type="text" id="sbalance" style="width: 100px;" disabled>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="cust_id" id="cust_id" value="0"
                                                   class="form-control">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-success alert-block" id="paysuccess"></div>
                        <form action="{{ route('save_payment') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label for="payamount">Amount*: </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-money-bill-alt"></i></div>
                                        </div>
                                        <input type="text" class="form-control" name="payamount" id="payamount"
                                               placeholder="০.০০">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="paidon">Date*: </label>

                                    <input type="text" class="form-control" id="paidon" name="paidon"
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="method">Payment Type:*</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            <select class="form-control" required="" id="paytype" name="paymethod">
                                                <option value="cash" selected="selected">Cash</option>
                                                <option value="card">Card</option>
                                                <option value="cheque">Cheque</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="mobile_banking">Mobile Banking</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="document">Document:</label>
                                        <input
                                            accept="application/pdf,text/csv,application/zip,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/jpg,image/png"
                                            name="document" type="file" id="document">
                                    </div>
                                </div>
                            </div>
                            <div class="payment_details_div row" id="card" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_type">Card Type</label>
                                        <select class="form-control" id="card_type" name="card_type">
                                            <option value="" selected="selected">--Select Card--</option>
                                            <option value="credit">Credit Card</option>
                                            <option value="debit">Debit Card</option>
                                            <option value="visa">Visa Card</option>
                                            <option value="master">Master Card</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_number">Company Bank</label>
                                        <select class="form-control" id="card_bank_account" name="card_bank_account">
                                            @foreach($getbanks as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="result"></div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="payment_details_div row" id="cheque" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="clients_bank">Customer's Bank</label>
                                        <input type="text" class="form-control" id="clients_bank" name="clients_bank"
                                               placeholder="Customer's Bank">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="clients_bank_acc">Customer's Bank Account</label>
                                        <input type="text" class="form-control" id="clients_bank_acc" name="clients_bank_acc" placeholder="Customer's Bank Account">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cheque_number">Checque No.</label>
                                        <input class="form-control" placeholder="Cheque No." name="cheque_number"
                                               type="text" id="cheque_number" placeholder="Checque No">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_type">Checque Type</label>
                                        <select class="form-control" id="check_type" name="check_type">
                                            <option value="pay_cash" selected="selected">Cash</option>
                                            <option value="pay_account">Account Payee</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_date">Cheque Date</label>
                                        <input type="text" class="form-control" id="check_date" name="check_date"
                                               placeholder="Cheque Date">
                                    </div>
                                </div>
                                <div class="pay_account" style="display: none; width:100%;">
                                    <div class="row col-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="card_number">Company Bank</label>
                                                <select class="form-control" id="shops_bank_account"
                                                        name="shops_bank_account">
                                                    @foreach($getbanks as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div id="cresult"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="payment_details_div row" id="bank_transfer" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bt_clients_bank">Customer's Bank</label>
                                        <input type="text" class="form-control" id="bt_clients_bank"
                                        name="bt_clients_bank" placeholder="Customer's Bank">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_account_number">Customer's Bank Account</label>
                                        <input class="form-control" placeholder="Bank Account No"
                                        name="bank_account_number" type="text" id="bank_account_number"
                                        placeholder="Customer's Bank Account">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_account_number">Transaction ID</label>
                                        <input class="form-control" placeholder="Transaction ID" name="txnid"
                                        type="text" id="txnid">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <select class="form-control" id="bt_shops_bank_acc" name="bt_shops_bank_acc">
                                            @foreach($getbanks as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="mAcc_result"></div>
                                </div>
                            </div>
                            <div class="payment_details_div row" id="mobile_banking" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mclients_bank">Mobile Bank Company</label>
                                        <input type="text" class="form-control" id="mclients_bank" name="mclients_bank"
                                        placeholder="Mobile Bank Company">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="maccount_number">Mobile Bank Number</label>
                                        <input class="form-control" placeholder="Mobile Bank Number"
                                        name="maccount_number" type="text" id="maccount_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mtxnid">Transaction ID</label>
                                        <input class="form-control" placeholder="Transaction ID" name="mtxnid"
                                        type="text" id="mtxnid">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <select class="form-control" id="mshops_bank_acc" name="mshops_bank_acc">
                                            <?php
                                            for($i = 0; $i < count($bank_infos);){
                                            $j = $i;
                                            $j1 = $i + 1;
                                            $j2 = $i + 2;
                                            $j3 = $i + 3;
                                            ?>
                                            <option
                                                value="{{$bank_infos[$j1]}}">{{$bank_infos[$j].','.$bank_infos[$j3]}}</option>
                                            <?php
                                            $i = $i + 4;
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="mAcc_result"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="note">Note:</label>
                                        <textarea class="form-control" rows="3" name="note" cols="50" id="note"
                                        placeholder="Note..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="save">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="alert alert-success alert-block" id="CartMsg"></div>
                    <form method="POST" action="" accept-charset="UTF-8" id="cust_edit" novalidate="novalidate">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Update Customer</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input class="form-control" name="name" type="text" id="name">
                                <input class="form-control" name="update_cust_id" type="text" id="update_cust_id"
                                hidden>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone *</label>
                                <input class="form-control" name="phone" type="text" id="phone">
                            </div>
                            <div class="form-group">
                                <label for="phone">Email:</label>
                                <input class="form-control" name="email" type="text" id="email">
                            </div>
                            <div class="form-group">
                                <label for="date">Date:</label>
                                <input class="form-control" name="date" type="text" id="date">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <input class="form-control" name="address" type="text" id="address">
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="inputAddress">Previous Due:</label>
                                    <input class="form-control" name="inputOpeningBalance" type="text"
                                           id="inputOpeningBalance">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button id="update_cus" class="btn btn-primary save">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@section('page-js-script')

    <script type="text/javascript">

        $(document).ready(function () {
            $("#inputPhone").change(function(){
                var s_text = $(this).val();
                var formData = new FormData();
                formData.append('s_text', s_text);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/dashboard/customers_phone',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {

                        if (data.includes(s_text)) {
                            $('#save_customer').prop('disabled',true)
                            $('#inputPhone').addClass('is-invalid')
                        }
                        else
                        {
                            $('#save_customer').prop('disabled',false)
                            $('#inputPhone').removeClass('is-invalid')
                        }
                    }
                });
            });
            $("#phone").change(function(){
                var s_text = $(this).val();
                var formData = new FormData();
                formData.append('s_text', s_text);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/dashboard/customers_phone',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {

                        if (data.includes(s_text)) {
                            $('#update_cus').prop('disabled',true)
                            $('#phone').addClass('is-invalid')
                        }
                        else
                        {
                            $('#update_cus').prop('disabled',false)
                            $('#phone').removeClass('is-invalid')
                        }
                    }
                });
            });
        });


        $(function () {
            $('#contact_add_form').validate({
                rules: {
                    inputName: {
                        required: true
                    },
                    inputPhone: {
                        required: true
                    }
                },
                messages: {
                    inputName: {
                        required: "Input customer name",
                    },
                    inputPhone: {
                        required: "Please give customer phone number",
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
        $(document).ready(function () {
            $('#check_date').datepicker({dateFormat: 'yy-mm-dd'});
            $('#paidon').datepicker({dateFormat: 'yy-mm-dd'});
            $('#date').datepicker({dateFormat: 'yy-mm-dd'});
            $('#inputDate').datepicker({dateFormat: 'yy-mm-dd'});
            $('#check_date').on('change', function () {
                $val = $('#check_date').val();
                $('#check_date').val($val);
            })
            $('#paidon').on('change', function () {
                $val = $('#paidon').val();
                $('#paidon').val($val);
            })
            $('#date').on('change', function () {
                $val = $('#date').val();
                $('#date').val($val);
            })
            $('#inputDate').on('change', function () {
                $val = $('#inputDate').val();
                $('#inputDate').val($val);
            })
            //Fill Input
            $('body').on('click', '.wow', function () {
                var bank_id = $(this).attr('id');
                var value = $(this).html();
                var card_bank_id = $('#card_bank_id');
                var sbank_id = $('#bank_id');
                var mbank_id = $('#mobile_bank_id');
                var card_bank = $('#card_bank');
                var shops_bank = $('#shops_bank');
                var mshops_bank = $('#bt_shops_bank');
                card_bank_id.val(bank_id);
                sbank_id.val(bank_id);
                sbank_id.val(bank_id);
                mbank_id.val(bank_id);
                card_bank.val(value);
                shops_bank.val(value);
                mshops_bank.val(value);
            });

            $('body').on('click', '.bank_acc', function () {
                var bank_id = $(this).attr('id');
                var value = $(this).html();
                var card_bank_acc_id = $('#card_bank_acc_id');
                var account_id = $('#account_id');
                var maccount_id = $('#mobile_bank_acc_id');
                var card_bank_account = $('#card_bank_account');
                var shops_bank_account = $('#shops_bank_account');
                var mobile_bank_account = $('#bt_shops_bank_acc');
                card_bank_acc_id.val(bank_id);
                account_id.val(bank_id);
                maccount_id.val(bank_id);
                card_bank_account.val(value);
                shops_bank_account.val(value);
                mobile_bank_account.val(value);
            });

            //Bank Search
            $('#card_bank,#shops_bank,#bt_shops_bank').on('keyup', function () {
                if ($('#card_bank').val() !== '') {
                    var text = $('#card_bank').val();
                } else if ($('#shops_bank').val() !== '') {
                    var text = $('#shops_bank').val();
                } else if ($('#bt_shops_bank').val() !== '') {
                    var text = $('#bt_shops_bank').val();
                }
                $.ajax({
                    type: "GET",
                    url: '/dashboard/search_bank',
                    data: 'text=' + text,
                    success: function (output) {
                        $("#result").html(output);
                        $("#cresult").html(output);
                        $("#mresult").html(output);
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });
            });

            //Bank Account Search
            $('#card_bank_account,#shops_bank_account,#bt_shops_bank_acc').on('keyup', function () {
                var text = $('#bank_id').val();
                $.ajax({
                    type: "GET",
                    url: '/dashboard/search_bank_acc',
                    data: 'text=' + text,
                    success: function (output) {
                        $("#acc_result").html(output);
                        $("#cAcc_result").html(output);
                        $("#mAcc_result").html(output);
                    },
                    error: function (result) {
                        console.log(result);
                    }
                });
            });

        });
        $(function () {
            $("#customerss").DataTable({
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [
                    { "width": "10%", "targets": 4 }
                ],
                "footerCallback": function (row, data, start, end, display) {
                    var api = this.api(), data;
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ? i : 0;
                    };
                    totalSum = api
                        .column(5)
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    $(api.column(5).footer()).html(
                        'TK-' + totalSum
                    );


                },

                buttons: [
                    {extend: 'copy'},
                    {
                        extend: 'print', customize: function (win) {

                            $(win.document.body).find('table').css('border', '1px solid black')
                            $(win.document.body).find('th').css('border', '1px solid black')
                            $(win.document.body).find('td').css('border', '1px solid black')
                            $(win.document.body).prepend(getReportHeader('Customer List')); //before the table

                        }
                    },
                    {extend: 'pdf'},
                    {extend: 'excel'},
                    {extend: 'csv'}
                ],


            });
        });
        $('select[name=paymethod]').change(function () {
            // hide all optional elements
            $('#card').hide();
            $('#cheque').hide();
            $('#bank_transfer').hide();
            $('#mobile_banking').hide();
            $("select[name=paymethod] option:selected").each(function () {
                $('#cheque').hide();
                $('#bank_transfer').hide();
                $('#card').hide();
                $('#mobile_banking').hide();
                var value = $(this).val();
                if (value == "card") {
                    $('#card').show();
                } else if (value == "cheque") {
                    $('#cheque').show();
                } else if (value == "bank_transfer") {
                    $('#bank_transfer').show();
                } else if (value == "mobile_banking") {
                    $('#mobile_banking').show();
                }
            });
        });
        $('select[name=check_type]').change(function () {
            // hide all optional elements
            $('.pay_account').hide();

            $("select[name=check_type] option:selected").each(function () {
                var value = $(this).val();
                if (value == "pay_account") {
                    $('.pay_account').show();
                }
            });
        });

        function open_container3(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('/dashboard/get_cust_details') }}',
                method: 'POST',
                dataType: 'JSON',
                data: {id: id.getAttribute('data-id')},
                success: function (data) {
                    $('#cust_id').val(data.id);
                    $('#sname').val(data.name);
                    $('#sphone').val(data.phone);
                    $('#shead').val(data.head);
                    $('#sdebit').val(data.debit);
                    $('#scredit').val(data.credit);
                    $('#sbalance').val(data.balance);
                    $('#spaymodal').modal('show');
                }
            });
        }

        function open_container2(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('/dashboard/update_cust') }}',
                method: 'POST',
                dataType: 'JSON',
                data: {id: id.getAttribute('data-id')},
                success: function (data) {
                    $('#name').val(data.name);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                    $('#email').val(data.email);
                    $('#date').val(data.date);
                    $('#update_cust_id').val(data.id);
                    $('#head').val(data.head);
                    $('#inputOpeningBalance').val(data.opb);
                    $('#debit').val(data.debit);
                    $('#credit').val(data.credit);
                    $('#editmodal').modal('show');
                }
            });
        }

        $(document).ready(function () {
            $("#CartMsg").hide();
            $('.save').click(function (e) {
                e.preventDefault();
                if ($('#name').val() == '' || $('#phone').val() == '') {
                    alert("Info fields can't be empty! Please give info to continue.");
                    return false;
                }
                var id = $("#update_cust_id").val();
                var name = $("#name").val();
                var phone = $("#phone").val();
                var address = $("#address").val();
                var email = $("#email").val();
                var inputOpeningBalance = $("#inputOpeningBalance").val();
                var date = $("#date").val();
                $.ajax({
                    url: "{{url('/dashboard/customerUp')}}",
                    data: 'id=' + id + '&name=' + name + '&phone=' + phone + '&address=' + address + '&email=' + email + '&date=' + date+ '&inputOpeningBalance=' + inputOpeningBalance,
                    type: 'get',
                    success: function (response) {
                        $("#CartMsg").show();
                        console.log(response);
                        $("#CartMsg").html(response);
                        window.setTimeout(function () {
                            location.reload();
                        }, 2000);
                    },
                    error: function (ts) {
                        alert(ts.responseText);
                    }
                });
            });
        });

        function deleteConfirmation(id) {
            swal.fire({
                title: "Delete?",
                text: "Are you sure!! Customer-related all data will be removed, that can not be recovered." ,
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes!",
                cancelButtonText: "No !",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/dashboard/delete_cust')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal.fire("Done!", results.message, "success");
                            } else {
                                swal.fire("Error!", results.message, "error");
                            }
                            window.setTimeout(function () {
                                location.reload();
                            }, 3000);
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            });
        }

        function DeactiveConfirmation(id) {
            swal.fire({
                title: "Change Status!",
                text: "Are you sure?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, Update!",
                cancelButtonText: "No, Cancel!",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'POST',
                        url: "{{url('/dashboard/up_cust')}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.success === true) {
                                swal.fire("Done!", results.message, "success");
                            } else {
                                swal.fire("Error!", results.message, "error");
                            }
                            window.setTimeout(function () {
                                location.reload();
                            }, 3000);
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function (dismiss) {
                return false;
            });
        }

        //save payment
        $(document).ready(function () {
            $("#paysuccess").hide();
            $('#save').click(function (e) {
                if ($('#payamount').val() == '' || $('#paidon').val() == '') {
                    alert("Please fill all mandatory fields with (*) sign");
                    return false;
                }
                if ($('#paytype').val() == 'check') {
                    if ($('#clients_bank').val() == '' || $('#clients_bank_acc').val() == '' || $('#check_amount').val() == '' || $('#check_date').val() == '') {
                        alert("Please fill all mandatory fields with (*) sign");
                        return false;
                    }
                    if ($('#check_type').val() == 'pay_account') {
                        if ($('#shops_bank').val() == '' || $('#shops_bank_account').val() == '') {
                            alert("Please fill cheque data");
                            return false;
                        }
                    }
                }
                // alert($('#paytype').val());
                // return false;
                var fieldValues = {};

                fieldValues.cust_id = $('#cust_id').val();
                fieldValues.cust_name = $('#sname').val();
                fieldValues.cust_phone = $('#sphone').val();
                fieldValues.amount = $('#payamount').val();
                fieldValues.paytype = $('#paytype').val();
                fieldValues.document = $('#document').val();
                fieldValues.remarks = $('#note').val();
                fieldValues.date = $('#paidon').val();

                if ($('#paytype').val() == 'cash') {
                    $('#card_type').val() == 'Cash';
                }
                fieldValues.cardtype = $('#card_type').val();
                // fieldValues.cardbank = $('#card_bank').val();
                // fieldValues.card_bank_id = $('#card_bank_id').val();
                fieldValues.card_bank_account = $('#card_bank_account').val();
                fieldValues.card_bank_name = $('#card_bank_account option:selected').text();

                //fieldValues.card_bank_acc_id = $('#card_bank_acc_id').val();

                fieldValues.clientsbank = $('#clients_bank').val();
                fieldValues.clientsbacc = $('#clients_bank_acc').val();
                fieldValues.checkno = $('#cheque_number').val();
                fieldValues.checktype = $('#check_type').val();
                fieldValues.checkdate = $('#check_date').val();
                // fieldValues.shopbank = $('#shops_bank').val();
                // fieldValues.bank_id = $('#bank_id').val();
                fieldValues.checksbacc = $('#shops_bank_account').val();
                fieldValues.shops_bank_name = $('#shops_bank_account option:selected').text();

                //fieldValues.account_id = $('#account_id').val();

                fieldValues.btcbank = $('#bt_clients_bank').val();
                fieldValues.btcbankacc = $('#bank_account_number').val();
                // fieldValues.bankaacno = $('#bank_account_number').val();
                // fieldValues.bt_shops_bank = $('#bt_shops_bank').val();
                // fieldValues.mobile_bank_id = $('#mobile_bank_id').val();
                fieldValues.bt_shops_bank_acc = $('#bt_shops_bank_acc').val();
                fieldValues.tranxid = $('#txnid').val();

                fieldValues.mclients_bank = $('#mclients_bank').val();
                fieldValues.mclientsaccount_number = $('#maccount_number').val();
                fieldValues.mobile_bank = $('#mshops_bank_acc').val();
                fieldValues.mtranxid = $('#mtxnid').val();

                var formData = new FormData();

                formData.append('fieldValues', JSON.stringify(fieldValues));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('save_payment') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    // beforeSend: function(){
                    //   $("#wait").show();
                    // },
                    success: function (data) {
                        $("#paysuccess").show();
                        $("#paysuccess").html(data);
                        console.log(data);
                        location.reload();
                    },
                    error: function (ts) {
                        $("#paysuccess").show();
                        $("#paysuccess").html(ts.responseText);
                        // $('#paymodal').addClass('d-none');
                        // window.open('http://localhost:8000/', '_blank');
                        window.open("/payment/invoice/" + ts.responseText + "", '_blank');
                        // console.log(data);
                        // location.reload();
                        setTimeout(function () {
                          location.reload();
                        }, 3000);
                    },
                });
            });
        });
    </script>

@stop
<style>
    #paymodal tr td input {
        border: none;
        background: none;
    }

    .dropdown-item:hover {
        background-color: dodgerblue !important;
        color: white !important;
    }

    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 600px !important;
        }
    }

    @media screen and (max-width: 768px) {
        .main-header a[data-widget="control-sidebar"] {
            display: none;
        }

        .modal-dialog {
            width: auto !important;
        }
    }
</style>
