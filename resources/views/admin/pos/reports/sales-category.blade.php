@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Sales By Category</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">Sales By Category</li>
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
                      <option>Select Category</option>
                      @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
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
                <h3 class="card-title">Sales Report By Category</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="bycategory" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $d)
                    <tr>
                      <td><a href="/dashboard/sales_invoice/{{$d->invoice_no}}">{{$d->invoice_no}}</a></td>
                      <td>{{$d->product_name}}</td>
                      <td>{{$d->qnt}}</td>
                      <td>{{$d->price}}</td>
                      <td>{{$d->total}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot align="right">
                    <tr>
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
        $('#bycategory').DataTable().destroy();
        load_data();
    });

    $('#filter').click(function(){
      var cid = $('#selectAction').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      if(from_date != '' &&  to_date != ''){
        $('#bycategory').DataTable().destroy();
        load_data(from_date, to_date, cid);
      }else{
        alert('Both Date is required');
      }
    });

    //Filter By Date
    load_data ();
    function load_data(from_date = '', to_date = '', cid = ''){
      $("#bycategory").DataTable({
        "responsive": true,
        "autoWidth": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var qty = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Total over this page
            pageQtyTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

              var total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			
				
            // Update footer by showing the total with the reference of the column index 
              $( api.column( 1 ).footer() ).html('Total');
              $( api.column( 2 ).footer() ).html(qty);
              $( api.column( 4 ).footer() ).html(total);
        },
        "precessing": true,
        "serverSide": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        ajax: {
          url: '{{ route("salesby.category") }}',
          data:{from_date:from_date, to_date:to_date, cid:cid},
        },
        columns: [
          {data:'product_name',},
          {
            "mData": "invoice_no",
            "mRender": function (data, type, row) {
                return "<a data-id=" + row.invoice_no + " href='/dashboard/sales_invoice/" + row.invoice_no + "'><span class='fa fa-eye'></span> invoice_no</a>";
            }
          },
          {data:'qnt',},
          {data:'price',},
          {data:'total',},
        ]
      });
    }

  });
</script>
@stop