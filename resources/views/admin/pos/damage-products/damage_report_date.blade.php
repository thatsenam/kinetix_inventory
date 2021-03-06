@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Damage Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="/dashboard/pos">POS</a></li>
                <li class="breadcrumb-item active">Damage Report</li>
                </ol>
            </div>
        </div>
    <div class="row">
      <div class="col-12">
          
            <div class="justify-content-center d-flex mb-2">
                <div>
                    <label class="text-dark"><b>Start Date</b></label>
                    <input class="form-control" name="stdate" id="stdate" type="text" required autocomplete="off">
                </div>
                <p class="mr-4"></p>
                <div>
                    <label class="text-dark"><b>End Date</b></label>
                    <input class="form-control" name="enddate" id="enddate" type="text" required autocomplete="off">
                </div>
                <div class="mt-auto ml-2">
                    <button type="button" id="search" class="btn btn-primary">Search</button>
                </div>
            </div>

             <div class="card">
                <div class="card-body">
                <table class="table table-bordered" id="converting">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Serial</th>
                                {{-- <th>Supplier</th> --}}
                                <th>Product</th>
                                <th>Qnt</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4">Total Amount</th>
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
    </section>
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
                    // {"bSearchable": true, "bVisible": false, "aTargets": [ 2 ]},
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
        
                    // Quantity
                    qty = api
                        .column( 4 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );

                    // Price Total
                    price = api
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    price = price.toFixed(2);
                    // Grand Total
                    gTotal = api
                        .column( 6 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
                    gTotal = gTotal.toFixed(2);
        
                    // Update footer
                    $( api.column( 4 ).footer() ).html(
                        qty
                    );
                    $( api.column( 5 ).footer() ).html(
                        price
                    );
                    $( api.column( 6 ).footer() ).html(
                        gTotal
                    );
                },
                ajax: {
                    url: '{{ route("get_damage_report_date") }}',
                    data:{stdate:stdate, enddate:enddate},
                },
                columns: [
                    {data:'date',},
                    {data:'dmg_inv',},
                    {data:'serial',},
                    // {data:'sname',},
                    {data:'pname',},
                    {data:'qnt',},
                    {data:'price',},
                    {data:'total',},
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
        
        $('body').on('click', '.delete', function(){
            
            if(confirm("Are you Sure to Delete?")){

                var id = $(this).data("id");
                var invoice = $(this).data("invoice");

                var formData = new FormData();
        	        formData.append('id', id);
        	        formData.append('invoice', invoice);
        	        
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('delete_damage_product')}}",
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
                          location.reload();
                      }
                }); 
                
            }else{
                e.preventDefault();
            }
            
        });
        
        $('body').on('dblclick', '.custom-table tr', function(){
            
            var purinv = $(this).find('.purinv').html();
            
            //window.location.replace("{{Request::root()}}/dashboard/purchase_report_single/"+purinv);
        })
        
    });
    
</script>

@stop
