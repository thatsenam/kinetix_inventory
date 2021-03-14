@extends('admin.pos.master')
        
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Supplier Group</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">All Supplier Groups</li>
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
                <h3 class="card-title">All Supplier Groups</h3>
                <button class="btn btn-info float-right" data-toggle="modal" data-target="#addCust">+New Supplier Group</button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="suppliers" class="table text-center table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Group</th>
                    <th>Option</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach($supplier_groups as $supplier)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$supplier->supplier_group}}</a></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" id="suppabc" data-id="{{$supplier->id}}" data-toggle="modal" data-target="#editmodal" onClick="open_container2(this);"><i class="fa fa-edit"></i> Update</a>
                                    <a class="dropdown-item" onclick="deleteConfirmation({{$supplier->id}})"><i class="fa fa-trash"></i>Delete</a>
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
                  <h5 class="modal-title" id="exampleModalLongTitle">Add New Supplier Group</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="inputName">Group Name:*</label>
                      <input class="form-control" placeholder="গ্রূপের নাম..." name="inputName" type="text" id="inputName" aria-required="true" required>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Supplier Group</button>
              </div>
            </form>
          </div>
      </div>
  </div>

  <!-- Pay Modal -->
 
  <!-- Edit Modal -->
  <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="editmodalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
          <div class="alert alert-success alert-block" id="CartMsg"></div>
          <form method="POST" action="" accept-charset="UTF-8" id="cust_edit" novalidate="novalidate">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Update Supplier Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="inputName">Group Name:*</label>
                      <input class="form-control" placeholder="Group Name..." name="name" type="text" id="name" aria-required="true" required>
                      <input class="form-control" placeholder="Group Name..." name="sup_id" type="text" id="sup_id" aria-required="true" hidden required>
                    </div>
                  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button class="btn btn-primary save">Update</button>
            </div>
          </form>
          </div>
      </div>
  </div>

@endsection

@section('page-js-script')
<script src="/js/conversion.js"></script>

<script type="text/javascript">
    $(function () {
        $('#addSupplier').validate({
            rules: {
                inputName: {
                required: true
                },
            },
            messages: {
                inputName: {
                    required: "Please fill up this field!",
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
    
    function open_container2(id){
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url:'{{ url("/dashboard/update_supp_group") }}',
        method: 'POST',
        dataType: 'JSON',
        data: { id:id.getAttribute('data-id') },
        success: function(data){
          $('#sup_id').val(data.id);
          $('#name').val(data.name);
          $('#editmodal').modal('show');
        }
      });
    }
    
    function deleteConfirmation(id) {
        swal.fire({
            title: "Delete?",
            text: "Are you sure?",
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
                    url: "{{url('/dashboard/delete_supp_group')}}/" + id,
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
      });
    });
    $(document).ready(function(){
      $("#CartMsg").hide();
      $('.save').click(function(e){
        e.preventDefault();
        if($('#name').val() == ''){
            alert("Info fields can't be empty! Please give info to continue.");
            return false;
        }
        var id = $("#sup_id").val();
        var name = $("#name").val();
        $.ajax({
          url: "{{url('/dashboard/supplierGroupUp')}}",
          data:'id=' + id + '&name=' + name ,
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
          alert("Please fill all mandatory iinput fields!");
          return false;
        }
        if($('#paytype').val() == 'check'){
          if($('#clients_bank').val() == '' || $('#clients_bank_acc').val() == '' || $('#check_amount').val() == '' || $('#check_date').val() == ''){
            alert("Please give cheque details");
            return false;
          }
          if($('#check_type').val() == 'pay_account'){
            if($('#shops_bank_account').val() == '' ){
              alert("Please give cheque details");
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
        fieldValues.shops_bank_name= $('#shops_bank_account option:selected').text();

        //fieldValues.account_id = $('#account_id').val();

        fieldValues.btcbank = $('#bt_clients_bank').val();
        //fieldValues.btcbankacc = $('#bt_clients_bank_acc').val();
        fieldValues.bankaacno = $('#bank_account_number').val();
        // fieldValues.bt_shops_bank = $('#bt_shops_bank').val();
        // fieldValues.mobile_bank_id = $('#mobile_bank_id').val();
        fieldValues.bt_shops_bank_acc = $('#bt_shops_bank_acc').val();
        fieldValues.mobile_bank_name = $('#bt_shops_bank_acc option:selected').text();

        //fieldValues.mobile_bank_acc_id = $('#mobile_bank_acc_id').val();
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