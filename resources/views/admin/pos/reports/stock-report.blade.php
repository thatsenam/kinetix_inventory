@extends('admin.pos.master')
@section('title', 'Stock Reports')
@section('content')
    @if($AccHeads <= 0 || $GenSettings ==null)
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                            <h1>Please, Configure General Settings and create Acoounts demo heads from before
                                proceed.</h1>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @else
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
                                    <span class="info-box-number">{{number_format($TotalPurchase,2)}}</span>
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
                                    <span class="info-box-number">{{number_format($TotalSold,2)}}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                    <form>
                        <div class="row input-daterange mb-3">
                            <div class="col-md-2">
                                <select name="category" id="category" class="form-control">
                                    <option></option>
                                    @foreach(\App\Category::query()->get() as $category)
                                        <option value="{{ $category->id }}"
                                                @if($selected_category == $category->id) selected @endif> {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="brand" id="brand" class="form-control ">
                                    <option></option>
                                    @foreach(\App\Brands::query()->get() as $brand)
                                        <option value="{{ $brand->id }}"
                                                @if($selected_brand == $brand->id) selected @endif> {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="start_date" id="from_date" class="form-control "
                                       placeholder="From Date" value="{{ $start_date }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="to_date" id="to_date" class="form-control"
                                       placeholder="To Date"
                                       value="{{ $end_date }}"
                                >
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <a href="{{ route('reports.stock-report') }}" id="refresh" class="btn btn-default">Refresh</a>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Reports</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="stocks" class="table table-sm table-bordered table-hover">
                                <thead style="font-size: 14px;">
                                <tr>
                                    <th>SL</th>
                                    <th>Product</th>
                                    <th>Unit Price</th>
                                    <th>Opening Stock</th>
                                    <th>Total Unit Purchased</th>
                                    <th>Purchase Return</th>
                                    <th>Total Unit Sold</th>
                                    <th>Sold Return</th>
                                    <th>Damage</th>
                                    <th>Current Stock</th>
                                    <th>Stock Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $index=>$record)
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $record->product_name }}</td>
                                        <td>{{ number_format($record->stock_price,2) }}</td>
                                        <td>{{ $record->opening_stock }}</td>
                                        <td>{{ $record->total_purchase }}</td>
                                        <td>{{ $record->total_purchase_return }}</td>
                                        <td>{{ $record->total_sales }}</td>
                                        <td>{{ $record->total_sales_return }}</td>
                                        <td>{{ $record->total_damage }}</td>
                                        <td>{{ $record->current_stock }}</td>
                                        <td>{{ number_format($record->current_stock  * $record->stock_price,2) }}</td>

                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    {{--                                    <th></th>--}}
                                    {{--                                    <th></th>--}}
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
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
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
    @endif
@endsection

@section('page-js-script')
    <script>
        $(document).ready(function () {
            $("#from_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#to_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $('#category').select2({placeholder: "Select Category", allowClear: true})
            $('#brand').select2({placeholder: "Select Brand", allowClear: true})
            load_data();

            function load_data(from_date = '', to_date = '') {
                $("#stocks").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "ordering": false,
                    "precessing": true,
                    "pageLength": 100,
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api(), data;

                        // // converting to interger to find total
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        for (let i = 0; i < 11; i++) {
                            let totalValue = api.column(i).data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);
                            $(api.column(i).footer()).html(totalValue);

                        }

                    },
                    "columnDefs": [
                        {"orderable": false, "sortable": false, "targets": [0, 1]}
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', {
                            extend: 'print', footer: true, title: '', customize: function (win) {

                                $(win.document.body).find('table').css('border', '1px solid black')
                                $(win.document.body).find('th').css('border', '1px solid black')
                                $(win.document.body).find('td').css('border', '1px solid black')
                                $(win.document.body).prepend(getReportHeader('স্টক রিপোর্টস')); //before the table

                            }
                        },
                    ],
                });
            }


        });
    </script>
@stop
