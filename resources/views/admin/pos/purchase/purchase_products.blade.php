@extends('admin.pos.master')
        
@section('content')


<div class="content-wrapper"> 
    <section class="content">
    <h2 class="ml-3">Purchase Products</h2>
    <div class="box-body">
        <div class="row">
          <div class="col-12" style="position: relative;">
             <form action="{{route('save_purchase_products')}}" method="POST">
                 @csrf
                 <div class="card" style="min-height: 500px;">
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
                                            <input type="text" name="supp_name" id="supp_name" class="form-control" placeholder="Supplier Name" autocomplete="off">
                                            <div id="supp_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                                
                                            <input type="hidden" name="supp_id" id="supp_id" value="0" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group"  style="position: relative;">
                                            <input type="text" name="supp_memo" id="supp_memo" class="form-control" placeholder="Memo No" autocomplete="off">
                                            <div id="memo_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ $warehouse_id }}">
                                    <div class="col-8">
                                        <div class="form-group" style="position: relative;">
                                            <input type="text" name="supp_name" id="supp_name" class="form-control" placeholder="Supplier Name" autocomplete="off">
                                            <div id="supp_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                                
                                            <input type="hidden" name="supp_id" id="supp_id" value="0" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group"  style="position: relative;">
                                            <input type="text" name="supp_memo" id="supp_memo" class="form-control" placeholder="Memo No" autocomplete="off">
                                            <div id="memo_div" style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                        </div>
                                    </div>
                                @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group"  style="position: relative;">
                                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-9">
                                        <div class="form-group" style="position: relative;">
                                            <input type="text" class="form-control" placeholder="Search Product" id="search" autocomplete="off">
                                            <div id="products_div" style="display: none; position: absolute; top: 30px; left: 0; width: 100%; z-index: 999;"></div>
                                            <input type="hidden" name="pid_hid" id="pid_hid">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="height:350px; overflow-y: auto; ">
                                    <div class="col-12" style=" padding-right: 0 !important;">
                                        <table class="price-table custom-table" style="">
                                            <tr><th>SL</th><th style="width: 100px;">Item</th><th>Price</th><th>Qty</th><th>IVA</th><th>Total</th><th>Delete</th></tr>
                                            
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
                                         <label>Amount</label>
                                         <input type="text" name="amount" id="amount" class="form-control" placeholder ="">
                                        
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-group">
                                         <label>Discount</label>
                                         <input type="text" name="discount" id="discount" class="form-control" placeholder ="" value="0">
                                        
                                      </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                      <div class="form-group">
                                         <label>Total IVA</label>
                                         <input type="text" name="total_vat" id="total_vat" class="form-control" readonly="true" value="0">
                                      </div>
                                    </div>
                                    <div class="col-6">
                                      <div class="form-group">
                                         <label>Total</label>
                                         <input type="text" name="total" id="total" class="form-control" placeholder ="">
                                         <input type="hidden" name="hid_total" id="hid_total" class="form-control" placeholder ="">
                                         
                                      </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-6">
                                      <div class="form-group">
                                         <label>Payment</label>
                                         <input type="text" name="payment" id="payment" class="form-control" placeholder ="" value="0"> 
                                      </div>
                                    </div>
                                    <div class="col-6">
                                        <div style="width: 120px; margin: 50px auto;">
                                            <input type="button" class="btn btn-success" id="pur_save" value="Save">
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

                <div class="modal fade" id="square_foot_modal" tabindex="-1" role="dialog" aria-labelledby="square_foot_modalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title float-center" id="square_foot_modalLabel">পরিমান</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div> 
                        <div class="modal-body">
                
                            <div class="form-group row">
                                <label for="quantity" class="col-sm-4 col-form-label">পরিমান</label>
                                <div class="col-sm-8">
                                  <input type="text" class="form-control" id="quantity">
                                </div>
                            </div>
                
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">বাদ</button>
                          {{-- <button type="button" class="btn btn-primary">OK</button> --}}
                        </div>
                      </div>
                    </div>
                  </div>
          </div>
        </div>
    </div>
    </section>
</div>

@endsection

@section('page-js-script')

<script type="text/javascript">

    var product_id;
    var product_serial;
    var product_vat;
    var serial_qty;
    var serial_array = {};

    $(document).ready(function(){

        $('#serial').on('keyup', function(e){
            
            e.preventDefault();
            
            if(e.which == 13){
                alert('access');
            }
            
        });
        
        $('#date').datepicker({dateFormat: 'yy-mm-dd'});
        
        $("#discount").on("change keyup paste", function(){
                
            var amount = Number($('#amount').val());
            var total_vat = Number($('#total_vat').val());
                    
            var discount = Number($(this).val());
                    
            $('#total').val(amount +total_vat - discount); 
                    
        });
        
        $("#payment").on("change keyup paste", function(){
                
            var amount = Number($('#amount').val());

            var total_vat = Number($('#total_vat').val());
                    
            var payment = Number($(this).val());
            
            var discount = Number($('#discount').val());
                    
            $('#total').val(amount +total_vat - payment - discount); 
                    
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
                        product_vat = $(this).find(".active").attr("data-vat");

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
            		  url: "{{ URL::route('get_purchase_products') }}",
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
        
        
        $("#supp_name").keyup(function(e){
                    
            if(e.which == 40 || e.which == 38){
            
            $("#supp_name").blur();
                        
            $('.supplier-list').find("li:first").focus().addClass('active').siblings().removeClass();
                        
            $('.supplier-list').on('focus', 'li', function() {
                $this = $(this);
                $this.addClass('active').siblings().removeClass();
                $this.closest('.supplier-list').scrollTop($this.index() * $this.outerHeight());
            });
                                
            $('.supplier-list').on('keydown', 'li', function(e) {
                                
                $this = $(this);
                if (e.keyCode == 40) {   
                                        
                    $this.next().focus();
                                        
                        return false;
                    }else if (e.keyCode == 38) {        
                        $this.prev().focus();
                        return false;
                    }
                }); 
                                
                $('.supplier-list').on('keyup', function(e){
                    if(e.which == 13){
                                    
                        var val = $(this).find(".active").attr("data-name");
                        var id = $(this).find(".active").attr("data-id");
                                   
                        $('#supp_name').val(val);
                        $('#supp_id').val(id);
                                    
                        $("#supp_name").focus();
                        $("#supp_div").hide();
                                    
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
            		  url: "{{ URL::route('get_supplier') }}",
                      method: 'post',
                      data: formData,
                      contentType: false,
                      cache: false,
                      processData: false,
                      dataType: "json",
            		  error: function(ts) {
                          
                          //alert((ts.responseText));
                          $('#supp_div').show();
                          $('#supp_div').html(ts.responseText);
                      },
                      success: function(data){
                        
                          $('#supp_div').show();
                          $('#supp_div').html(ts.responseText);
                          //alert(data);
                          
                      }
            		   
            	}); 
                   
        });
        
        
        $("#supp_memo").keyup(function(e){
                    
            if(e.which == 40 || e.which == 38){
            
            $("#supp_memo").blur();
                        
            $('.suppmemo-list').find("li:first").focus().addClass('active').siblings().removeClass();
                        
            $('.suppmemo-list').on('focus', 'li', function() {
                $this = $(this);
                $this.addClass('active').siblings().removeClass();
                $this.closest('.suppmemo-list').scrollTop($this.index() * $this.outerHeight());
            });
                                
            $('.suppmemo-list').on('keydown', 'li', function(e) {
                                
                $this = $(this);
                if (e.keyCode == 40) {   
                                        
                    $this.next().focus();
                                        
                        return false;
                    }else if (e.keyCode == 38) {        
                        $this.prev().focus();
                        return false;
                    }
                }); 
                                
                $('.suppmemo-list').on('keyup', function(e){
                    if(e.which == 13){
                        
                                    
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
            		  url: "{{ URL::route('get_suppmemo') }}",
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
                        
                          $('#memo_div').show();
                          $('#memo_div').html(ts.responseText);
                          //alert(data);
                          
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

        $("#supp_memo").focusout(function() {
            $('#memo_div').hide();
        });
        
        $('#qnt').on('keyup', function(e){
            
            e.preventDefault();
            
            if(e.which == 13){
                
                var id = $('#pid_hid').val();
                var supp_id = $('#supp_id').val();
                var name = $('#search').val();
                var memo = $('#supp_memo').val();
                var qnt = Number($(this).val());
                var price = Number($('#price').val());

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
                                    "<input type='text' class='form-control' id='serial-"+i+"' required>" +
                                "</div>" +
                            "</div>"
                        );
                    }

                    $('#serial_modal').modal('toggle');

                    $('#serial_modal').on('shown.bs.modal', function () {
                            $('#serial-'+0).trigger('focus')
                    });
                }
                
                if(supp_id == 0){
                    alert("No suplier selected. Please select a supplier or create a new supplier!");
                    return false;
                }
                
                if(id == 0){
                    alert("Invalid product. Please select product or add product before proceed!!");
                    return false;
                }
                
                if(id == '' || name == '' || qnt == '' || price == ''){
                    
                    alert("Please Fillup All Fields ");
                    return false;
                }
                
                totalPrice = (qnt * price); 
                var total_vat= totalPrice*product_vat/100;
                
                add_product_to_table(id, name, qnt, price, totalPrice, total_vat);
                
                $('#search').focus();
            }
            
        });
        
        
        

        
        //////Place Order///////////
        
        $('#pur_save').click(function(e){
      
            var i = 0;
            
            var cartData = [];

            var warehouse_id = $('#warehouse_id').val();
            if(warehouse_id == null){
                alert('Please Select Warehouse');
                return ;
            }
    
            //$(this).attr('disabled', true);
            
            $('.price-table tr td').each(function(){
               
               var take_data = $(this).html();
              
               if($(this).attr('data-prodid') != ''){prodid = $(this).attr('data-prodid'); cartData.push(prodid);}
               else if($(this).attr('data-prodid') == ''){cartData.push("0");}
             
               if($(this).attr('class') == 'name'){name = $(this).html();  cartData.push(name);}
               
               if($(this).attr('class') == 'qnt'){qnt = $(this).html();  cartData.push(qnt);}
             
               if($(this).attr("class") == 'price'){price = $(this).html(); cartData.push(price);}
              
               if($(this).attr("class") == 'totalPriceTd'){totalPriceTd = $(this).html(); cartData.push(totalPriceTd);} 
               if($(this).attr("class") == 'totalVatTd'){totalVatTd = $(this).html(); cartData.push(totalVatTd);} 
               
               i = i +1;
           });
           
           cartData = cartData.filter(item => item);
           
            
           if(i<5){
               alert("Please Make A List.");
               return false;
           }
           
            var fieldValues = {};
            
            
            fieldValues.warehouse_id = $('#warehouse_id').val();
            fieldValues.supp_name = $('#supp_name').val();
            fieldValues.supp_id = $('#supp_id').val();
            fieldValues.supp_memo = $('#supp_memo').val();
            fieldValues.date = $('#date').val();
            fieldValues.amount = $('#amount').val();
            fieldValues.discount = $('#discount').val() ?? 0;
            fieldValues.payment = $('#payment').val() ?? 0;
            fieldValues.total = $('#total').val();
            fieldValues.total_vat = $('#total_vat').val();
            
            
           
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
                url: "{{ URL::route('save_purchase_products') }}",
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
                    
                    // alert(ts.responseText);
                    alert("Your Purchase Has Been Completed!!!");
                    
                    if(ts.responseText == '')  {
    
                        //alert(ts.responseText);
                        
                        $('#supp_name').val("");
                        $('#supp_id').val("0");
                        $('#amount').val("0");
                        $('#discount').val("0");
                        $('#total').val("0");
                        $('#payment').val("0");
                        $('#total_vat').val("0");
                       
                        $('#hid_total').val("0");
                        
                        $('.price-table td').remove();
                        
                        $('#pur_save').attr('disabled', false); 
                        
                        $("#wait").hide();
                    
                    }else{
                        //alert(ts.responseText);
                        
                        $('#supp_name').val("");
                        $('#supp_id').val("0");
                        $('#supp_memo').val("");
                        $('#amount').val("0");
                        $('#discount').val("0");
                        $('#total').val("0");
                        $('#payment').val("0");
                       
                        $('#hid_total').val("0");
                        $('#total_vat').val("0");
                        $('.price-table td').remove();
                        
                        $('#pur_save').attr('disabled', false);
                        
                        $("#wait").hide();
                    }
            
                },
                success: function(data){
                    
                    // alert(data);
                        
                        alert("Your Purchase Has Been Completed!!!");
                        location.reload()
                         //$('.cart-table tr').remove();
                    
                }
            }); 
          
              e.preventDefault(); 
       }); 
        
        
        
      
         $('body').on('click', '.delete', function(e){
            
            var totalPriceTd = Number($(this).closest('tr').find('.totalPriceTd').html());
        
            var productID = Number($(this).closest('tr').find(".name").attr('data-prodid'));
            var totalVatTd = Number($(this).closest('tr').find(".totalVatTd").html());

            delete serial_array[productID];
            
            var grandTotal = Number($('#total').val());
        
            var totalPrice = $('#hid_total').val();
            var totalVatField = $('#total_vat').val();
            
            
            totalPrice = Number(totalPrice - totalPriceTd);
            var totalVat = Number(totalVatField - totalVatTd);
            
            grandTotalPrice = Number(grandTotal - totalPriceTd);
            
            $('#hid_total').val(totalPrice);
            
            $('#total_vat').val(totalVat);
            
            $('#total').val(grandTotalPrice);

            $('#amount').val(grandTotalPrice);

            $(this).closest('tr').remove();
            
        }); 
        
        $('#discount').on('blur', function(e){
            
            var total = Number($('#hid_total').val());
            var total_vat = Number($('#total_vat').val());
    
            var discount = Number($(this).val());
            
            var total = Number(total - discount +total_vat);
            
            $('#total').val(total);
            
        });
        
        
        $('#payment').on('blur', function(e){
            
            var total = Number($('#hid_total').val());
            var total_vat = Number($('#total_vat').val());
            
            var discount = Number($('#discount').val());
    
            var payment = Number($(this).val());
            
            var total = Number((total +total_vat - payment) - discount);
            
            $('#total').val(total);
            
        });
        
        
        $('body').on('click', '#add_customer', function(e){
           $('.modalDiv2').show(500);
          // alert("sfasdsd");
        });
        
        $('body').on('click', '#add_waiter', function(e){
           $('.modalDiv').show(500);
           
        });
        
        $('.close').click(function(){
            
            $('.modalDiv2').hide(500);
            $('.modalDiv').hide(500);
        });
        
        $('body').on('click', '.btnCat', function(e){
            
            var id = $(this).attr('id');
            
            if(id == 'allcat'){
                $('.foodTab').show();
            }else{
            
                $('.foodTab').hide();
                
                $('.'+id).show();
            }
            
        });
        
        $('body').on('click', '#delete', function(e){
            
            if(confirm("Are you Sure to Delete?")){
                
            }else{
                e.preventDefault();
            }
            
        });
        
     
        $('body').on('click', function(){
            
            $('#supp_div').hide();
            $('#products_div').hide();
            $('#cust_div').hide();
            $('#waiter_div').hide();
        });
      
    });
    
    function selectProducts(id, name, price, serial, vat){

        $('#search').val(name);
        $('#pid_hid').val(id);
        $('#price').val(price);
        
        product_id = id;
        product_serial = serial;                                    
        product_vat = vat;                                    

        $("#price").focus();
        $("#products_div").hide();
        
    }
    
    function selectSupplier(name, id){
                                   
        $('#supp_name').val(name);
        $('#supp_id').val(id);
                                    
        $("#supp_name").focus();
        $("#supp_div").hide(); 
    }
    
    var sl = 1;
    
    function add_product_to_table(id, name, qnt, price, total, totalVat){
        
            var id = id;
            var name = name;
            var price = Number(price);
            var total = Number(total);
            var totalVat = Number(totalVat);
            totalVat = totalVat.toFixed(2);
            price = price.toFixed(2);
        
            
            $('.price-table').show();
            
            $('.price-table').append("<tr><td>"+sl+"</td><td data-prodid='"+id+"' style='width:200px;' class='name'>"+name+"</td><td class='price'>"+price+"</td><td class='qnt'>"+qnt+"</td><td class='totalVatTd'>"+totalVat+"</td><td class='totalPriceTd'>"+total+"</td><td><i class='delete mdi mdi-delete'></i></td></tr>");
            
            var totalPrice = Number($('#hid_total').val());
            var totalVatField = Number($('#total_vat').val());
            
            totalPrice = Number(totalPrice + total);
            totalPrice = totalPrice.toFixed(2);
            totalVatField = Number(totalVatField + totalVat);
            totalVatField = totalVatField.toFixed(2);
        
            $('#hid_total').val(totalPrice);
            $('#total_vat').val(totalVatField);
            $('#amount').val(totalPrice);
            
            var allTotal = Number(totalPrice)+Number(totalVatField);
            allTotal = allTotal.toFixed(2);

            $('#total').val(allTotal);
            
            $('#pid_hid').val("0");
            $('#search').val("");
            $('#price').val("");
            $('#qnt').val("");
            
            sl =sl + 1;
    }
    
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
    
        /* .box-body {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 3px;
            border-bottom-left-radius: 3px;
            padding: 10px;
            background-color: #FFF;
        } */
        
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

</style>
