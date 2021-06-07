@extends('admin.pos.master')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Purchase By Supplier</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">Purchase By Supplier</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="row input-daterange mb-3">
              <div class="col-md-3">
                  <select id="selectAction" class="form-control select2">
                      <option>Select Supplier</option>
                      @foreach($supliers as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                      @endforeach
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
                <h3 class="card-title">Purchase Report By Supplier</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="bysupplier" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>Date</th>
                        <th>View Invoice</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Due</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>View Invoice</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Due</th>
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
      <!-- /.container-fluid -->
    </section>
</div>
@endsection

@section('page-js-script')
<script type="text/javascript">

    $(document).ready(function() {
        $('.select2').select2();
    });
  $(document).ready(function() {
    $("#from_date,#to_date").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
    });
    //On Click Refresh Button
    $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#bysupplier').DataTable().destroy();
        load_data();
    });

    $('#filter').click(function(){
      var supID = $('#selectAction').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != ''){
        $('#bysupplier').DataTable().destroy();
        load_data(from_date, to_date, supID);
      }else{
        alert('Both Date is required');
      }
    });

    //Filter By Date
    load_data ();
    function load_data(from_date = '', to_date = '', supID = ''){
      $("#bysupplier").DataTable({
        "responsive": true,
        "autoWidth": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "precessing": true,
        "serverSide": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        ajax: {
          url: '{{ route("purchaseby.supplier") }}',
          data:{from_date:from_date, to_date:to_date, supID:supID},
        },
        columns: [
          {data:'date',},
        //   {data:'invoice_no',},
          {
            "mData": "pur_inv",
            "mRender": function (data, type, row) {
                return "<a data-id=" + row.pur_inv + " href='/dashboard/purchase_invoice/" + row.pur_inv + "'><span class='fa fa-eye'></span> View</a>";
            }
          },
          {data:'name',},
          {data:'total',},
          {data:'payment',},
          // {data:'user',},
          {data:'due',},
        ]
      });
    }

  });
</script>
@stop
