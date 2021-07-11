@extends('admin.pos.master')

@section('content')
    @if($AccHeads <= 0 || $GenSettings ==null)
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                            <h1> Please Add General Settings and Account Heads</h1>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Suppliers</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
                                <li class="breadcrumb-item active">All Supplier</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
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
                                    <h3 class="card-title">All Suppliers</h3>
                                    <button class="btn btn-info float-right" data-toggle="modal" data-target="#addCust">
                                        +New Supplier
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="suppliers" class="table text-center table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>

                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>Payable</th>
                                            <th>Option</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach($suppliers as $supplier)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$supplier->name}}</a></td>

                                                <td>{{$supplier->phone}}</td>
                                                <td>{{$supplier->address}}</td>
                                                <td>{{ $supplier->balance }}</td>
                                                <td class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-info btn-sm dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                               data-target="#paymodal" data-id="{{$supplier->id}}"
                                                               onClick="open_container3(this);"><i
                                                                    class="fa fa-money-bill-alt"></i> Pay</a>
                                                            <a class="dropdown-item"
                                                               href="{{url('/dashboard/supplier/'.$supplier->id)}}"><i
                                                                    class="fa fa-eye" aria-hidden="true"></i> View </a>
                                                            <a class="dropdown-item" href="#" id="suppabc"
                                                               data-id="{{$supplier->id}}" data-toggle="modal"
                                                               data-target="#editmodal"
                                                               onClick="open_container2(this);"><i
                                                                    class="fa fa-edit"></i> Update</a>
                                                            <a class="dropdown-item" href="#"
                                                               onclick="deleteConfirmation({{$supplier->id}})"><i
                                                                    class="fa fa-trash"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
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
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Add Supplier Modal -->
        <div class="modal fade" id="addCust" tabindex="-1" role="dialog" aria-labelledby="addCustTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form method="POST" action="" accept-charset="UTF-8" id="addSupplier" novalidate="novalidate">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Add New Supplier</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputName" class="required">Name: <span style="color: red">*</span></label>
                                        <input class="form-control" placeholder="Name..." name="inputName" type="text"
                                               id="inputName" aria-required="true" required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputPhone" class="required">Phone: <span
                                                style="color: red">*</span></label>
                                        <input class="form-control" placeholder="Phone..." name="inputPhone" type="text"
                                               id="inputPhone" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputPhone">Email:</label>
                                        <input class="form-control" placeholder="Email..." name="inputEmail" type="text"
                                               id="inputEmail" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputAddress">Address:</label>
                                        <input class="form-control" placeholder="Address..." name="inputAddress"
                                               type="text" id="inputAddress">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" style="display: none">
                                        <label for="inputPhone">Village or area name:</label>
                                        <input class="form-control" placeholder="Village or area name..."
                                               name="inputArea" type="text" id="inputArea">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" style="display: none">
                                        <label for="inputPhone">Upazilla :</label>
                                        <input class="form-control" placeholder="Upazilla..." name="inputUpazilla"
                                               type="text" id="inputUpazilla" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" style="display: none">
                                        <label for="inputPhone">District :</label>
                                        <input class="form-control" placeholder="District..." name="inputDistrict"
                                               type="text" id="inputDistrict" aria-required="true">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputAddress">Previous Due</label>
                                        <input class="form-control" name="inputOpeningBalance" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12" style="display:none">
                                    <div class="form-group">
                                        <label for="inputAddress">Supplier Details:</label>
                                        <textarea class="form-control" placeholder="Supplier Details..."
                                                  name="inputDetails" id="inputDetails" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="save_customer" class="btn btn-primary" disabled>Add Supplier
                            </button>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">Pay</h5>
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
                                                <th>Balance
                                                </td>
                                                <td><input type="text" id="sbalance" style="width: 100px;" disabled>
                                                </td>
                                            </tr>
                                            <input type="hidden" name="supp_id" id="supp_id" value="0"
                                                   class="form-control">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-success alert-block" id="paysuccess"></div>
                        <form action="" method="post" enctype="multipart/form-data">
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
                                        <label for="card_type">Card Type *</label>
                                        <select class="form-control" id="card_type" name="card_type">
                                            <option value="" selected="selected">SelectOne</option>
                                            <option value="credit">Credit Card</option>
                                            <option value="debit">Debit Card</option>
                                            <option value="visa">Visa Card</option>
                                            <option value="master">Master Card</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="card_number">Company Bank *</label>
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
                                        <label for="clients_bank">Supplier Bank</label>
                                        <input type="text" class="form-control" id="clients_bank" name="clients_bank"
                                               placeholder="Supplier Bank">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="clients_bank_acc">Supplier Bank Account</label>
                                        <input type="text" class="form-control" id="clients_bank_acc"
                                               name="clients_bank_acc" placeholder="Supplier Bank Account">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cheque_number">Cheque Number</label>
                                        <input class="form-control" name="cheque_number" type="text" id="cheque_number"
                                               placeholder="Cheque Number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="check_type">Cheque Type</label>
                                        <select class="form-control" id="check_type" name="check_type">
                                            <option value="pay_cash" selected="selected">Cash</option>
                                            <option value="pay_account">Account</option>
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
                                                <label for="card_number">Company Bank *</label>
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
                                        <label for="bt_clients_bank">Supplier Bank</label>
                                        <input type="text" class="form-control" id="bt_clients_bank"
                                               name="bt_clients_bank" placeholder="Supplier Bank Account">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_account_number">Supplier Bank Account Number</label>
                                        <input class="form-control" name="bank_account_number" type="text"
                                               id="bank_account_number" placeholder="Supplier Bank Account Number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bank_account_number">Transaction Number</label>
                                        <input class="form-control" placeholder="Transaction Number" name="txnid"
                                               type="text" id="txnid">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Bank Name *</label>
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
                                        <label for="mtxnid">Transaction No</label>
                                        <input class="form-control" placeholder="Transaction No." name="mtxnid"
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
                                                  placeholder="Note:..."></textarea>
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
                            <h5 class="modal-title" id="exampleModalLongTitle">Update Supplier</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputName" class="required">Name: <span class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Name..." name="name" type="text"
                                               id="name" aria-required="true" required>
                                        <input class="form-control" placeholder="Name..." name="sup_id" type="text"
                                               id="sup_id" aria-required="true" hidden required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputPhone" class="required">Phone:<span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" placeholder="Phone..." name="phone" type="text"
                                               id="phone" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputPhone">E-mail:</label>
                                        <input class="form-control" placeholder="E-mail:..." name="email" type="text"
                                               id="email" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputAddress" class="required">Address:</label>
                                        <input class="form-control" placeholder="Address..." name="address" type="text"
                                               id="address">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" style="display: none">
                                        <label for="inputPhone">Village or area name:</label>
                                        <input class="form-control" placeholder="Village or area name..." name="area"
                                               type="text" id="area" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" style="display: none">
                                        <label for="inputPhone">Upazilla :</label>
                                        <input class="form-control" placeholder="upazilla..." name="upazilla"
                                               type="text"
                                               id="upazilla" aria-required="true">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group" style="display: none">
                                        <label for="inputPhone">District:</label>
                                        <input class="form-control" placeholder="district..." name="district"
                                               type="text"
                                               id="district" aria-required="true">
                                    </div>
                                </div>


                            </div>
                            <div class="row">

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputAddress">Previous due</label>
                                        <input class="form-control" name="inputOpeningBalance" type="text"
                                               id="inputOpeningBalance">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group" style="display:none">
                                        <label for="inputAddress">Supplier details:</label>
                                        <textarea class="form-control" placeholder="Supplier details..."
                                                  name="details" id="details" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary save" id="up_sup">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('page-js-script')
    <script src="/js/conversion.js"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $("#inputPhone").change(function () {
                var s_text = $(this).val();
                var formData = new FormData();
                formData.append('s_text', s_text);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/dashboard/supplier_phone',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {

                        if (data.includes(s_text)) {
                            $('#save_customer').prop('disabled', true)
                            $('#inputPhone').addClass('is-invalid')
                        } else {
                            $('#save_customer').prop('disabled', false)
                            $('#inputPhone').removeClass('is-invalid')
                        }
                    }
                });
            });
            $("#phone").change(function () {
                var s_text = $(this).val();
                var formData = new FormData();
                formData.append('s_text', s_text);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/dashboard/supplier_phone',
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {

                        if (data.includes(s_text)) {
                            $('#up_sup').prop('disabled', true)
                            $('#phone').addClass('is-invalid')
                        } else {
                            $('#up_sup').prop('disabled', false)
                            $('#phone').removeClass('is-invalid')
                        }
                    }
                });
            });
        });


        $(function () {
            $('#addSupplier').validate({
                rules: {
                    inputName: {
                        required: true
                    },
                    inputPhone: {
                        required: true
                    },

                },
                messages: {
                    inputName: {
                        required: "This field value is required",
                    },
                    inputPhone: {
                        required: "This field value is required",
                    },

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
            $('#check_date').on('change', function () {
                $val = $('#check_date').val();
                $('#check_date').val($val);
            })
            $('#paidon').on('change', function () {
                $val = $('#paidon').val();
                $('#paidon').val($val);
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

            $('select[name=paymethod]').change(function () {
                // hide all optional elements
                $('#card').hide();
                $('#cheque').hide();
                $('#bank_transfer').hide();
                $('#mobile_banking').hide();
                $("select[name=paymethod] option:selected").each(function () {
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

        });

        $('#phone').on('input', async function () {
            let supplier_id = $('#supp_id').val();
            let phone = $('#phone').val();
            let request = await fetch("{{ url('supplier/is-phone-unique') }}/" + `${phone}/${supplier_id}`);
            console.log(await request.json(),supplier_id);
        });


        function open_container2(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url("/dashboard/update_supp") }}',
                method: 'POST',
                dataType: 'JSON',
                data: {id: id.getAttribute('data-id')},
                success: function (data) {
                    $('#sup_id').val(data.id);
                    $('#name').val(data.name);
                    $('#phone').val(data.phone);
                    $('#address').val(data.address);
                    $('#email').val(data.email);
                    $('#area').val(data.area);
                    $('#upazilla').val(data.upazilla);
                    $('#district').val(data.district);
                    $('#inputOpeningBalance').val(data.opb || 0);
                    // alert(data.opb)
                    $('#details').val(data.details);

                    $('#editmodal').modal('show');
                }
            });
        }

        function open_container3(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('/dashboard/get_supp_details') }}',
                method: 'POST',
                dataType: 'JSON',
                data: {id: id.getAttribute('data-id')},
                success: function (data) {
                    $('#supp_id').val(data.id);
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

        function deleteConfirmation(id) {
            swal.fire({
                title: "Delete?",
                text: "Are you sure!! Supplier-related all data will be removed, that can not be recovered.",
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
                        url: "{{url('/dashboard/delete_supp')}}/" + id,
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

        $(document).ready(function () {
            $("#suppliers").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [[0, "asc"]],
            });
        });
        $(document).ready(function () {
            $("#CartMsg").hide();
            $('.save').click(function (e) {
                e.preventDefault();
                if ($('#name').val() == '' || $('#phone').val() == '') {
                    alert("Please fill all field with (*) sign!");
                    return false;
                }
                var id = $("#sup_id").val();
                var name = $("#name").val();
                var phone = $("#phone").val();
                var address = $("#address").val();
                var email = $("#email").val();
                var area = $("#area").val();
                var upazilla = $("#upazilla").val();
                var district = $("#district").val();
                var details = $("#details").val();
                var inputOpeningBalance = $("#inputOpeningBalance").val();

                $.ajax({
                    url: "{{url('/dashboard/supplierUp')}}",
                    data: 'id=' + id + '&name=' + name + '&phone=' + phone + '&address=' + address + '&email=' + email + '&area=' + area + '&upazilla=' + upazilla + '&district=' + district + '&details=' + details + '&inputOpeningBalance=' + inputOpeningBalance,
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

        //save payment
        $(document).ready(function () {
            $("#paysuccess").hide();
            $('#save').click(function (e) {
                if ($('#payamount').val() == '' || $('#paidon').val() == '') {
                    alert("Please fill all field with (*) sign!");
                    return false;
                }
                if ($('#paytype').val() == 'card') {

                    if ($('#card_bank_account').val() == '' || $('#card_type').val() == '') {
                        alert("Please fill with (*) sign!");
                        return false;
                    }

                }
                if ($('#paytype').val() == 'check') {
                    if ($('#clients_bank').val() == '' || $('#clients_bank_acc').val() == '' || $('#check_amount').val() == '' || $('#check_date').val() == '') {
                        alert("Please provide checque details");
                        return false;
                    }
                    if ($('#check_type').val() == 'pay_account') {
                        if ($('#shops_bank_account').val() == '') {
                            alert("Please provide checque details");
                            return false;
                        }
                    }
                }
                var fieldValues = {};

                fieldValues.supp_id = $('#supp_id').val();
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

                //fieldValues.mobile_bank_acc_id = $('#mobile_bank_acc_id').val();

                var formData = new FormData();

                formData.append('fieldValues', JSON.stringify(fieldValues));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('supp_payment') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        $("#paysuccess").show();
                        $("#paysuccess").html(data);
// 			            window.open("/payment/invoice/" + data.responseText + "", '_blank');
                        console.log(data);
                        // location.reload();
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    },
                    error: function (ts) {
                        $("#paysuccess").show();
                        $("#paysuccess").html(ts.responseText);
                        // $('#paymodal').addClass('d-none');
                        // window.open('http://localhost:8000/', '_blank');
                        window.open("/payment/invoice/" + ts.responseText + "", '_blank');
                        console.log(ts);
                        // location.reload();
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    },
                });
            });
        });
    </script>

@stop
<style>

    .dropdown-item:hover {
        background-color: dodgerblue !important;
        color: white !important;
    }

    #paymodal tr td input {
        border: none;
        background: none;
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
