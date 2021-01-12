@extends('admin.pos.master')
        
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Trial Balance</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">Trial Balance</li>
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
            <div class="card" id="ledhhh">
              <div class="card-body">
                <h2 class="text-center">Trial Balance</h2>
                <div class="row input-daterange mb-3">
                  <div class="col-md-3">
                    <label for="from_date">Start Date</label>
                    <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly="">
                  </div>
                  <div class="col-md-3">
                    <label for="to_date">End Date</label>
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" readonly="">
                  </div>
                  <div class="col-md-3">
                    <span style="line-height:30px;">Filter</span><br>
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <h2 class="text-center mt-4">Beautyshop Inc.</h2>
              <span class="text-center">Some Where In UK. United Kingdom</span>
              <span class="text-center"><i class="fa fa-phone"></i> +01947347345345</span>
              <div class="card-header">
                <div class="row">
                  <div class="col-md-9">
                    <h3 class="card-title"><strong>Trial Balance</strong></h3> <br>
                    <span><strong>From:</strong></span> <input type="text" disabled id="from"> <strong>TO</strong> <input type="text" disabled id="to">
                  </div>
                  <div class="col-md-3">
                    <div class="text-right">
                      <a href="javascript:window.print();" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="trials" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Ledger Name</th>
                      <th>Debit</th>
                      <th>Credit</th>
                      <th>Closing Balance</th>
                    </tr>
                  </thead>
                  <tfoot align="right">
                    <tr>
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
<script type="text/javascript">
  $(document).ready(function() {
    $("#from_date,#to_date").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
    });
    //On Click Refresh Button
    $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#trials').DataTable().destroy();
        load_data();
    });

    $('#filter').click(function(){
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var from = $('#from');
      var to = $('#to');
      from.val(from_date);
      to.val(to_date);
      if(from_date != '' &&  to_date != ''){
        $('#trials').DataTable().destroy();
        load_data(from_date, to_date);
      }else{
        alert('Both Date is required');
      }
    });

    //Filter By Date
    load_data ();
    function load_data(from_date = '', to_date = ''){
      $("#trials").DataTable({
        "responsive": true,
        "autoWidth": false,
        "lengthMenu": [[-1, 10, 25, 50, 100], ["All", 10, 25, 50, 100]],
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
            var debTotal = api
                .column( 1 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Total over this page
            pagedebTotal = api
                .column( 1, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

              var creTotal = api
                .column( 2 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageCreTotal = api
                .column( 2, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			
				
            // Update footer by showing the total with the reference of the column index 
              $( api.column( 0 ).footer() ).html('Total');
              $( api.column( 1 ).footer() ).html(pagedebTotal);
              $( api.column( 2 ).footer() ).html(pageCreTotal);
        },
        "precessing": true,
        "serverSide": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        ajax: {
          url: '{{ route("trial.balance") }}',
          data:{from_date:from_date, to_date:to_date},
        },
        columns: [
          {data:'head',},
          {data:'debit',},
          {data:'credit',},
          {data:'balance',},
        ]
      });
    }

  });
</script>
@stop
<style>
input[type="text"]:disabled {
  border: none;
  background: none;
  width: 80px;
}
@media print {
  #ledhhh { display: none }
}
</style>
