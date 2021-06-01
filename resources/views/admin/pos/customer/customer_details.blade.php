@extends('admin.pos.master')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>গ্রাহক সমূহ</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/Dashboard">হোম</a></li>
                            <li class="breadcrumb-item active">গ্রাহকের বিস্তারিত</li>
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
                                <h3 class="card-title">গ্রাহকের বিস্তারিত</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" name="cust_head" id="cust_id" value="{{$get_head->head}}">
                                    <input type="hidden" name="cust_id" id="id" value="{{$get_head->cid}}">
                                    <div class="col-md-4">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                            <tr>
                                                <th scope="row">নাম</th>
                                                <td>{{$customer->name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">ঠিকানা</th>
                                                <td>{{$customer->address}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">মোবাইল</th>
                                                <td>{{$customer->phone}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                            <tr>
                                                <th scope="row">মোট বিক্রয়</th>
                                                <td>{{ $total_sale}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">মোট বিক্রয় পরিশোধ</th>
                                                <td>{{ $total_sale_paid}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">সর্বমোট বকেয়া</th>
                                                <td>{{ $customer->due}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                            <tr>
                                                <th scope="row">মোট বিক্রয় ফেরত</th>
                                                <td>{{$total_return}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">মোট নগদ ফেরত</th>
                                                <td>{{$cash_return}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">মোট বিক্রয় ফেরত বাকি</th>
                                                <td>{{$return_due}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="col-12">
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                               href="#custom-tabs-four-home" role="tab"
                                               aria-controls="custom-tabs-four-home" aria-selected="true">লেজার</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                               href="#custom-tabs-four-profile" role="tab"
                                               aria-controls="custom-tabs-four-profile" aria-selected="false">বিক্রয়</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade active show" id="custom-tabs-four-home"
                                             role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                            <div class="row input-daterange mb-3">
                                                <div class="col-md-3">
                                                    <input type="text" name="from_date" id="from_date"
                                                           class="form-control"
                                                           value="<?php echo date('Y-m-d'); ?>"
                                                           readonly="">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="to_date" id="to_date" class="form-control"
                                                           placeholder="শুরুর তারিখ" readonly="">
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="button" name="filter" id="filter"
                                                            class="btn btn-primary">ফিল্টার
                                                    </button>
                                                    <button type="button" name="refresh" id="refresh"
                                                            class="btn btn-default">রিফ্রেশ
                                                    </button>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="text-right">
                                                        <a href="javascript:window.print();" class="btn btn-default"><i
                                                                class="fas fa-print"></i> প্রিন্ট</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="contact-ledger mt-5 mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <!-- Account Summary -->
                                                        <div class="card card-widget widget-user-2">
                                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                                            <div class="card-header bg-info">
                                                                <h3 class="card-title">গ্রাহক:</h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <b><i class="fa fa-user mr-2"></i> {{$customer->name}}
                                                                </b> <br>
                                                                <span><i
                                                                        class="fa fa-map-marker-alt mr-2"></i> <?php if (empty($customer->address)) {
                                                                        echo "No address given.";
                                                                    } else {
                                                                        echo $customer->address;
                                                                    } ?></span> <br>
                                                                <span><i class="fa fa-phone mr-2"></i> {{$customer->phone}}</span>
                                                            </div>
                                                        </div>
                                                        <!-- End Account Summary -->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <!-- Account Summary -->
                                                        <div class="card card-widget widget-user-2">
                                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                                            <div class="card-header bg-info">
                                                                <h3 class="card-title">প্রস্তুতকারক:</h3>
                                                            </div>
                                                            <div class="card-body">
                                                                <b><i class="fa fa-globe mr-2"></i> {{$GenSettings->site_name ?? " "}}
                                                                </b> <br>
                                                                <span><i class="fa fa-map-marker-alt mr-2"></i> {{$GenSettings->site_address ?? " "}}</span>
                                                                <br>
                                                                <span><i class="fa fa-phone mr-2"></i> {{$GenSettings->phone ?? " "}}</span>
                                                            </div>
                                                        </div>
                                                        <!-- End Account Summary -->
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="float-right font-weight-bolder">Previous Balance : <span id="previousBalance">0</span></span>

                                            <table id="ledger" class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>তারিখ</th>
                                                    <th>মেমো</th>
                                                    <th>বিস্তারিত</th>
                                                    <th>ডেবিট</th>
                                                    <th>ক্রেডিট</th>
                                                    <th>ব্যালেন্স</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                             aria-labelledby="custom-tabs-four-profile-tab">
                                            <table id="sales" class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>তারিখ</th>
                                                    <th>মেমো</th>
                                                    <th>মোট</th>
                                                    <th>সর্বমোট</th>
                                                    <th>পরিশোধ</th>
                                                    <th>বাকি</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($sales as $sale)
                                                    <tr>
                                                        <td>{{$sale->date}}</td>
                                                        <td>
                                                            <a href="{{url('/dashboard/sales_invoice/'.$sale->invoice_no)}}"><i
                                                                    class="fa fa-eye"
                                                                    aria-hidden="true"></i> {{$sale->invoice_no}}</a>
                                                        </td>
                                                        <td>{{$sale->amount}}</td>
                                                        <td>{{$sale->gtotal}}</td>
                                                        <td>{{$sale->payment}}</td>
                                                        <td>{{$sale->due}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
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
<style>
    .nav-tabs {
        display: flex !important;
    }
</style>
@section('page-js-script')
    <script src="/js/conversion.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var lastdate = new Date();
            lastdate.setDate(lastdate.getDate() - 365)
            $("#from_date,#to_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
            });
            $("#from_date").datepicker('setDate', lastdate);
            $("#to_date").datepicker('setDate', new Date());

            $val = $('#from_date').val();
            $('#from_date').val(($val));

            $val = $('#to_date').val();
            $('#to_date').val(($val));


            $('#from_date').on('change', function () {
                $val = $('#from_date').val();
                $('#from_date').val(($val));
            })
            $('#to_date').on('change', function () {
                $val = $('#to_date').val();
                $('#to_date').val(($val));
            })

            $("#sales").DataTable({
                "responsive": true,
                "autoWidth": false,
                "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            });
            //On Click Refresh Button
            $('#refresh').click(function () {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#ledger').DataTable().destroy();
                load_data();
            });

            $('#filter').click(function () {
                var custhead = $('#cust_id').val();


                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {
                    $('#ledger').DataTable().destroy();
                    load_data(from_date, to_date, custhead);
                } else {
                    alert('Both Date is required');
                }
            });

            //Filter By Date
            load_data();

            function load_data(from_date = '', to_date = '', custhead = '') {

                $.ajax({
                    url: "{{ route('customer.details.previousBalance') }}",
                    cache: false,
                    data: {from_date: from_date, to_date: to_date, sid: $('#id').val()},
                    success: function (response) {
                        console.log(response)
                        $("#previousBalance").text(response.p_balance);
                    },
                    error: function (result) {
                        console.log(result)

                        $("#previousBalance").text(response.p_balance);
                    }
                });
                var custhead = $('#cust_id').val();

                $("#ledger").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    "precessing": true,
                    "serverSide": true,
                    "columnDefs": [
                        {"orderable": false, "targets": 0}
                    ],
                    "pageLength": 50,
                    "oLanguage": {
                        "sSearch": "খুঁজুন:"
                    },
                    "language": {
                        "paginate": {
                            "previous": "পূর্বের পাতা",
                            "next": "পরবর্তী পাতা",
                        },
                        "info": "মোট _TOTAL_ রেকর্ড থেকে _START_ থেকে _END_ পর্যন্ত দেখানো হচ্ছে",
                        "infoEmpty": "মোট 0 রেকর্ড থেকে 0 থেকে 0 পর্যন্ত দেখানো হচ্ছে",
                    },
                    ajax: {
                        url: '{{ route("customer.details") }}',
                        data: {from_date: from_date, to_date: to_date, custhead: custhead},
                    },
                    columns: [
                        {data: 'date',},
                        {data: 'vno',},
                        {data: 'description',},
                        {data: 'debit',},
                        {data: 'credit',},
                        // {data:'user',},
                        {data: 'balance',},
                    ]
                });
            }

        });
    </script>
@stop
