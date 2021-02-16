@extends('admin.pos.master')
@section('title', 'স্টক স্থানান্তর রিপোর্ট')
@section('content')

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>স্টক স্থানান্তর রিপোর্ট</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">মূলপাতা</a></li>
              <li class="breadcrumb-item active">স্টক স্থানান্তর রিপোর্ট</li>
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
                <input type="text" name="from_date" id="from_date" class="form-control" placeholder="পারম্ভিক তারিখ">
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
                                <th>গুদাম</th>
                                <th>পণ্যের নাম</th>
                                <th>স্টক ইন</th>
                                <th>স্টক আউট</th>
                                <th>মন্তব্য</th>
                            </tr>
                        </thead>
                        <tbody>
                                        
                        </tbody>
                        <tfoot align="right">
                            <tr style="font-size: 13px;">
                                <th colspan="3">সর্বমোট</th>
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
            $('#from_date').val(etob($val));
        })
        $('#to_date').on('change',function(){
            $val=$('#to_date').val();
            $('#to_date').val(etob($val));
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
                    $( api.column( 2 ).footer() ).html('সর্বমোট');
                    $( api.column( 3 ).footer() ).html( etob(inTotal) );
                    $( api.column( 4 ).footer() ).html( etob(outTotal) );
                },
                "precessing": true,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": 0 }
                ],
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
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'print', text: 'প্রিন্ট করুন' },
                    { extend: 'copy', text: 'কপি করুন' },
                    { extend: 'pdf', text: 'পিডিএফ' },
                    { extend: 'excel', text: 'এক্সেল' },
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
                            return etob(data);
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
                            return etob(data);
                        }
                    },
                    {
                        "data": 'out_qnt',
                        "render": function (data) {
                            return etob(data);
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