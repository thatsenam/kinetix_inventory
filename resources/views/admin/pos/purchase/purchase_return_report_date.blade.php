@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper">
    <div class="row">
      <div class="col-12">
          
             <div class="card">
                <div class="card-header">
                    <h3>Purchase Return Report</h3>
                </div>
                <div class="card-body custom-table">
                    
                    <form action="get_purchase_report_date" method="POST">
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
                        
                        <tr><th>Id</th><th>Date</th><th>Invoice</th><th>Supplier</th><th>Product</th><th>Qnt</th><th>Price</th><th>Total</th><th>Action</th></tr>
                        
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
            		  url: "{{ URL::route('get_purchase_return_report_date') }}",
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
            		      $('.custom-table td').remove();
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
                
                var id = $(this).closest('tr').find('.retid').html();
                var invoice = $(this).closest('tr').find('.invoice').html();
                
                
                var formData = new FormData();
        	        formData.append('id', id);
        	        formData.append('invoice', invoice);
        	        
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('delete_purchase_return')}}",
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

<style>

        .custom-modal{
            position: fixed; 
            left: 0; 
            top: 50px; 
            width: 100%; 
            height: 100%; 
            background-color: #e6e6e6; 
            z-index:999; 
            display: none;
        }
        
        #printRest{
            width: 330px;
        }
        
        #printRest tr td{
            font-size: 12px;
            border: 1px solid #000;
        }
        
        .sugg-list {
            width: 100%;
            background-color: #e6e6e6;
            padding: 0;
        }
        
        .sugg-list li{
            width: 100%;
            border-bottom: #FFF;
            color: #6a6a6a;
            list-style-type: none;
            padding: 5px;
        }
        
        .sugg-list li:hover{
            width: 100%;
            background-color: #006fd2;
            color: #FFF;
            padding: 0;
        }
    
        .custom-table{

            width: 100%;
            border-collapse: collapse;
        }
        
        .custom-table tr th{
            background-color: #1bcfb4;
            color: #FFF;
            text-align: center;
            padding: 5px;
        }
        
        .custom-table tr td{
            padding: 5px;
            border: 1px solid #e6e6e6;
            text-align: center;
            font-size: 14px;
        }
        
        .custom-text{
            
            width: 150px;
            border: 1px solid #e6e6e6;
            border-radius: 2px;
            outline: none;
            padding: 5px;
            box-sizing: border-box;
            text-align: center;
        }
        
        .custom-text:focus{
            border-color: dodgerBlue;
        }

        .card .card-body {
            padding: 0.5rem 0.5rem !important;
        }
        
        .content-wrapper {
            
            padding: 0.25rem 0.25rem !important;
        }
    
        .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
            background-color: #FFF;
        }
        
        .box.box-success {
            border-top-color: #6da252;
        }
        
        .input-group .input-group-addon {
            border-radius: 0;
            border-color: #d2d6de;
            background-color: #fff;
        }
        .input-group-addon:first-child {
            border-right: 0;
                border-right-color: currentcolor;
        }
        .input-group .form-control:first-child, .input-group-addon:first-child, .input-group-btn:first-child > .btn, .input-group-btn:first-child > .btn-group > .btn, .input-group-btn:first-child > .dropdown-toggle, .input-group-btn:last-child > .btn-group:not(:last-child) > .btn, .input-group-btn:last-child > .btn:not(:last-child):not(.dropdown-toggle) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group-addon {
            min-width: 41px;
        }
        .input-group-addon {
            padding: 6px 12px;
            font-size: 14px;
            font-weight: 400;
            line-height: 1;
            color: #555;
            text-align: center;
            background-color: #eee;
            border: 1px solid #ccc;
            border-top-color: rgb(204, 204, 204);
            border-right-color: rgb(204, 204, 204);
            border-right-style: solid;
            border-right-width: 1px;
            border-bottom-color: rgb(204, 204, 204);
            border-left-color: rgb(204, 204, 204);
            border-radius: 4px;
        }
        .input-group-addon, .input-group-btn {
            width: 1%;
            white-space: nowrap;
            vertical-align: middle;
        }
        .input-group .form-control, .input-group-addon, .input-group-btn {
            display: table-cell;
        }
        
        .select2-hidden-accessible {
            border: 0 !important;
            clip: rect(0 0 0 0) !important;
            height: 1px !important;
            margin: -1px !important;
            overflow: hidden !important;
            padding: 0 !important;
            position: absolute !important;
            width: 1px !important;
        }
        
        .input-group-btn > .btn {
            position: relative;
        }
        
        .btn-icon {
            height: 34px;
        }
        
        .form-group .select2-container {
            width: 100% !important;
        }
        
        .select2-container {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
        }
        
        .form-group .select2-container .select2-selection--single {
            height: 34px;
            border: 1px solid #d2d6de;
        }
        .form-group .select2-container--default .select2-selection--single {
            border-radius: 0px;
        }
        
        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 28px;
            user-select: none;
            -webkit-user-select: none;
        }
        .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            border: 1px solid #d2d6de;
            border-radius: 0;
            padding: 6px 12px;
            height: 34px;
        }
        
        .form-group .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 1px;
            right: 1px;
            width: 20px;
        }
        
        .form-group .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #555 transparent transparent transparent;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0 4px;
            height: 0;
            left: 50%;
            margin-left: -4px;
            margin-top: -2px;
            position: absolute;
            top: 50%;
            width: 0;
        }
        
        .active{
            color: #FF0;
            background-color: #FFF;
        }
        
        #tblTotal tr td{
            border-top: 0;
        }
           
        .fancy-file {
            display: block;
            position: relative;
        }
        
        .fancy-file div {
            position: absolute;
            top: -1px;
            left: 0px;
            z-index: 1;
            height: 36px;
        }
        
        .fancy-file input[type="text"], .fancy-file button, .fancy-file .btn {
            display: inline-block;
            margin-bottom: 0;
            vertical-align: middle;
        }
        
    }


</style>