@extends('admin.pos.master')
@section('title', 'Stock Reports')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Reports</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stock Reports</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                    <div class="info-box-content">
                        <span class="info-box-text">Total Purchhase</span>
                        <span class="info-box-number">{{$TotalPurchase}}</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                    <div class="info-box-content">
                        <span class="info-box-text">Total Sold</span>
                        <span class="info-box-number">{{$TotalSold}}</span>
                    </div>
                    <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>
            <div class="row input-daterange mb-3">
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
                    <h3 class="card-title">All Reports</h3>
                </div>
              <!-- /.card-header -->
                <div class="card-body">
                    <table id="stocks" class="table table-sm table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit Price</th>
                                <th>Opening Stock</th>
                                <th>Current Stock</th>
                                <th>Total Unit Purchased</th>
                                <th>Total Unit Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                        <tfoot align="right">
                            <tr>
                                <th colspan="3" ></th>
                                <th class="text-left"></th>
                                <th></th>
                                <th class="text-left"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
       </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('page-js-script')
<script>
    $(document).ready(function(){
        $( "#from_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $( "#to_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        load_data ();
        function load_data(from_date = '', to_date = ''){
            $("#stocks").DataTable({
                "responsive": true,
                "autoWidth": false,
                "precessing": true,
                "serverSide": true,
                "pageLength": 100,
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
                    var pur = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    // Total over this page
                    pagePurTotal = api
                        .column( 4, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                        leftTotal = api
                        .column( 2, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                        var sold = api
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                        pageSoldTotal = api
                        .column( 5, { page: 'current'} )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    
                        
                    // Update footer by showing the total with the reference of the column index 
                    $( api.column( 0 ).footer() ).html('Total Purchased');
                    $( api.column( 3 ).footer() ).html(pur);
                    $( api.column( 4 ).footer() ).html('Total Sold');
                    $( api.column( 5 ).footer() ).html(sold);
                },
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                ajax: {
                url: '{{ route("reports.stocks") }}',
                data:{from_date:from_date, to_date:to_date},
                },
                columns: [
                    {data:'product_name',},
                    {data:'price',},
                    {data:'OpenngS',},
                    {data:'currenTstock',},
                    {data:'pPurchase',},
                    {data:'psold',}
                ]
            });
        }
        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date != '' &&  to_date != ''){
                $('#stocks').DataTable().destroy();
                load_data(from_date, to_date);
            }
            else{alert('Both Date is required');}
        });
        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#stocks').DataTable().destroy();
            load_data();
        });
    });
</script>
@stop