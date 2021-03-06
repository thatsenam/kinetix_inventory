@extends('admin.pos.master')
        
@section('content')


<div class="content-wrapper" >
    <section class="content">
    <h2 class="ml-2">Check Clearance</h2>
    <div class="row">
      <div class="col-12">
          
             <div class="card">
                <div class="card-body">
                    
                    <form action="get_sales_report_date" method="POST">
                        @csrf
                        <div class="row">
                            
                            <div class="col-4">
                                <label>Check Type</label>
                                
                                <select name="check_type" id="check_type" class="form-control">
                                    <option value="collection">Collection</option>
                                    <option value="payment">Payment</option>
                                </select>
                            </div>
                            
                            <div class="col-2">
                                <label>Date From</label>
                                <input type="text" class="form-control" name="stdate" id="stdate">
                            </div>
                            
                            <div class="col-2">
                                <label>Date To</label>
                                <input type="text" class="form-control" name="enddate" id="enddate">
                            </div>
                            <div class="col-2">
                                <input type="submit" class="btn btn-success btn-lg" id="search" value="Search" style="margin-top:20px;">
                            </div>
                        </div>
                    </form>
                    
                    
                            
                    <table class="collection-table custom-table table table-bordered mt-4" style="display:none;">
                        
                        <tr><th>Date</th><th>Invoice</th><th>Party Name</th><th>Address </th><th>Bank Name</th><th>Account</th><th>Check No</th><th>Amount</th><th>Action</th></tr>
                        
                    </table>
                    
                </div>
             </div>
      </div>
    </div>
    </section>
    
    <div class="row pos_div" style="position: absolute; top: 10px;width: 60%; margin: 0 auto; background: #FFF; height:600px; overflow-y:auto; padding: 10px; z-index: 999999; display: none;">
        
        <div class="col-12" style="position:relative">
            
            <button class="btn btn-primary close" style="position:absolute; top: 10px; right: 10px;">X</button>
            
            <!-------------------------------->
                                    
                    <div id="printdiv" style="font-family:Franklin Gothic Medium; ">
                							
                		<table id="print_add" style="width: margin: 0px auto; padding: 10px; text-align:left; display:none;">
                			<tr>
                			    <td style='width:70%;'>
                							
                        			<span id="company" style='font-size:42px'><?php echo "Company Name";?></span><br />
                        			
                        				<span style='font-size:16px' id="company_add"><?php echo "Street Name"; ?></span><br />
                        							
                        				<!--<span style='font-size:14px'><b>Contact: 01XXXXXXXXX, Contact: 01XXXXXXXXX</b></span>-->
                    							
                				</td>
                				<td id="logoimage" style='width:30%; text-align:right;'>
                						            
                				    <!--<img src='/images/logo_ccb.png' style='width:100px; height:auto;'>-->
                						            
                				</td>
                			</tr>
                		</table>
                						
                						
                		<table id="mid_section" style="width:100%; font-size:16px; display:none;">
                						    
                		    <tr><td style="text-align:center; font-size:22px" colspan="2"><b>INVOICE / BILL</b></td></tr>
                						    
                		    <tr>
                				<td id="cust_add" style="width: 50%; padding-left:10px;"></td>
                        		<td id="others_info" style="text-align: right;"></td>
                    		</tr>
                    						
                    	</table>
                                        
                        <div id="prodlistDiv" class="row" style="margin: 10px 0;">
                            <div class="col-12" style="padding-right: 0 !important; padding-left: 0 !important;">
                                <table id="prodlist" class="price-table custom-table" style="">
                                    <tr><th style="width: 100px;">Item</th><th>price</th><th>Qty</th><th>Total</th><th>Delete</th></tr>
                                </table>
                            </div>
                        </div>
                               
                        <table id="bottom_section" style="margin-top:40px; width: 94%; font-size:16px; display:none;">
                		    <tr>
                		        <td id="bottom_left" style="width:70%; padding-left:30px;"></td>
                        		<td id="bottom_right" style="width:30%;"></td>
                    		</tr>
                    	</table>
                                        
                        <table id="footer_section" style="margin-top:40px; width: 94%; font-size:16px; display:none;">
                		    <tr>
                				<td id="footer1" style="text-align:left; padding:20px;"></td>
                        	</tr>
                        	<tr>
                        	    <td id="footer2" style="text-align:right; padding-top:80px;">
                            			<b>Authorized Signature & Company Stamp</b>
                        		</td>
                    		</tr>
                    		<tr>
                    		    <td id="footer3" style="text-align:center; padding:20px;">
                            		Note: warranty voide if sticker removed item, burn case and physical damage of goods, warranty not cover mouse, keyboard, cable adopter and powe supply unit of casing.
                        		</td>
                    		</tr>
                    	</table>
                             
                                    
                    </div>
            <!--------------------------------->
            
            <button class="btn btn-success btn-lg print" style="margin-top: 20px;"> Print</button>    
            
            
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
            var check_type = $('#check_type').val();
        			
        	var formData = new FormData();
        	    formData.append('stdate', stdate);
        	    formData.append('enddate', enddate);
        	    formData.append('check_type', check_type);
        			
        	    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('get_check_clearance') }}",
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
                          $('.collection-table').show();
                          $('.collection-table td').remove();
                          $('.collection-table tr:last').after(ts.responseText);
                          
                          //alert(ts.responseText)
                      },
                      success: function(data){
                         
                          alert(data);
                      }
            		   
                }); 
            });
        

        
        $('body').on('click', '.clear', function(){

                        var transid = $(this).closest('tr').attr('data-transid');
                        
                        var check_type = $('#check_type').val();
                        
                        $(this).attr('disabled', true);
                			
                		var formData = new FormData();
                			formData.append('transid', transid);
                			formData.append('check_type', check_type);
                			
                			$.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                			
                    	$.ajax({
                    		  url: "{{ URL::route('save_check_clearance') }}",
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
                                    
                                    
                              },
                              success: function(data){
                                
                                    alert(data);
                                    
                              }
                    		   
                    	    });            
            
            
        })
        
        $('.close').click(function(){
            
            $('#cust_add').html("");
            
            $("#prodlist td").remove();
            
            $(".footer-table td").remove();
            
            $('.pos_div').hide();
            
        });
        
        $('.print').click(function(){
            
            $('#print_add').css('width', '332px');
            
            $('#mid_section').css('width', '332px');
            
            $('#prodlist').css('width', '332px');
            
            $('#prodlist td').css('font-size', '12px');
            
            $('.footer-table').css('width', '332px');
            
            $('.footer-table td').css('font-size', '12px');
            
            Print();
            
            location.reload();
        });
        
    });
    
    function Print(){
            
               //////////////printReceipt///////////
			 
				var prtContent = document.getElementById("printdiv");
				var WinPrint = window.open('', '', 'left=0,top=0, toolbar=0,scrollbars=0,status=0');
				WinPrint.document.write(prtContent.innerHTML);
				WinPrint.document.close();
				WinPrint.focus();
				WinPrint.print();
				WinPrint.close();
    }
    
</script>

@stop
