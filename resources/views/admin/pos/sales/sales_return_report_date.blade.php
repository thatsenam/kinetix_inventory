@extends('admin.pos.master')
        
@section('content')


<div class="content-wrapper">
    <div class="row">
      <div class="col-12">
          
             <div class="card">
                <div class="card-header">
                    <h3 class="ml-2">Sales Return Report</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-2">
                            <label>Date From</label>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" name="stdate" id="stdate" autocomplete="off">
                        </div>
                        <div class="col-2">
                            <label>Date To</label>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" name="enddate" id="enddate" autocomplete="off">
                        </div>
                        <div class="col-2">
                            <input type="submit" class="btn btn-success btn-lg" id="search" value="Search">
                        </div>
                    </div>  
                </div>
             </div>
             <div class="card">
                 <div class="card-body">
                    <table class="table table-striped" id="converting">
                        
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Return Inv</th>
                                <th>Sales Inv</th>
                                <th>Serial</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>U.Price</th>
                                <th>T.Price</th>
                                <th>IVA</th>
                                <th>Total</th>
                                <th>Cash Return</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">Total</th>
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
             </div>
      </div>
    </div>
</div>


@endsection

@section('page-js-script')

<script type="text/javascript">

    $(document).ready(function(){

        $( "#stdate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        $( "#enddate").datepicker({
            dateFormat: 'yy-mm-dd'
        });
        load_data ();
        function load_data(stdate = '', enddate = ''){
            $("#converting").DataTable({
                "responsive": true,
                "autoWidth": false,
                "precessing": true,
                "serverSide": true,
                "columnDefs": [
                    { "orderable": false, "targets": 0 },
                    {"bSearchable": true, "bVisible": false, "aTargets": [ 3 ]},
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50,
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
                    qty = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    qty = qty.toFixed(2);
                    uprice = api
                        .column( 7 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    uprice = uprice.toFixed(2);

                    tprice = api
                        .column( 8 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    tprice = tprice.toFixed(2);

                    tvat = api
                        .column( 9 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    tvat = tvat.toFixed(2);


                    // Payment Total
                    total = api
                        .column( 10 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    total = total.toFixed(2);

                    cash_return = api
                        .column( 11 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    cash_return = cash_return.toFixed(2);

        
                    // Update footer
                    $( api.column( 6 ).footer() ).html(
                        qty
                    );
                    $( api.column( 7 ).footer() ).html(
                        uprice
                    );
                    $( api.column( 8 ).footer() ).html(
                        tprice
                    );
                    $( api.column( 9 ).footer() ).html(
                        tvat
                    );
                    $( api.column( 10 ).footer() ).html(
                        total
                    );
                    $( api.column( 11 ).footer() ).html(
                        cash_return
                    );
                },
                ajax: {
                    url: '{{ route("get_sales_return_report_date") }}',
                    data:{stdate:stdate, enddate:enddate},
                },
                columns: [
                    {data:'date',},
                    {data:'rinvoice',},
                    {data:'sinvoice',},
                    {data:'serial',},
                    {data:'cname',},
                    {data:'pname',},
                    {data:'qnt',},
                    {data:'uprice',},
                    {data:'tprice',},
                    {data:'vat_amount',},
                    {data:'total',},
                    {data:'cash_return',},
                    {data:'remarks',},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }
        $('#search').click(function(){
            var stdate = $('#stdate').val();
            var enddate = $('#enddate').val();
            if(stdate != '' &&  enddate != ''){
                $('#converting').DataTable().destroy();
                load_data(stdate, enddate);
            }
            else{alert('Both Date is required');}
        });
        $('#refresh').click(function(){
            $('#stdate').val('');
            $('#enddate').val('');
            $('#converting').DataTable().destroy();
            load_data();
        });
        
        // $( function() {
        //     $( "#stdate" ).datepicker({dateFormat: 'yy-mm-dd' });
        // } );
        
        // $( function() {
        //     $( "#enddate" ).datepicker({dateFormat: 'yy-mm-dd' });
        // } );
        
        
        // $('#search').on('click', function(e){
            
        //     e.preventDefault();
            
        //     var stdate = $('#stdate').val();
        //     var enddate = $('#enddate').val();
        			
        // 	var formData = new FormData();
        // 	    formData.append('stdate', stdate);
        // 	    formData.append('enddate', enddate);
        			
        // 	    $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        			
        //         $.ajax({
        //     		  url: "{{ URL::route('get_sales_return_report_date') }}",
        //               method: 'post',
        //               data: formData,
        //               contentType: false,
        //               cache: false,
        //               processData: false,
        //               dataType: "json",
        //     		  beforeSend: function(){
        //         			//$("#wait").show();
        //         		},
        //     		  error: function(ts) {
        //                   $('.custom-table').show();
        //                   $('.custom-table td').remove();
        //                   $('.custom-table tr:last').after(ts.responseText);
                          
        //                   //alert(ts.responseText)
        //               },
        //               success: function(data){
                         
        //                   alert(data);
        //               }
            		   
        //         }); 
        //     });
        
        $('body').on('click', '.delete', function(){
            
            if(confirm("Are you Sure to Delete?")){
                
                // var invoice = $(this).closest('tr').find('.invoice').html();

                var invoice = $(this).data("invoice");
                
                var formData = new FormData();
        	        formData.append('invoice', invoice);
        	        
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('delete_sales_return')}}",
                      method: 'post',
                      data: formData,
                      contentType: false,
                      cache: false,
                      processData: false,
                      dataType: "json",
            		  beforeSend: function(){
                			//$("#wait").show();
                		},
            		  error: function(ts) {
                          
                          alert(ts.responseText);
                          location.reload();
                      },
                      success: function(data){
                         
                          alert(data);
                      }
                }); 
                
            }else{
                e.preventDefault();
            }
            
        });
        
        $('body').on('dblclick', '.custom-table tr', function(){
            
            var purinv = $(this).find('.purinv').html();
            
            window.location.replace("{{Request::root()}}/dashboard/purchase_report_single/"+purinv);
        })
        
    });
    
</script>

@stop

