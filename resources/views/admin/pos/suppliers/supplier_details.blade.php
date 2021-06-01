@extends('admin.pos.master')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>সরবরাহকারী</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/Dashboard">হোম</a></li>
                            <li class="breadcrumb-item active">সরবরাহকারী তথ্য</li>
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
                                <h3 class="card-title">সরবরাহকারী বিস্তারিত</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" class="form-control" value="{{$get_head->head}}" id="cust_id">
                                    <input type="hidden" name="cust_id" id="id" value="{{$get_head->cid}}">
                                    <input type="hidden" name="supplier_id" id="supplier_id" value="{{$supplier->id}}">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                            <tr>
                                                <th scope="row">নাম</th>
                                                <td>{{$supplier->name}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">ঠিকানা</th>
                                                <td>{{$supplier->address}}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">মোবাইল</th>
                                                <td>{{$supplier->phone}}</td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-sm">
                                            <tbody>
                                            <tr>
                                                <th scope="row"> মোট ক্রয়</th>
                                                <td>{{ $total_purchase }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row"> মোট ছাড়</th>
                                                <td>{{ $sumDiscount }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">মোট পরিশোধ</th>
                                                <td>{{ $fromPayment }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">সর্বমোট বকেয়া</th>
                                                <td>{{$supplier->due}}</td>
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
                                               aria-controls="custom-tabs-four-profile" aria-selected="false">ক্রয়
                                                সমূহ</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade active show" id="custom-tabs-four-home"
                                             role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

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
                                                                <b><i class="fa fa-user mr-2"></i> {{$supplier->name}}
                                                                </b> <br>
                                                                <span><i
                                                                        class="fa fa-map-marker-alt mr-2"></i> <?php if (empty($supplier->address)) {
                                                                        echo "No address given.";
                                                                    } else {
                                                                        echo $supplier->address;
                                                                    } ?></span> <br>
                                                                <span><i class="fa fa-phone mr-2"></i> {{$supplier->phone}}</span>
                                                            </div>
                                                        </div>
                                                        <!-- End Account Summary -->
                                                    </div>
                                                    <div class="col-md-6">
                                                        <!-- Account Summary -->
                                                        <div class="card card-widget widget-user-2">
                                                            <!-- Add the bg color to the header using any of the bg-* classes -->
                                                            <div class="card-header bg-info">
                                                                <h3 class="card-title">প্রস্তুতকারক</h3>
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
                                            <div class="row input-daterange mb-3">
                                                <div class="col-md-3">
                                                    <input type="text" name="from_date" id="from_date"
                                                           class="form-control" readonly="">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" name="to_date" id="to_date" class="form-control"
                                                           placeholder="To Date" readonly="">
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
                                            <span class="float-right font-weight-bolder">Previous Balance : <span id="previousBalance">0</span></span>
                                            <table id="ledger" class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>তারিখ</th>
                                                    <th>মেমো নং</th>
                                                    <th>বিস্তারিত</th>
                                                    <th>ডেবিট</th>
                                                    <th>ক্রেডিট</th>
                                                    <th>বাকী</th>
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
                                                    <th>মেমো নং</th>
                                                    <th>মোট</th>
                                                    <th>পরিশোধ</th>
                                                    <th>বাকী</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($purchase as $purc)
                                                    <tr>
                                                        <td>{{ $purc->date}}</td>
                                                        <td>
                                                            <a href="{{url('/dashboard/purchase_invoice/'.$purc->pur_inv)}}"><i
                                                                    class="fa fa-eye"
                                                                    aria-hidden="true"></i> {{ $purc->pur_inv }}
                                                            </a></td>
                                                        <td>{{ $purc->amount }}</td>
                                                        <td>{{ $purc->payment }}</td>
                                                        <td>{{ $purc->total }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel"
                                             aria-labelledby="custom-tabs-four-messages-tab">
                                            Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris.
                                            Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa
                                            eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer
                                            vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit
                                            condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis
                                            velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum
                                            odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla
                                            lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum
                                            metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac
                                            ornare magna.
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
            $('#from_date').val($val);

            $val = $('#to_date').val();
            $('#to_date').val($val);


            $('#from_date').on('change', function () {
                $val = $('#from_date').val();
                $('#from_date').val($val);
            })
            $('#to_date').on('change', function () {
                $val = $('#to_date').val();
                $('#to_date').val($val);
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
                var sid = $('#id').val();
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {
                    $('#ledger').DataTable().destroy();
                    load_data(from_date, to_date, sid);
                } else {
                    alert('Both Date is required');
                }
            });

            //Filter By Date
            $('#filter').click()

            function load_data(from_date = '', to_date = '', sid = '') {

                $.ajax({
                    url: "{{ route('supplier.details.previousBalance') }}",
                    cache: false,
                    data: {from_date: from_date, to_date: to_date, sid: $('#id').val()},
                    success: function (response) {
                        console.log(response)
                        $("#previousBalance").text(response.p_balance);
                    }
                });


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
                        url: '{{ route("supplier.details") }}',
                        data: {from_date: from_date, to_date: to_date, sid: sid, supplier_id:$('#supplier_id').val()},

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
