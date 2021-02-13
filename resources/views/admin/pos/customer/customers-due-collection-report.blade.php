@extends('admin.pos.master')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customers Due Collection Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Due Collection Report</li>
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
                        <div class="row mx-auto input-daterange mb-3">
                            <div class="col-md-3">
                              <input type="text" value="{{ date('Y-m-01') }}" name="from_date" id="from_date" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                              <input type="text" value="{{ date('Y-m-d') }}" name="to_date" id="to_date" class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-md-3">
                              <button type="button" name="search" id="search" class="btn btn-primary">Search</button>
                            </div>
                          </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Due Collection List</h3>
                            </div>
                            <div class="card-body">
                                <table id="reports" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            {{-- <th>Total Due</th> --}}
                                            <th>Due Paid</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($collections as $i => $collection)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $collection['name'] }}</td>
                                                <td>{{ $collection['phone'] }}</td>
                                                <td>{{ $collection['due'] }}</td>
                                                <td>{{ $collection['paid'] }}</td>
                                            </tr>
                                        @endforeach --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total</th>
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
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

@section('page-js-script')

    <script>
        $(document).ready(function () {

            $( "#from_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $( "#to_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            function load_data(from_date = '', to_date = ''){
                $("#reports").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };
            
                        // Total over all pages

                        // totalDue = api
                        //     .column( 3 )
                        //     .data()
                        //     .reduce( function (a, b) {
                        //         return intVal(a) + intVal(b);
                        //     }, 0 );
                        paid = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        // Update footer
                        // $( api.column( 3 ).footer() ).html(
                        //     totalDue
                        // );
                        $( api.column( 3 ).footer() ).html(
                            paid
                        );
                    },
                    ajax: {
                        url: '{{ route("get_customers_due_collection_report") }}',
                        data:{from_date:from_date, to_date:to_date},
                    },
                    columns: [
                        {data:'date',},
                        {data:'name',},
                        {data:'phone',},
                        {data:'due',},
                    ]
                });
            }
        
            $('#search').click(function(){
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if(from_date != '' &&  to_date != ''){
                    $('#reports').DataTable().destroy();
                    load_data(from_date, to_date);
                }
                else{alert('Both Date is required');}
            });

        });
    </script>

@stop
