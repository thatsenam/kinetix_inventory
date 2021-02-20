
@extends('admin.pos.master')

@section('title','Sales Return')

@section('content')

@if($AccHeads <= 0 || $GenSettings ==null)
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="card" style="height: 100px;width: 100%;padding: 30px;color: red;">
                        <h1>Please, Configure General Settings and create Acoounts demo heads from before proceed.</h1>
                    </div>
                </div>
            </div>
        </section>
    </div>
@else

<div class="main-panel">
    <div class="content-wrapper">
    <!-- Page Title Header Starts-->
        
        
        <img id="wait" src="{{asset('images/wait2.gif')}}" style="width:300px; margin: 0 auto; position:fixed; top:200px; left:40%; z-index:999; display:none">
        
        
        <div class="box-body">
            <div class="row">
              <div class="col-12" style="position: relative;">
                 <form action="{{route('sales_return_save')}}" method="POST">
                     @csrf
                     <div class="card" style="min-height: 500px;">
                         <div class="card-header">
                            <h3>Sales Return</h3>
                         </div>
                         <div class="card-body">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="row">
                                    @if($warehouses->count()>1)
                                        <div class="col-5">
                                            <select name="warehouse_id" id="warehouse_id" class="form-control">
                                                <option value="" disabled selected>Select Warehouse</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group" style="position: relative;">
                                                <input type="text" name="cust_phone" id="cust_phone" class="form-control" placeholder="Customer Phone">
                                                <div id="cust_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                            
                                                <input type="hidden" name="cust_id" id="cust_id" value="0" class="form-control">
                                                <input type="hidden" name="cust_name" id="cust_name" value="" class="form-control">
                                                
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group"  style="position: relative;">
                                                <input type="text" name="invoice" id="invoice" class="form-control" placeholder="Invoice">
                                                <div id="memo_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ $warehouse_id }}">
                                        <div class="col-6">
                                            <div class="form-group" style="position: relative;">
                                                <input type="text" name="cust_phone" id="cust_phone" class="form-control" placeholder="Customer Phone">
                                                <div id="cust_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                            
                                                <input type="hidden" name="cust_id" id="cust_id" value="0" class="form-control">
                                                <input type="hidden" name="cust_name" id="cust_name" value="" class="form-control">
                                                
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group"  style="position: relative;">
                                                <input type="text" name="invoice" id="invoice" class="form-control" placeholder="Invoice">
                                                <div id="memo_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                            </div>
                                        </div>
                                    @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group"  style="position: relative;">
                                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <div class="form-group" style="position: relative;">
                                                <input type="text" class="form-control" placeholder="Search Product" id="search">
                                                <div id="products_div" style="display: none; position: absolute; top: 30px; left: 0; width: 100%; z-index: 999;"></div>
                                                <input type="hidden" name="pid_hid" id="pid_hid">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="height:350px; overflow-y: auto; ">
                                        <div class="col-12" style="padding-right: 0 !important;">
                                            <table class="price-table custom-table" style="">
                                                <tr><th style="width: 100px;">Item</th><th>price</th><th>Qty</th><th>Total</th><th>Delete</th></tr>
                                                
                                            </table>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-5">
                                    
                                    <div class="row">
                                        <div class="col-4">
                                          <div class="form-group">
                                            <input type="text" name="price" id="price" class="form-control" placeholder ="Price">
                                            
                                          </div>
                                        </div>
                                        <div class="col-4">
                                          <div class="form-group">
                                            <input type="text" name="qnt" id="qnt" class="form-control" placeholder ="Quantity">
                                            
                                          </div>
                                        </div>
                                        <div class="col-4">
                                          <div class="form-group">
                                            <input type="text" name="date" id="date" class="form-control" placeholder ="date" value="<?php echo date("Y-m-d");?>" style="padding: 0.94rem 0.5rem;">
                                            
                                          </div>
                                        </div>
                                    </div>
                            
                                    <div class="row">
                                        
                                        <div class="col-6">
                                          <div class="form-group">
                                             <label>Total</label>
                                             <input type="text" name="total" id="total" class="form-control" placeholder =""  value="0">
                                             <input type="hidden" name="hid_total" id="hid_total" class="form-control" placeholder ="" value="0">
                                             
                                          </div>
                                        </div>
                                        
                                        <div class="col-6">
                                          <div class="form-group">
                                             <label>Payment</label>
                                             <input type="text" name="payment" id="payment" class="form-control" placeholder ="" value="0">
                                          </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            <label>Remarks</label>
                                            <textarea class="form-control" id="remarks" name="remarks" rows='5'></textarea>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <div style="width: 120px; margin: 50px auto;">
                                                <input type="button" class="btn btn-danger btn-lg" id="cancel" value="Cancel">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div style="width: 120px; margin: 50px auto;">
                                                <input type="button" class="btn btn-success btn-lg" id="save" value="Save">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     
                </form> 

        <!-- Modal -->
        <div class="modal fade" id="serial_modal" tabindex="-1" role="dialog" aria-labelledby="serial_modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100" id="serial_modalLabel">Product Serial</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div id="serial_input">

                        </div>
                        <div class="float-right">
                            <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                            <button type="button" id="serial_save" class="btn btn-primary">SAVE</button>
                        </div>
                    </form>
                </div>
            
            </div>
            </div>
        </div>
                     <!-------Customer Entry----------->
        
                    
        
                     
                  </div>
                </div>
                
        </div>
        
    </div>
          <!-- content-wrapper ends -->
          
</div>
<!-- main-panel ends -->

@endif
    
@endsection

@section('page-js-script')

    <script type="text/javascript">

        var product_id;
        var product_serial;
        var serial_qty;
        var serial_array = {};
        
        $(document).ready(function() {
            
            $('#date').datepicker({dateFormat: 'yy-mm-dd'});
               
               
            $("#cust_phone").keyup(function(e){
                    
                if(e.which == 40 || e.which == 38){
                
                    $("#cust_phone").blur();
                                
                    $('.customer-list').find("li:first").focus().addClass('active').siblings().removeClass();
                                
                    $('.customer-list').on('focus', 'li', function() {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.customer-list').scrollTop($this.index() * $this.outerHeight());
                    });
                                        
                    $('.customer-list').on('keydown', 'li', function(e) {
                                        
                        $this = $(this);
                        if (e.keyCode == 40) {   
                                                
                            $this.next().focus();
                                                
                                return false;
                            }else if (e.keyCode == 38) {        
                                $this.prev().focus();
                                return false;
                            }
                        }); 
                                        
                        $('.customer-list').on('keyup', function(e){
                            if(e.which == 13){
                                            
                                var phone = $(this).find(".active").attr("data-phone");
                                var name = $(this).find(".active").attr("data-name");
                                var id = $(this).find(".active").attr("data-id");
                                           
                                $('#cust_phone').val(phone);
                                $('#cust_name').val(name);
                                $('#cust_id').val(id);
                                            
                                $("#search").focus();
                                $("#cust_div").hide();
                                            
                                //window.location.replace("{{Request::root()}}/admin/editcat/"+val);
                                            
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
                		  url: "{{ URL::route('get_customer') }}",
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
                              
                              $('#cust_div').show();
                              $('#cust_div').html(ts.responseText);
                              //alert((ts.responseText));
                          },
                          success: function(data){
                            
                              $('#cust_div').show();
                              $('#cust_div').html(ts.responseText);
                              //alert(data);
                              
                          }
                		   
                	}); 
                       
                }); 
                
                $("#invoice").keyup(function(e){
                    
                    if(e.which == 40 || e.which == 38){
                    
                        $("#invoice").blur();
                                    
                        $('.invoice-list').find("li:first").focus().addClass('active').siblings().removeClass();
                                    
                        $('.invoice-list').on('focus', 'li', function() {
                            $this = $(this);
                            $this.addClass('active').siblings().removeClass();
                            $this.closest('.invoice-list').scrollTop($this.index() * $this.outerHeight());
                        });
                                            
                        $('.invoice-list').on('keydown', 'li', function(e) {
                                            
                            $this = $(this);
                            if (e.keyCode == 40) {   
                                                    
                                $this.next().focus();
                                                    
                                    return false;
                                }else if (e.keyCode == 38) {        
                                    $this.prev().focus();
                                    return false;
                                }
                            }); 
                                            
                            $('.invoice-list').on('keyup', function(e){
                                if(e.which == 13){
                                                
                                    var invoice = $(this).find(".active").attr("data-invoice");
                                               
                                    $('#invoice').val(invoice);
                                   
                                    $("#search").focus();
                                    $("#memo_div").hide();
                                                
                                    //window.location.replace("{{Request::root()}}/admin/editcat/"+val);
                                                
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
                    		  url: "{{ URL::route('get_invoice') }}",
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
                                  $('#memo_div').show();
                                  $('#memo_div').html(ts.responseText);
                                  
                              },
                              success: function(data){
                                  //alert(data);
                                  $('#memo_div').show();
                                  $('#memo_div').html(ts.responseText);
                                  
                                  
                              }
                    		   
                    	}); 
                       
                }); 
                
                
            $("#barcode").keypress(function(e){
                
                if(e.which == 13){
                    
                    var s_text = $(this).val();
            			
            		var formData = new FormData();
            			formData.append('s_text', s_text);
            			
            			$.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
            			
                	$.ajax({
                		  url: "{{ URL::route('get_barcode') }}",
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
                              
                                /* $('#search').val(name);
                                $('#pid_hid').val(id);
                                $('#price').val(price);
                                            
                                $("#price").focus(); */
                          },
                          success: function(data){
                              
                             //var obj = JSON.parse(JSON.stringify(data));
                             var obj = data;
                              
                             var name = obj.name;
                             var id = obj.id;
                             var price = obj.price;
                             product_id = id;
                             product_serial = obj.serial;
                             
                              
                                $('#search').val(name);
                                $('#pid_hid').val(id);
                                $('#price').val(price);
                                            
                                $("#price").focus(); 
                              
                          }
                		   
                	    }); 
                    }   
                }); 
                
                
                $("#search").keyup(function(e){
                    
                    if(e.which == 40 || e.which == 38){
                    
                    $("#search").blur();
                                
                    $('.products-list').find("li:first").focus().addClass('active').siblings().removeClass();
                                
                    $('.products-list').on('focus', 'li', function() {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.products-list').scrollTop($this.index() * $this.outerHeight());
                    });
                                        
                    $('.products-list').on('keydown', 'li', function(e) {
                                        
                        $this = $(this);
                        if (e.keyCode == 40) {   
                                                
                            $this.next().focus();
                                                
                                return false;
                            }else if (e.keyCode == 38) {        
                                $this.prev().focus();
                                return false;
                            }
                        }); 
                                        
                        $('.products-list').on('keyup', function(e){
                            if(e.which == 13){
                                        
                                var id = $(this).find(".active").attr("data-id");    
                                var name = $(this).find(".active").attr("data-name");
                                var price = $(this).find(".active").attr("data-price");  
                                product_id = id;
                                product_serial = $(this).find(".active").attr("data-serial");
                                
                                $('#search').val(name);
                                $('#pid_hid').val(id);
                                $('#price').val(price);
                                            
                                $("#price").focus();
                                $("#products_div").hide();
                                            
                                //window.location.replace("{{Request::root()}}/admin/editcat/"+val);
                                            
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
                		  url: "{{ URL::route('get_products') }}",
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
                              
                              $('#products_div').show();
                              $('#products_div').html(ts.responseText);
                              //alert((ts.responseText));
                          },
                          success: function(data){
                            
                              $('#products_div').show();
                              $('#products_div').html(ts.responseText);
                              //alert(data);
                              
                          }
                		   
                	}); 
                   
                });

            $('#serial_save').click(function (e) {
                var i, val = [];
                var qnt = $('#qnt').val();
                var access = 0;

                for(i=0; i<serial_qty; i++)
                {
                    var ser = $('#serial-'+i).val();
                    if(ser == '')
                    {
                        $('#serial-'+i).addClass("is-invalid");
                        access = 1;
                    }
                    else
                    {
                        $('#serial-'+i).removeClass("is-invalid");
                    }
                }
                if(access == 1)
                {
                    return;
                }
                for(i=0; i<serial_qty; i++)
                {
                    var ser = $('#serial-'+i).val();
                    
                    val[i] = ser;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
                }
                serial_array[product_id] = val;

                $('#serial_modal').modal('hide');
                
                console.log(JSON.stringify(serial_array));
            });
                
               
                $('#qnt').on('keyup', function(e){
            
                    e.preventDefault();
                    
                    if(e.which == 13){
                        
                        var id = $('#pid_hid').val();
                        var cust_id = $('#cust_id').val();
                        var name = $('#search').val();
                        var qnt = Number($(this).val());
                        var price = Number($('#price').val());
                        var totalPrice = Number($('#hid_total').val());

                        serial_qty = qnt;

                        var warehouse_id = $('#warehouse_id').val();
                        if(warehouse_id == null){
                            alert('Please Select Warehouse');
                            return ;
                        }

                        if(product_serial == 1)
                        {
                            $("#serial_input").empty();
                            for(i=0;i<qnt;i++)
                            {
                                $('#serial_input').append(
                                    "<div class='form-group row'>" +
                                        "<label for='serial-"+i+"' class='col-3 col-form-label'>Serial "+(i+1)+"</label>" +
                                        "<div class='col-9'>" + 
                                            "<input list='serial_suggest' type='text' class='form-control' id='serial-"+i+"' required>" +
                                            "<datalist id='serial_suggest'></datalist>" +
                                        "</div>" +
                                    "</div>"
                                );
                            }

                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $.ajax({
                                url: "/get_serial_sold/" + product_id,
                                method: 'get',
                                data: '',
                                contentType: false,
                                cache: false,
                                processData: false,
                                dataType: "json",
                                beforeSend: function() {},
                                error: function(ts) {},
                                success: function(response) {
                                    // var obj = JSON.parse(JSON.stringify(response));
                                    serial_unsold = response;
                                    console.log(serial_unsold);
                                    $("datalist").empty();
                                    var j; 
                                    for (j = 0; j < serial_unsold.length; ++j) {
                                        $('datalist').append(
                                            "<option>" + serial_unsold[j] + "</option>"
                                        );
                                    }

                                    $('#serial_modal').modal('toggle');

                                    $('#serial_modal').on('shown.bs.modal', function () {
                                            $('#serial-'+0).trigger('focus')
                                    });
                                }
                            });
                        }
                        
                        if(cust_id == 0){
                            alert("No customer selected. Please select a customer & try again!");
                            return false;
                        }
                        
                        if(name == '' || qnt == '' || price == ''){
                            
                            alert("Please Fillup All Fields ");
                            return false;
                        }
                        
                        add_product_to_table(id, name, qnt, price, totalPrice, 0, 0);
                    
                    }
                    
                });
             
              
                
                $("#payment").on("change keyup paste", function(){
                
                    var hid_total = Number($('#hid_total').val());
                    
                    var payment = Number($(this).val());
                    
                    $('#total').val(hid_total - payment); 
                    
                });
                
                $('#cancel').click(function(){
                    
                    $('#search').val("");
                    $('#pid_hid').val("");
                    $('#price').val("");
                    $('#cust_phone').val("");
                    $('#cust_name').val("");
                    $('#cust_id').val("");
                    $('#qnt').val("");
                    $('#total').val("");
                    $('#hid_total').val("");
                    $('#payment').val("");
                                            
                    $("#cust_div").hide();
                    $("#products_div").hide();
                    
                    location.reload();
                    
                });
               
                        
        //////Save///////////
         $('input[name="qnt"]').focus(function() {
             $("#save").removeAttr('disabled');
         });
        
        $('#save').click(function(e){

           
            var i = 0;
            
            var cartData = [];

            var warehouse_id = $('#warehouse_id').val();
            if(warehouse_id == null){
                alert('Please Select Warehouse');
                return ;
            }
    
            $(this).attr('disabled', true);
            
            $('.price-table tr td').each(function(){
               
               var take_data = $(this).html();
              
               if($(this).attr('data-prodid') != ''){prodid = $(this).attr('data-prodid'); cartData.push(prodid);}
             
               if($(this).attr("class") == 'qnty'){quantity =$(this).html(); cartData.push(quantity);}
             
               if($(this).attr("class") == 'uprice'){uprice = $(this).html(); cartData.push(uprice);}
              
               if($(this).attr("class") == 'totalPriceTd'){totalPriceTd = $(this).html(); cartData.push(totalPriceTd);} 
               
               i = i +1;
           });
           
           cartData = cartData.filter(item => item);
         
           if(i<5){
               alert("Please Choose A Product.");
               return false;
           }


           
            var fieldValues = {};
            
            fieldValues.warehouse_id = $('#warehouse_id').val();
            fieldValues.cust_id = $('#cust_id').val();
            fieldValues.cust_name = $('#cust_name').val();
            fieldValues.cust_phone = $('#cust_phone').val();
            fieldValues.invoice = $('#invoice').val();
            fieldValues.total = $('#total').val();
            fieldValues.hid_total = $('#hid_total').val();
            fieldValues.remarks = $('#remarks').val();
            fieldValues.payment = $('#payment').val();
            fieldValues.date = $('#date').val();
            
            if(cust_id<=0){
               alert("Please select a customer!");
               return false;
            }
           
            var formData = new FormData();
           
            formData.append('fieldValues', JSON.stringify(fieldValues)); 
            formData.append('cartData', JSON.stringify(cartData)); 
            formData.append('serialArray', JSON.stringify(serial_array));

            product_id = '';
            product_serial = '';
            serial_qty = '';
            serial_array = {};
            serial_unsold = '';
            warranty = '';
            product_stock = '';
           		
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
          
          $.ajax({
                url: "{{ URL::route('sales_return_save') }}",
                method: 'post',
                data: formData,
                //data: cartData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                beforeSend: function(){
                	$("#wait").show();
                },
                error: function(ts) {
                    
                    alert(ts.responseText);
                    
                    $('.price-table td').remove();
                    
                    $("#wait").hide();
                        
                    $('#save').attr('disabled', false);
                    
                    location.reload();    
                
                    },
                    success: function(data){
                        
                        alert(data);
                            
                        }
                    }); 
              
                  e.preventDefault(); 
               }); 
                   
                $('body').on('click', '.delete', function(e){
            
                    var totalPriceTd = Number($(this).closest('tr').find('.totalPriceTd').html());
                    var productID = Number($(this).closest('tr').find("td").attr('data-prodid'));

                    delete serial_array[productID];
                    
                    var totalPrice = $('#hid_total').val();
                    
                    
                    totalPrice = Number(totalPrice - totalPriceTd);
                    
                    $('#hid_total').val(totalPrice);
                    
                    $('#total').val(totalPrice);
                    
                    $(this).closest('tr').remove();
                    
                }); 
               
               $('body').on('click', function(){
            
                    $('#products_div').hide();
                    $('#cust_div').hide();
                    
                });
        });
            
        function selectCustomer(id, phone, name){
                 
            $('#cust_phone').val(phone);
            $('#cust_name').val(name);
            $('#cust_id').val(id);
                                            
            $("#search").focus();
            $("#cust_div").hide();
        }
        
        function selectProducts(id, name, price, serial){

            $('#search').val(name);
            $('#pid_hid').val(id);
            $('#price').val(price);
            product_id = id;
            product_serial = serial;  
                                        
            $("#price").focus();
            $("#products_div").hide();
            
        }
            
        function add_product_to_table(id, name, qnt, price, totalPrice, pvat, vat){
        
            var id = id;
            var name = name;
            var qnt = Number(qnt);
            var price = Number(price);
            var totalPrice = Number(totalPrice);

            var total = (price * qnt);
             
            $('.price-table').show();
            
            $('.price-table').append("<tr><td data-prodid='"+id+"' style='width:200px;'>"+name+"</td><td class='uprice'>"+price+"</td><td class='qnty'>"+qnt+"</td><td class='totalPriceTd'>"+total+"</td><td><i class='delete mdi mdi-delete'></i></td></tr>");
            
            totalPrice = Number(totalPrice + total);
            
            $('#hid_total').val(totalPrice);
        
            $('#total').val(totalPrice);
        
            $('#search').val("");
            
            $('#price').val("");
            
            $('#qnt').val("");
            
            $('#barcode').val("");
            
            $('#search').focus();
        }
            
    </script>
@stop

<style>
        
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
