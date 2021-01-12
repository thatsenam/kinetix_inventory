@extends('admin.pos.master')
        
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>General Ledger</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Dashboard">Home</a></li>
              <li class="breadcrumb-item active">General Ledger</li>
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
                <h2 class="text-center">Ledger Report</h2>
                <div class="row input-daterange mb-3">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="heads">Select Head</label>
                      <select id="heads" name="heads" class="select2 form-control custom-select">
                          <option disabled>Select one</option>
                          @foreach($heads as $head)
                            <option value="{{$head->cid}}">{{$head->head}}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
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
                    <h3 class="card-title"><strong>General Ledger</strong></h3> <br>
                    <span><strong>For:</strong></span> <input type="text" disabled id="hhh" style="width: 300px;"> <br>
                    <span><strong>From:</strong></span> <input type="text" disabled id="from"> <strong>TO</strong> <input type="text" disabled id="to"> <br>
                    <span><strong>Previous Balance:</strong></span> <input type="text" disabled id="prvb">
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
                <table id="ledgers" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>V No.</th>
                      <th>Description</th>
                      <th>Debit</th>
                      <th>Credit</th>
                      <th>Balance</th>
                    </tr>
                  </thead>
                  <tfoot align="right">
                    <tr>
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
    var lastdate = new Date();
    lastdate.setDate(lastdate.getDate() - 365)
    $("#from_date,#to_date").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd",
    });
    $('#filter').click(function(){
      var hid = $('#heads').find(":selected").val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url:'{{route("prev.balance")}}',
        data:'from_date=' + from_date + '&to_date=' + to_date + '&hid=' + hid,
        success:function(response){
          console.log(response);
          $("#prvb").val(response);
          // alert(response);
        },
        error: function(ts) {
          $("#prvb").html(response);
          alert(ts.responseText);
        }
		  });
    });
    //On Click Refresh Button
    $('#refresh').click(function(){
        $('#from_date').val('');
        $('#to_date').val('');
        $('#ledgers').DataTable().destroy();
        load_data();
    });

    $('#filter').click(function(){
      var hid = $('#heads').find(":selected").val();
      var headval = $('#heads').find(":selected").html();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var head = $('#hhh');
      var from = $('#from');
      var to = $('#to');
      head.val(headval);
      from.val(from_date);
      to.val(to_date);
      if(from_date != '' &&  to_date != ''){
        $('#ledgers').DataTable().destroy();
        load_data(from_date, to_date, hid);
      }else{
        alert('Both Date is required');
      }
    });

    //Filter By Date
    load_data ();
    function load_data(from_date = '', to_date = '', hid = ''){
      $("#ledgers").DataTable({
        "responsive": true,
        "autoWidth": false,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
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
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            // Total over this page
            pagedebTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

              var creTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

                pageCreTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
			
				
            // Update footer by showing the total with the reference of the column index 
              $( api.column( 2 ).footer() ).html('Total');
              $( api.column( 3 ).footer() ).html(pagedebTotal +' ('+ debTotal +' total)');
              $( api.column( 4 ).footer() ).html(pageCreTotal +' ('+ creTotal +' total)');
        },
        "precessing": true,
        "serverSide": true,
        "columnDefs": [
            { "orderable": false, "targets": 0 }
        ],
        ajax: {
          url: '{{ route("general.ledger") }}',
          data:{from_date:from_date, to_date:to_date, hid:hid},
        },
        columns: [
          {data:'date',},
          {data:'vno',},
          {data:'description',},
          {data:'debit',},
          {data:'credit',},
          // {data:'user',},
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
