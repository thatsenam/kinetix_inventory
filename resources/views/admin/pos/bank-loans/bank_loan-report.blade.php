@extends('admin.pos.master')
@section('title', 'ব্যাংক ঋণ রিপোর্ট')
@section('content')

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>ব্যাংক ঋণ রিপোর্ট</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">মূলপাতা</a></li>
              <li class="breadcrumb-item active">ব্যাংক ঋণ রিপোর্ট</li>
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
                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="শুরুর তারিখ">
              </div>
              <div class="col-md-3">
                <input type="text" name="to_date" id="to_date" class="form-control" placeholder="শেষ তারিখ">
              </div>
              <div class="col-md-3">
                <button type="button" name="filter" id="filter" class="btn btn-primary">ফিল্টার</button>
                <button type="button" name="refresh" id="refresh" class="btn btn-default">রিফ্রেশ</button>
              </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">একনজরে সকল রিপোর্টস</h3>
                </div>
              <!-- /.card-header -->
                <div class="card-body">
                    <table id="reports" class="table table-sm table-bordered table-hover">
                        <thead>
                            <tr style="font-size: 13px;">
                                <th>তারিখ</th>
                                <th>ব্যাংকের নাম</th>
                                <th>ঋণ গ্রহণের তারিখ</th>
                                <th>মেয়াদ উত্তীর্ণের তারিখ</th>
                                <th>ঋণের পরিমাণ</th>
                                <th>ইন্টারেস্ট রেইট</th>
                                <th>কিস্তির পরিমাণ</th>
                                <th>মোট পরিশোধ</th>
                                <th>মোট বাঁকি</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                                        
                        </tbody>
                        <!-- <tfoot align="right">
                            <tr style="font-size: 13px;">
                                <th colspan="5">সর্বমোট পরিমান</th>
                                <th></th>
                                <th>সর্বমোট পরিশোধ</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot> -->
                    </table>
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
</div>

<div id="printThis" class="col-12">
    <div id="MyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-body">
              <div class="col-12 text-center">
                <h4>{{ $GenSettings->site_name }}</h4>
                <span>{{ $GenSettings->site_address }}</span>
                <h6>উৎপাদন রিপোর্ট</h6>
              </div>
              <table id="memo-details"></table>
              <br>
              <table id="prodlist" class="mb-2 table-bordered price-table custom-table" style="width:100%;"></table>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button id="btnPrint" type="button" class="btn btn-primary">Print</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

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
                "precessing": true,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy'},
                    { extend: 'pdf'},
                    { extend: 'excel'},
                    { extend: 'csv'}
                ],
                "pageLength": 50,
                ajax: {
                    url: '{{ route("bank_loan_report_date") }}',
                    data:{from_date:from_date, to_date:to_date},
                },
                columns: [
                    {
                        "data": 'loan_date',
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
                        "data": 'loan_date',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'loan_expiry_date',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'loan_amount',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'interest_rate',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'installment_amount',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'total_paid',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'total_unpaid',
                        "render": function (data) {
                            return data;
                        }
                    },
                    {
                        "data": 'status',
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