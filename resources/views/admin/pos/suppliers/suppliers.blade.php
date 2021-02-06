@extends('admin.pos.master')
        
@section('content')

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
              <li class="breadcrumb-item active">View All Suppliers</li>
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
                <button class="btn btn-info float-right" data-toggle="modal" data-target="#addCust">+Add Supplier</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="suppliers" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Actions</th>
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
                        <td class="text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Actions
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#paymodal" data-id="{{$supplier->id}}" onClick="open_container3(this);"><i class="fa fa-money-bill-alt"></i> Pay</a>
                                    <a class="dropdown-item" href="{{url('/dashboard/supplier/'.$supplier->id)}}"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                    <a class="dropdown-item" id="suppabc" data-id="{{$supplier->id}}" data-toggle="modal" data-target="#editmodal" onClick="open_container2(this);"><i class="fa fa-edit"></i> Edit</a>
                                    <a class="dropdown-item" onclick="deleteConfirmation({{$supplier->id}})"><i class="fa fa-trash"></i> Delete</a>
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
  <div class="modal fade" id="addCust" tabindex="-1" role="dialog" aria-labelledby="addCustTitle" aria-hidden="true">
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
                      <label for="inputName">Name:*</label>
                      <input class="form-control" placeholder="Enter Name..." name="inputName" type="text" id="inputName" aria-required="true" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label for="inputPhone">Phone:*</label>
                      <input class="form-control" placeholder="Enter Phone..." name="inputPhone" type="text" id="inputPhone" aria-required="true" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                      <div class="form-group">
                        <label for="inputAddress">Address:*</label>
                        <input class="form-control" placeholder="Enter Address..." name="inputAddress" type="text" id="inputAddress">
                      </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Create Supplier</button>
              </div>
            </form>
          </div>
      </div>
  </div>

  <!-- Pay Modal -->
  <div class="modal fade" id="paymodal" tabindex="-1" role="dialog" aria-labelledby="paymodalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Payment</h5>
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
                            <th>Name</td>
                            <td><input type="text" id="sname" disabled></td>
                          </tr>
                          <tr>
                            <th>Phone</td>
                            <td><input type="text" id="sphone" disabled></td>
                          </tr>
                          <tr>
                            <th>Balance</td>
                            <td><input type="text" id="sbalance" style="width: 100px;" disabled></td>
                          </tr>
                          <input type="hidden" name="supp_id" id="supp_id" value="0" class="form-control">
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
                      <input type="text" class="form-control" name="payamount" id="payamount" placeholder="0.00">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="paidon">Paid On*: </label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                      <input type="date" class="form-control" id="paidon" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="method">Payment Method:*</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                        <select class="form-control" required="" id="paytype" name="paymethod">
                          <option value="cash" selected="selected">Cash</option>
                          <option value="card">Card</option>
                          <option value="cheque">Cheque</option>
                          <option value="bank_transfer">Bank Transfer</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                        <label for="document">Attach Document:</label>
                        <input accept="application/pdf,text/csv,application/zip,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/jpeg,image/jpg,image/png" name="document" type="file" id="document">
                        <p class="small"> <br> Allowed File: .pdf, .csv, .zip, .doc, .docx, .jpeg, .jpg, .png </p>
                    </div>
                  </div>
                </div>
                <div class="payment_details_div row" id="card" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="card_type">Card Type</label>
                      <select class="form-control" id="card_type" name="card_type">
                        <option value="" selected="selected">Select Card Type</option>
                        <option value="credit">Credit Card</option>
                        <option value="debit">Debit Card</option>
                        <option value="visa">Visa</option>
                        <option value="master">MasterCard</option></select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="card_number">Shop Bank</label>
                      <input type="text" class="form-control" id="card_bank" name="card_bank" placeholder="Select Bank Name">
                    </div>
                    <input type="hidden" id="card_bank_id" name="card_bank_id" value="0">
                    <div id="result"></div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="card_holder_name">Shop Account</label>
                      <input type="text" class="form-control" id="card_bank_account" name="card_bank_account" placeholder="Select Bank Account Name">
                    </div>
                    <input type="hidden" id="card_bank_acc_id" name="card_bank_acc_id" value="0">
                    <div id="acc_result"></div>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="payment_details_div row" id="cheque" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clients_bank">Client's Bank</label>
                      <input type="text" class="form-control" id="clients_bank" name="clients_bank" placeholder="Enter Client's Bank Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clients_bank_acc">Client's Bank Account</label>
                      <input type="text" class="form-control" id="clients_bank_acc" name="clients_bank_acc" placeholder="Enter Client's Bank Acc Name">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="cheque_number">Cheque No.</label>
                      <input class="form-control" placeholder="Cheque No." name="cheque_number" type="text" id="cheque_number" placeholder="Enter Cheque Number">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="check_type">Check Type</label>
                      <select class="form-control" id="check_type" name="check_type">
                        <option value="pay_cash" selected="selected">Cash</option>
                        <option value="pay_account">Account</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                        <div class="form-group">
                          <label for="check_date">Cheque Date</label>
                          <input type="text" class="form-control" id="check_date" name="check_date" placeholder="Select Date">
                        </div>
                  </div>
                  <div class="pay_account" style="display: none; width:100%;">
                    <div class="row col-12">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="shops_bank">Shop's Bank	</label>
                          <input type="text" class="form-control" id="shops_bank" name="shops_bank" placeholder="Select Bank Name">
                        </div>
                        <input type="hidden" id="bank_id" name="bank_id" value="0">
                        <div id="cresult"></div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="shops_bank_account">Shop's Bank Account	</label>
                          <input type="text" class="form-control" id="shops_bank_account" name="shops_bank_account" placeholder="Select Bank Acc Name">
                        </div>
                        <input type="hidden" id="account_id" name="account_id" value="0">
                        <div id="cAcc_result"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="payment_details_div row" id="bank_transfer" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bt_clients_bank">Client's Bank</label>
                      <input type="text" class="form-control" id="bt_clients_bank" name="bt_clients_bank" placeholder="Enter Client's Bank Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bt_clients_bank_acc">Account Holder Name</label>
                      <input type="text" class="form-control" id="bt_clients_bank_acc" name="bt_clients_bank_acc" placeholder="Enter Account Holder Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bank_account_number">Bank Account No (Mobile Banking)</label>
                      <input class="form-control" placeholder="Bank Account No" name="bank_account_number" type="text" id="bank_account_number" placeholder="017xxxxxxxx">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bank_account_number">TXN-ID</label>
                      <input class="form-control" placeholder="Enter Transactions ID" name="txnid" type="text" id="txnid">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bt_shops_bank">Shop's Bank	</label>
                      <input type="text" class="form-control" id="bt_shops_bank" name="bt_shops_bank" placeholder="Select Shop Bank Name">
                    </div>
                    <input type="hidden" id="mobile_bank_id" name="mobile_bank_id" value="0">
                    <div id="mresult"></div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="bt_shops_bank_acc">Shop's Bank Account	</label>
                      <input type="text" class="form-control" id="bt_shops_bank_acc" name="bt_shops_bank_acc" placeholder="Select Bank Acc Name">
                    </div>
                    <input type="hidden" id="mobile_bank_acc_id" name="mobile_bank_acc_id" value="0">
                    <div id="mAcc_result"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="note">Payment Note:</label>
                      <textarea class="form-control" rows="3" name="note" cols="50" id="note" placeholder="Note about this payment..."></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="save">Save changes</button>
            </div>
          </div>
      </div>
  </div>
  <!-- Edit Modal -->
  <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
          <div class="alert alert-success alert-block" id="CartMsg"></div>
          <form method="POST" action="" accept-charset="UTF-8" id="cust_edit" novalidate="novalidate">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Name:</label>
                <input class="form-control" name="name" type="text" id="name">
              </div>
              <div class="form-group">
                <label for="phone">Phone:</label>
                <input class="form-control" name="phone" type="text" id="phone">
              </div>
              <div class="form-group">
                <label for="address">Address:</label>
                <input class="form-control" name="address" type="text" id="address">
              </div>
              <div class="form-group">
                <label for="address">Email:</label>
                <input class="form-control" name="email" type="text" id="email">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button class="btn btn-primary save">Save Changes</button>
            </div>
          </form>
          </div>
      </div>
  </div>

@endsection

@section('page-js-script')

<script type="text/javascript">
    var supplierID;

    $(function () {
        $('#addSupplier').validate({
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
                    required: "Supplier Name Field Can't Be Empty!",
                },
                inputPhone: {
                    required: "Supplier Phone Field Can't Be Empty!",
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
    $(document).ready(function() {
      $('#check_date').datepicker({dateFormat: 'yy-mm-dd'});

      //Fill Input
      $('body').on('click', '.wow', function(){
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

      $('body').on('click', '.bank_acc', function(){
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
      $('#card_bank,#shops_bank,#bt_shops_bank').on('keyup', function(){
        if($('#card_bank').val() !== ''){
          var text = $('#card_bank').val();
        }else if($('#shops_bank').val() !== ''){
          var text = $('#shops_bank').val();
        }else if($('#bt_shops_bank').val() !== ''){
          var text = $('#bt_shops_bank').val();
        }
        $.ajax({
          type:"GET",
          url: '/dashboard/search_bank',
          data: 'text=' + text,
          success:function(output) {
            $("#result").html(output);
            $("#cresult").html(output);
            $("#mresult").html(output);
          },
          error: function(result){
            console.log(result);
          }
        });
      });

      //Bank Account Search
      $('#card_bank_account,#shops_bank_account,#bt_shops_bank_acc').on('keyup', function(){
        var text = $('#bank_id').val();
        $.ajax({
          type:"GET",
          url: '/dashboard/search_bank_acc',
          data: 'text=' + text,
          success:function(output) {
            $("#acc_result").html(output);
            $("#cAcc_result").html(output);
            $("#mAcc_result").html(output);
          },
          error: function(result){
            console.log(result);
          }
        });
      });

      $('select[name=paymethod]').change(function () {
        // hide all optional elements
        $('#card').hide();
        $('#cheque').hide();
        $('#bank_transfer').hide();
        
        $("select[name=paymethod] option:selected").each(function () {
            var value = $(this).val();
            if(value == "card") {
              $('#card').show();
            } else if(value == "cheque") {
              $('#cheque').show();
            } else if(value == "bank_transfer") {
              $('#bank_transfer').show();
            }
        });
      });
      $('select[name=check_type]').change(function () {
        // hide all optional elements
        $('.pay_account').hide();
        
        $("select[name=check_type] option:selected").each(function () {
            var value = $(this).val();
            if(value == "pay_account") {
              $('.pay_account').show();
            }
        });
      });

    });
    function open_container2(id){
      supplierID = id.getAttribute('data-id');
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url:'{{ url('/dashboard/update_supp') }}',
        method: 'POST',
        dataType: 'JSON',
        data: { id:id.getAttribute('data-id') },
        success: function(data){
          $('#name').val(data.name);
          $('#phone').val(data.phone);
          $('#address').val(data.address);
          $('#email').val(data.email);
          // $('#head').val(data.head);
          // $('#debit').val(data.debit);
          // $('#credit').val(data.credit);
          $('#editmodal').modal('show');
        }
      });
    }
    function open_container3(id)
    {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:'{{ url('/dashboard/get_supp_details') }}',
            method: 'POST',
            dataType: 'JSON',
            data: { id:id.getAttribute('data-id') },
            success: function(data){
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
            text: "Please ensure and then confirm!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
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
                        window.setTimeout(function(){ 
                            location.reload();
                        } ,3000);
                    }
                });
            } else {
                e.dismiss;
            }
        }, function (dismiss) {
            return false;
      });
    }
    $(document).ready(function() {
      $("#suppliers").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "desc" ]]
      });
    });
    $(document).ready(function(){
      $("#CartMsg").hide();
      $('.save').click(function(e){
        e.preventDefault();
        if($('#name').val() == '' || $('#phone').val() == '' || $('#address').val() == ''){
            alert("Info fields can't be empty! Please give info to continue.");
            return false;
        }
        // var id = $("#suppabc").attr('data-id');
        var id = supplierID;
        var name = $("#name").val();
        var phone = $("#phone").val();
        var address = $("#address").val();
        var email = $("#email").val();
        $.ajax({
          url: "{{url('/dashboard/supplierUp')}}",
          data:'id=' + id + '&name=' + name + '&phone=' + phone + '&address=' + address + '&email=' + email,
          type:'get',
          success:function(response){
            $("#CartMsg").show();
            console.log(response);
            $("#CartMsg").html(response);
				    window.setTimeout(function(){ 
              location.reload();
            } ,2000);
          },
          error: function(ts) {         
            // alert(ts.responseText);
            window.setTimeout(function(){ 
                            location.reload();
                        } ,1000);
          }
        });
      });
    });

    //save payment
    $(document).ready(function(){
      $("#paysuccess").hide();
      $('#save').click(function(e){
        if($('#payamount').val() == '' || $('#paidon').val() == ''){
          alert("Please Fillup All Required Fields With (*) Sign");
          return false;
        }
        if($('#paytype').val() == 'check'){
          if($('#clients_bank').val() == '' || $('#clients_bank_acc').val() == '' || $('#check_amount').val() == '' || $('#check_date').val() == ''){
            alert("Please Fillup All Check Information");
            return false;
          }
          if($('#check_type').val() == 'pay_account'){
            if($('#shops_bank').val() == '' || $('#shops_bank_account').val() == '' ){
              alert("Please Fillup All Check Information");
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

        if($('#paytype').val() == 'cash'){
          $('#card_type').val() == 'Cash';
        }
        fieldValues.cardtype = $('#card_type').val();
        fieldValues.cardbank = $('#card_bank').val();
        fieldValues.card_bank_id = $('#card_bank_id').val();
        fieldValues.card_bank_account = $('#card_bank_account').val();
        fieldValues.card_bank_acc_id = $('#card_bank_acc_id').val();

        fieldValues.clientsbank = $('#clients_bank').val();
        fieldValues.clientsbacc = $('#clients_bank_acc').val();
        fieldValues.checkno = $('#cheque_number').val();
        fieldValues.checktype = $('#check_type').val();
        fieldValues.checkdate = $('#check_date').val();
        fieldValues.shopbank = $('#shops_bank').val();
        fieldValues.bank_id = $('#bank_id').val();
        fieldValues.checksbacc = $('#shops_bank_account').val();
        fieldValues.account_id = $('#account_id').val();

        fieldValues.btcbank = $('#bt_clients_bank').val();
        fieldValues.btcbankacc = $('#bt_clients_bank_acc').val();
        fieldValues.bankaacno = $('#bank_account_number').val();
        fieldValues.bt_shops_bank = $('#bt_shops_bank').val();
        fieldValues.mobile_bank_id = $('#mobile_bank_id').val();
        fieldValues.bt_shops_bank_acc = $('#bt_shops_bank_acc').val();
        fieldValues.mobile_bank_acc_id = $('#mobile_bank_acc_id').val();
        fieldValues.tranxid = $('#txnid').val();
        
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
          success: function(data){
            $("#paysuccess").show();
            $("#paysuccess").html(data);
// 			window.open("/payment/invoice/" + data.responseText + "", '_blank');
            console.log(data);
            // location.reload();
// 			setTimeout(function() {
//               location.reload();
//             }, 2000);
          },
          error: function(ts) {
            $("#paysuccess").show();
            $("#paysuccess").html(ts.responseText);
            // $('#paymodal').addClass('d-none');
            // window.open('http://localhost:8000/', '_blank');
            window.open("/payment/invoice/" + ts.responseText + "", '_blank');
            console.log(data);
            location.reload();
			      setTimeout(function() {
              location.reload();
            }, 2000);
          },
        });
      });
    });
</script>

@stop
<style>
#paymodal tr td input{
  border: none;
  background: none;
}
@media (min-width: 576px){
  .modal-dialog {
      max-width: 600px !important;
  }
}
@media screen and (max-width: 768px) {
  .main-header a[data-widget="control-sidebar"] {
    display: none;
  }
  .modal-dialog{
    width: auto !important;
  }
}
</style>