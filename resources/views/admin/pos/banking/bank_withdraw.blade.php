@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper" >
    <div class="row">
      <div class="col-12">
          
             <div class="card">
                <div class="card-header">
                    <h3>Bank Withdraw</h3>
                </div>
                <div class="card-body custom-table">
                    
                    <form action="" method="POST">
                        @csrf
                        
                        <table class="table" style="width: 600px; margin: 0 auto;">
                            <tr>
                                <td>
                                    <select name="bank_id" id="bank_id" class="form-control">
                                        <option>Select Bank</option>
                                        
                                        @foreach($bank_info as $row)
                                        
                                        <option value = {{$row->id}}>{{$row->name}}</option>
                                        
                                        @endforeach
                                        
                                    </select>
                                </td>
                                <td>
                                    <select name="account_id" id="account_id" class="form-control">
                                        
                                        <option>Select Account</option>
                                        
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <input type="text" name="balance" id="balance" class="form-control" placeholder="Balance">
                                </td>
                                <td>
                                    <input type="text" name="check_no" id="check_no" class="form-control" placeholder="Check No">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
                                </td>
                                <td>
                                    <input type="text" name="date" id="date" class="form-control" value="<?php echo date('Y-m-d');?>">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <input type="text" name="remarks" id="remarks" class="form-control" placeholder="Remarks">
                                </td>
                                <td>
                                    <input type="button" class="btn btn-success btn-lg" id="save" value="Save">
                                </td>
                            </tr>
                            
                        </table>
                        
                    </form>
                    
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
            $( "#date" ).datepicker({dateFormat: 'yy-mm-dd' });
        } );
        
        $('#bank_id').change(function(){
            
            var bank_id = $(this).val();
            
            var formData = new FormData();
        	    formData.append('bank_id', bank_id);
            
             $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('get_bank_acc') }}",
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
            		      
            		      //alert(ts.responseText)
            		      
                          $('#account_id option').remove();
                          $('#account_id').append(ts.responseText);
                          
                      },
                      success: function(data){
                         
                          alert(data);
                      }
            		   
                }); 
            
        });
        
        $('#account_id').change(function(){
            
            var account_id = $(this).val();
            
            var formData = new FormData();
        	    formData.append('account_id', account_id);
            
             $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('get_bank_balance') }}",
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
            		      
            		      //alert(ts.responseText);
                          //$('#balance').val(ts.responseText);
                          
                      },
                      success: function(data){
                         
                          //alert(data);
                          $('#balance').val(data);
                      }
            		   
                }); 
            
        });
        
        $('#save').click(function(){
            
            
            var bank_id = $('#bank_id').val();
            var bank_name = $('#bank_id option:selected').text();
            var account_id = $('#account_id').val();
            var account_name = $('#account_id option:selected').text();
            var balance = $('#balance').val();
            var check_no = $('#check_no').val();
            var amount = $('#amount').val();
            var date = $('#date').val();
            var remarks = $('#remarks').val();
            
            var formData = new FormData();
                formData.append('bank_id', bank_id);
                formData.append('bank_name', bank_name);
        	    formData.append('account_id', account_id);
        	    formData.append('account_name', account_name);
        	    formData.append('balance', balance);
        	    formData.append('check_no', check_no);
        	    formData.append('amount', amount);
        	    formData.append('date', date);
        	    formData.append('remarks', remarks);
            
             $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('save_bank_withdraw') }}",
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
    border: 1px solid #e6e6e6;
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