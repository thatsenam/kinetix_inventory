@extends('admin.master')
        
@section('content')

<div class="content-wrapper" >
    <div class="row">
      <div class="col-12">
          
             <div class="card">
                <div class="card-header">
                    <h3>Client Registration</h3>
                </div>
                <div class="card-body custom-table">
                    
                    <form action="" method="POST">
                        @csrf
                        
                        <table class="table" style="width: 600px; margin: 0 auto;">
                            <tr>
                                <td>
                                    <label>Client's Name</label>
                                </td>
                                
                                <td style="position: relative;">
                                    <input type="text" class="form-control" id="client_name" name="client_name">
                                    
                                    <div id="client_div" style="width: 100%; display: none; position: absolute; top: 40px; left: 0; z-index: 999;"></div>
                                            
                                    <input type="hidden" name="client_id" id="client_id" value="0" class="form-control">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Shop Name</label>
                                </td>
                            
                                <td>
                                    <input type="text" class="form-control" id="shop_name" name="shop_name">
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Mobile</label>
                                </td>
                                
                                <td>
                                    <input type="text" class="form-control" id="mobile" name="mobile">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Address</label>
                                </td>
                                
                                <td>
                                    <textarea name="address" id="address" class="form-control" rows="5"></textarea>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <label>Type</label>
                                </td>
                                
                                <td>
                                    <select class="form-control" id="client_type" name="client_type">
                                        <option value="trial">Trial</option>
                                        <option value="basic">Basic</option>
                                        <option value="premium">Premium</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan="2">
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
       
        
                $('#client_name').keyup(function(e){
                    
                    if(e.which == 40 || e.which == 38){
            
                        $("#client_name").blur();
                        
                        $('.client-list').find("li:first").focus().addClass('activex').siblings().removeClass();
                        
                            $('.client-list').on('focus', 'li', function() {
                                $this = $(this);
                                $this.addClass('activex').siblings().removeClass();
                                $this.closest('.client-list').scrollTop($this.index() * $this.outerHeight());
                            });
                                
                            $('.client-list').on('keydown', 'li', function(e) {
                                
                                    $this = $(this);
                                    if (e.keyCode == 40) {   
                                        
                                        $this.next().focus();
                                        
                                        return false;
                                    } else if (e.keyCode == 38) {        
                                        $this.prev().focus();
                                        return false;
                                    }
                            }); 
                                
                            $('.client-list').on('keyup', function(e){
                                
                                if(e.which == 13){
                                   
                                   var id = $(".activex").attr('data-id');
                                   var name = $(".activex").attr("data-name");
                                   var shop = $(".activex").attr("data-shop");
                                   var phone = $(".activex").attr("data-phone");
                                   var address = $(".activex").attr("data-address");
                                    
                                   $('#client_id').val(id);
                                   $('#client_name').val(name);
                                   $('#shop_name').val(shop);
                                   $('#mobile').val(phone);
                                   $('#address').val(address);
                                    
                                    $("#client_name").focus();
                                    
                                    $("#client_div").hide();
                                    
                                    //window.location.replace("{{Request::root()}}/admin/editman/"+val);
                                    
                                }
                             });
                        
                        return false;
                    }
                    
                    var s_text = $(this).val();
                    
                    var formData = new FormData();
                    
                    formData.append('s_text', s_text);
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    
            		$.ajax({
            		  url: "{{ URL::route('get_seller') }}",
                      method: 'post',
                      data: formData,
                      contentType: false,
                      cache: false,
                      processData: false,
                      dataType: "json",
            		  beforeSend: function(){
                			$("#wait").show();
                		},
            		  error: function(ts) {
                          
                          //alert(ts.responseText);
                          
                          $('#client_div').show();
                          $('#client_div').html(ts.responseText);
                          
                          $("#wait").hide();
                           
                      },
                      success: function(data){
                        $("#wait").hide();
                          
                          $('#client_div').show();
                          $('#client_div').html(data);
                          
                          //alert(data);
                          
                      }
            		   
            		}); 
                    
                }); 
        

        
        $('#save').click(function(){
            
            $(this).attr('disabled', true);
            
            var client_id = $('#client_id').val();
            var client_name = $('#client_name').val();
            var shop_name = $('#shop_name').val();
            var mobile = $('#mobile').val();
            var address = $('#address').val();
            var client_type = $('#client_type').val();
            
            
            var formData = new FormData();
                formData.append('client_id', client_id);
                formData.append('client_name', client_name);
        	    formData.append('shop_name', shop_name);
        	    formData.append('mobile', mobile);
        	    formData.append('address', address);
        	    formData.append('client_type', client_type);

            
             $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('save_client_registration') }}",
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
                          $('#save').attr('disabled', false);
                          
                      },
                      success: function(data){
                         
                          alert(data);
                          $('#save').attr('disabled', false);
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


.sugg-list{
            width: 100%;
            background-color: #e6e6e6;
            padding: 0;
        }
        
.sugg-list li{
            width: 100%;
            border-bottom: #FFF;
            color: #6a6a6a;
            border-bottom: 1px solid #fff;
            list-style-type: none;
            padding: 5px;
        }
        
.sugg-list li:hover{
            width: 100%;
            background-color: #006fd2;
            color: #FFF;
            padding: 0;
        }

.col-2{
    padding-left:10px !important;
    padding-right:10px !important;
    text-align: center;
}



</style>
