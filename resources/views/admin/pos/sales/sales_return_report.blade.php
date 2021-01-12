@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper">
    <div class="row">
      <div class="col-12">
          
             <div class="card">
                <div class="card-header">
                    <h3>Sales Return Report</h3>
                </div>
                <div class="card-body custom-table">
                    
                    <form action="" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-2">
                                <label>Date From</label>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control" name="stdate" id="stdate">
                            </div>
                            <div class="col-2">
                                <label>Date To</label>
                            </div>
                            <div class="col-2">
                                <input type="text" class="form-control" name="enddate" id="enddate">
                            </div>
                            <div class="col-2">
                                <input type="submit" class="btn btn-success btn-lg" id="search" value="Search">
                            </div>
                        </div>
                    </form>
                    
                    
                        
                    <table class="custom-table" style="display:none;">
                        
                        <tr><th>Date</th><th>Return Inv</th><th>Sales Inv</th><th>Customer</th><th>Product</th><th>qnt</th><th>U.Price</th><th>T.Price</th><th>Total</th><th>Cash Return</th><th>Action</th></tr>
                        
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
        
        $( function() {
            $( "#stdate" ).datepicker({dateFormat: 'yy-mm-dd' });
        } );
        
        $( function() {
            $( "#enddate" ).datepicker({dateFormat: 'yy-mm-dd' });
        } );
        
        
        $('#search').on('click', function(e){
            
            e.preventDefault();
            
            var stdate = $('#stdate').val();
            var enddate = $('#enddate').val();
        			
        	var formData = new FormData();
        	    formData.append('stdate', stdate);
        	    formData.append('enddate', enddate);
        			
        	    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('get_sales_return_report_date') }}",
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
                          $('.custom-table').show();
                          $('.custom-table tr:last').after(ts.responseText);
                          
                          //alert(ts.responseText)
                      },
                      success: function(data){
                         
                          alert(data);
                      }
            		   
                }); 
            });
        
        $('body').on('click', '.delete', function(){
            
            if(confirm("Are you Sure to Delete?")){
                
                var invoice = $(this).closest('tr').find('.invoice').html();
                
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
                          //location.reload();
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

<style>

.custom-table{

    width: 100%;
    border-collapse: collapse;
}

.custom-table tr th{
    background-color: #1bcfb4;
    padding: 5px;
    color: #FFF;
    text-align: center;
}

.custom-table tr td{
    padding: 5px;
    border: 1px solid #e6e6e6;
    text-align: center;
    font-size: 14px;
}

.col-2{
    padding-left:10px !important;
    padding-right:10px !important;
    text-align: center;
}

</style>