@extends('layouts.admin.app')
@section('content')
<script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pending Orders</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">View Pendings</li>
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

            <div class="row input-daterange mb-3">
              <div class="col-md-3">
                  <select id="selectAction" class="form-control select2">
                      <option>Action</option>
                      <option value="confirm">Confirm</option>
                      <option value="paid">Paid</option>
                      <option value="cancel">Cancel</option>
                  </select>
              </div>
              <div class="col-md-3">
                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" readonly>
              </div>
              <div class="col-md-3">
                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly>
              </div>
              <div class="col-md-3">
                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Pending Orders</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="Pendings" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="select_all"></th>
                      <th>Order Number</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($getPendings as $pendings)
                    <tr>
                        <td><input type="checkbox" class="slct" value="{{$pendings->order_number}}"></td>
                        <td><a href="/admin/get_invoice/{{$pendings->order_number}}">{{$pendings->order_number}}</a></td>
                        <td>{{$pendings->name}}</td>
                        <td>{{$pendings->email}}</td>
                        <td>{{$pendings->grand_total}}</td>
                        <td>{{$pendings->order_date}}</td>
                        <td><a href="/admin/get_invoice/{{$pendings->order_number}}"><i class="fa fa-eye"></i> View</a></td>
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
  <script type="text/javascript">
  $(document).ready(function() {
    $(function () {
      $('.select2').select2()
    })
      
    $( "#from_date").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $( "#to_date").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    jQuery(document).ready(function() {
      jQuery('#select_all').change(function() {
        $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
      });
    });
    
    load_data ();
    function load_data(from_date = '', to_date = ''){
      $("#Pendings").DataTable({
        "responsive": true,
        "autoWidth": false,
        "precessing": true,
        "serverSide": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        ajax: {
          url: '{{ route("orders.pending") }}',
          data:{from_date:from_date, to_date:to_date},
        },
        columns: [
          {
            "mData": "order_number",
            "mRender": function (data, type, row) {
                return "<input name='myID' type='checkbox' class='slct' data-id=" + row.order_number + " value=" + row.order_number + ">";
            }
          },
          {data:'order_number',},
          {data:'name',},
          {data:'email',},
          {data:'grand_total',},
          {data:'order_date',},
          {
            "mData": "order_number",
            "mRender": function (data, type, row) {
                return "<a data-id=" + row.order_number + " href='/admin/get_invoice/" + row.order_number + "'><span class='fa fa-eye'></span> View</a>";
            }
          }
        ]
      });
    }

    $('#filter').click(function(){
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != ''){
        $('#Pendings').DataTable().destroy();
        load_data(from_date, to_date);
      }
      else{alert('Both Date is required');}
    });

    $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#Pendings').DataTable().destroy();
        load_data();
    });

      $('#selectAction').change(function(){
        var take_value = $(this).val();
        var orderid = [];
        $("input[name='myID']:checked").each(function () {
          orderid.push($(this).data("id"));
        });

        var formData = new FormData();
        formData.append('orderid', orderid);

        if(take_value == "confirm"){
          $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              url: "{{ URL::route('admin.orders.save_pending') }}",
              method: 'post',
              data: formData,
              contentType: false,
              cache: false,
              processData: false,
              dataType: "json",
              beforeSend: function(){
                  // $("#wait").show();
              },
              error: function(ts) {     
                  // alert(ts.responseText);
                  location.reload();
              },
              success: function(data){
                location.reload();
              }   		   
          });             
        }

        if(take_value == "cancel"){
          $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              url: "{{ URL::route('admin.orders.cancel_pending') }}",
              method: 'post',
              data: formData,
              contentType: false,
              cache: false,
              processData: false,
              dataType: "json",
              beforeSend: function(){
                  // $("#wait").show();
              },
              error: function(ts) {     
                  // alert(ts.responseText);
                  location.reload();
              },
              success: function(data){
                location.reload();
              }   		   
          });             
        }

        if(take_value == "paid"){
          $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              url: "{{ URL::route('admin.orders.cancel_pending') }}",
              method: 'post',
              data: formData,
              contentType: false,
              cache: false,
              processData: false,
              dataType: "json",
              beforeSend: function(){
                  // $("#wait").show();
              },
              error: function(ts) {     
                  // alert(ts.responseText);
                  location.reload();
              },
              success: function(data){
                location.reload();
              }   		   
          });             
        }

      });

});
</script>

@endsection