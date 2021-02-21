@extends('admin.pos.master')
@section('title', 'Stock Transfer Report')
@section('content')

@if($AccHeads <= 0 || $GenSettings ==null)
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                        <h1>Please, Configure General Settings and create Acoounts demo heads from before proceed.</h1>
                    </div>
                </div>
            </div>
        </section>
    </div>
@else
<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Transfer Report</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main Page</a></li>
              <li class="breadcrumb-item active">Stock Transfer Report</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row input-daterange mb-3">
            <div class="col-md-3">
                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="Start Date">
              </div>
              <div class="col-md-3">
                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="End Date">
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
                    <table id="reports" class="table table-sm table-bordered table-hover">
                        <thead>
                            <tr style="font-size: 13px;">
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Product</th>
                                <th>Stock In</th>
                                <th>Stock Out</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                                        
                        </tbody>
                        <tfoot>
                            <tr style="font-size: 13px;">
                                <th colspan="3">Total</th>
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
    </section>
    <!-- /.content -->
</div>
@endif
@endsection
@section('page-js-script')
<script src="{{ asset('js/conversion.js')}}"></script>
<script>
    $(document).ready(function(){
        $( "#from_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $( "#to_date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $('#from_date').on('change',function(){
            $val=$('#from_date').val();
            $('#from_date').val($val);
        })
        $('#to_date').on('change',function(){
            $val=$('#to_date').val();
            $('#to_date').val($val);
        })
        load_data ();
        function load_data(from_date = '', to_date = ''){
            $("#reports").DataTable({
                "responsive": true,
                "autoWidth": false,
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
                    var inTotal = api
                        .column( 3 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        
                    var outTotal = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                        
                   
                    // Update footer by showing the total with the reference of the column index 
                    $( api.column( 2 ).footer() ).html('Total');
                    $( api.column( 3 ).footer() ).html(inTotal);
                    $( api.column( 4 ).footer() ).html(outTotal);
                },
                "precessing": true,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                // "oLanguage": {
                //     "sSearch": "খুঁজুন:"
                // },
                // "language": {
                //     "paginate": {
                //         "previous": "পূর্বের Page",
                //         "next": "পরবর্তী Page",
                //     },
                //     "info": "মোট _TOTAL_ রেকর্ড থেকে _START_ থেকে _END_ পর্যন্ত দেখানো হচ্ছে",
                //     "infoEmpty": "মোট 0 রেকর্ড থেকে 0 থেকে 0 পর্যন্ত দেখানো হচ্ছে",
                // },
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'print' },
                    { extend: 'copy' },
                    { extend: 'pdf' },
                    { extend: 'excel' },
                ],
                "pageLength": 50,
                ajax: {
                    url: '{{ route("stock_transfer_report_date") }}',
                    data:{from_date:from_date, to_date:to_date},
                },
                columns: [
                    {
                        "data": 'date',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'name',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'product_name',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'in_qnt',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'out_qnt',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'remarks',
                        "render": function (data) {
                            return data;
                        }
                    },
                ]
            });
        }
        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if(from_date != '' &&  to_date != ''){
                $('#reports').DataTable().destroy();
                load_data(from_date, to_date);
            }
            else{alert('Both Date is required');}
        });
        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('#reports').DataTable().destroy();
            load_data();
        });
    });
</script>
@stop