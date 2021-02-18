@extends('admin.pos.master')
@section('title', 'Stock Report By Warehouse')
@section('content')

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Report By Warehouse</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main Page</a></li>
              <li class="breadcrumb-item active">Stock Report By Warehouse</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row input-daterange mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Reports</h3>
                    </div>
                <!-- /.card-header -->
                    <div class="card-body">
                        <?php
                            $length = count($trow);
                            for($i=0; $i< $length; $i++){
                                echo $trow[$i];
                            }
                        ?>
                    </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<style>
    table {border-collapse: collapse;border-spacing: 0;width: 100%;border: 1px solid #ddd;}
    th, td {text-align: left;padding: 8px;}
    tr:nth-child(even){background-color: #f2f2f2}
</style>
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
                    $( api.column( 2 ).footer() ).html('Total');
                    $( api.column( 3 ).footer() ).html( etob(inTotal) );
                    $( api.column( 4 ).footer() ).html( etob(outTotal) );
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
                //         "previous": "পূর্বের পাতা",
                //         "next": "পরবর্তী পাতা",
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