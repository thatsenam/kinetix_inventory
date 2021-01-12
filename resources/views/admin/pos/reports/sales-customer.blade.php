@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales By Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">Sales By Customer</li>
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
                      <option>Select Customer</option>
                      @foreach($customers as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
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
                <h3 class="card-title">Sales Report By Customer</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="bycustomer" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>Date</th>
                        <th>Invoice No.</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Due</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Invoice No.</th>
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
    $("#from_date,#to_date").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
    });
    //On Click Refresh Button
    $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#bycustomer').DataTable().destroy();
        load_data();
    });

    $('#filter').click(function(){
      var custID = $('#selectAction').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != ''){
        $('#bycustomer').DataTable().destroy();
        load_data(from_date, to_date, custID);
      }else{
        alert('Both Date is required');
      }
    });

    //Filter By Date
    load_data ();
    function load_data(from_date = '', to_date = '', custID = ''){
      $("#bycustomer").DataTable({
        "responsive": true,
        "autoWidth": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "precessing": true,
        "serverSide": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        ajax: {
          url: '{{ route("salesby.customer") }}',
          data:{from_date:from_date, to_date:to_date, custID:custID},
        },
        columns: [
          {data:'date',},
        //   {data:'invoice_no',},
          {
            "mData": "invoice_no",
            "mRender": function (data, type, row) {
                return "<a data-id=" + row.invoice_no + " href='/dashboard/sales_invoice/" + row.invoice_no + "'><span class='fa fa-eye'></span> View</a>";
            }
          },
          {data:'name',},
          {data:'gtotal',},
          {data:'payment',},
          // {data:'user',},
          {data:'due',},
        ]
      });
    }

  });
</script>
@stop