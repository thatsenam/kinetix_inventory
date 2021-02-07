@extends('admin.pos.master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sales Report By Brand</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="/dashboard/pos">POS</a></li>
                            <li class="breadcrumb-item active">Sales Report By Brand</li>
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
                        <div class="row input-daterange mb-3">
                            <div class="col-md-3">
                                <select class="custom-select" name="brand_name" id="brand_name">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="from_date" id="from_date" value="{{ date('01-m-Y') }}"
                                    class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="to_date" id="to_date" value="{{ date('d-m-Y') }}"
                                    class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-md-3">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">All Reports</h3>
                            </div>
                            <div class="card-body">
                                <table id="reports" class="table table-sm table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Invoice</th>
                                            <th>Product Name</th>
                                            <th>Qnt</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
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
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->


    <script type="text/javascript">

        $(document).ready(function() {

            $("#from_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#to_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            function load_data(from_date = '', to_date = '', brandSelected) {
                $("#reports").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "precessing": true,
                    "serverSide": true,
                    "columnDefs": [{
                            "orderable": false,
                            "targets": 0
                        },
                        // {"bSearchable": true, "bVisible": false, "aTargets": [ 2 ]},
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 50,
                    "footerCallback": function(row, data, start, end, display) {
                        var api = this.api(),
                            data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total over all pages
                        qnt = api
                            .column(3)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        price = api
                            .column(4)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        total = api
                            .column(5)
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(3).footer()).html(
                            qnt
                        );
                        $(api.column(4).footer()).html(
                            price
                        );
                        $(api.column(5).footer()).html(
                            total
                        );
                    },
                    ajax: {
                        url: '{{ route('get_sales_report_brand') }}',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                            brand_id: brandSelected
                        },
                    },
                    columns: [{
                            data: 'sl',
                        },
                        {
                            data: 'invoice_no',
                        },
                        {
                            data: 'product_name',
                        },
                        {
                            data: 'qnt',
                        },
                        {
                            data: 'price',
                        },
                        {
                            data: 'total',
                        },
                    ]
                });
            }

            $('#filter').click(function() {

                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                var brandSelected = $('#brand_name').val();

                if (from_date != '' && to_date != '' && brandSelected != '') {
                    $('#reports').DataTable().destroy();
                    load_data(from_date, to_date, brandSelected);
                } else {
                    alert('Both Date is required');
                }
            });
        });
    </script>
@endsection
