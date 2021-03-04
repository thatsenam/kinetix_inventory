@extends('admin.pos.master')
@section('title', 'Stock Transfer')
@section('content')

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stock Transfer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main Page</a></li>
              <li class="breadcrumb-item active">Stock Transfer</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if ($success = Session::get('flash_message_success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $success }}</strong>
                    </div>
                    @endif
                    @if ($error = Session::get('flash_message_error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $error }}</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('admin.pos.stock.transfer') }}" id="" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                        <h3 class="card-title">Stock Transfer</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        </div>
                        <div class="card-body">
                        <div class="field_wrapper">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputFromWareHouse">From Warehouse</label>
                                        <select name="inputFromWareHouse" id="inputFromWareHouse" class="custom-select">
                                            <option selected="" disabled="" value="">Select Warehouse</option>
                                            @foreach($warehouses as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputToWareHouse">To Warehouse</label>
                                        <select name="inputToWareHouse" id="inputToWareHouse" class="custom-select">
                                            <option selected="" disabled="" value="">Select Warehouse</option>
                                            @foreach($warehouses as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputProduct">Product</label>
                                        <input type="text" class="form-control" placeholder="Search Product" id="search" autocomplete="off">
                                        <div id="products_div" style="display: none; position: absolute; top: 70px; left: 0; width: 100%; z-index: 999;"></div>
                                        <input type="hidden" name="pid_hid" id="pid_hid">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputQty">Quantity</label>
                                        <input type="text" name="inputQty2" id="inputQty2" class="form-control" placeholder="Quantity" required>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="row">
                            <div id="prodlistDiv" class="col-12" style="height:auto; overflow-y: auto; ">
                                <div style="padding-right: 0 !important;">
                                    <table id="prodlist" class="price-table custom-table">
                                        <tr><th>#</th><th style="width: 100px;">Product<th>From</th><th>Qty.</th><th>To</th><th>Delete</th></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputDate">Date</label>
                                    <input type="text" value="{{ date('Y-m-d') }}" id="inputDate" name="inputDate" class="form-control" placeholder="Transfer Date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputRemarks">Remarks</label>
                                    <input type="text" id="inputRemarks" name="inputRemarks" class="form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                <a href="" class="btn btn-secondary">Cancel</a>
                <input value="Submit" class="btn btn-success float-right save">
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>

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
</style>

@endsection
@section('page-js-script')
<script>
    //Initialize Select2 Elements
    $(function () {
        $('.select2').select2()
    });
    $(document).ready(function(){
        $("#inputDate").datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('body').on('click', function(){
            $('#products_div').hide();
        });
        //Search Product
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
                $('.products-list').on('keyup', function(e) {
                    if (e.which == 13) {

                        var id = $(this).find(".active").attr("data-id");
                        var name = $(this).find(".active").attr("data-name");
                        var price = $(this).find(".active").attr("data-price");

                        product_id = id;
                        product_serial = $(this).find(".active").attr("data-serial");
                        warranty = $(this).find(".active").attr("data-warranty");
                        product_stock = $(this).find(".active").attr("data-stock");

                        $('#product_stock').html(product_stock);
                        $('#search').val(name);
                        $('#pid_hid').val(id);
                        
                        $("#products_div").hide();

                        //window.location.replace("{{ Request::root() }}/admin/editcat/"+val);

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

        // $('#quantity2').on("keyup", function (e) {
        //     if (e.which == 13) {
        //         var qty_box = Number(btoe( $('#quantity2').val()));
        //         var total_kg = per_box_qty*qty_box;
        //         box=qty_box;
        //         fraction=0;
        //         $('#inputQty2').val(etob(total_kg));
        //         $('#quantity2').val('');
    
        //         $('#square_foot_modal2').modal('hide');
        //         $('#inputQty').focus();
        //     }
        // });

        ///After Press Enter on Qty
        $('#inputQty2').on('keyup', function(e){
            
            e.preventDefault();
            if(e.which == 13){
                
                var id = $('#pid_hid').val();
                var name = $('#search').val();
                var qnt = Number( $('#inputQty2').val());
                var fromWareHouse = $('#inputFromWareHouse').val();
                var fromWareHouseT = $("#inputFromWareHouse option:selected" ).text();
                var toWareHouse = $('#inputToWareHouse').val();
                var toWareHouseT = $("#inputToWareHouse option:selected" ).text();

                if(fromWareHouse == null || toWareHouse == null){
                    alert('Warehouse To করুন');
                    return ;
                }

                if(fromWareHouse == toWareHouse){
                    alert('একই WarehouseেTo Product না!');
                    return ;
                }

                if(id == 0){
                    alert("কোন Search Product হয় নি!!");
                    return false;
                }

                if(id == '' || name == '' || qnt == ''){
                    alert("সকল ফিল্ড গুলো পূর্ণ করুন");
                    return false;
                }
                
                add_product_to_table(id, name, qnt, fromWareHouse, fromWareHouseT, toWareHouse, toWareHouseT);
                $('#search').focus();
            }
        
        });

        var sl = 1;
        function add_product_to_table(id, name, qnt, fromWareHouse, fromWareHouseT, toWareHouse, toWareHouseT){
                
            var id = id;
            var name = name;
            
            $('.price-table').show();
            
            $('.price-table').append("<tr><td>"+sl+"</td><td data-prodid='"+id+"' style='width:150px;' class='name'>"+name+"</td><td data-fromid='"+fromWareHouse+"' class='from'>"+fromWareHouseT+"</td><td class='qnt'>"+qnt+"</td><td data-toid='"+toWareHouse+"' class='to'>"+toWareHouseT+"</td><td><i class='delete mdi mdi-delete'></i></td></tr>");

            $('#pid_hid').val("0");
            $('#search').val("");
            $('#inputQty2').val("");
            $('#square_foot_input').val("");
            $('#inputFromWareHouse').val("");
            $('#inputToWareHouse').val("");
            
            sl =sl + 1;
        }

        $('body').on('click', '.delete', function(e){
            $(this).closest('tr').remove();
        });

        //Save Data
        $('input[name="inputQty2"]').focus(function() {
            $(".save").removeAttr('disabled');
        });

        $('.save').click(function(e){
          
           var i = 0;
           var cartData = [];
   
           $(this).attr('disabled', true);
           $('.price-table tr td').each(function(){
              
              var take_data = $(this).html();
             
              if($(this).attr('data-prodid') != ''){prodid = $(this).attr('data-prodid'); cartData.push(prodid);}

              if($(this).attr('data-fromid') != ''){fromid = $(this).attr('data-fromid'); cartData.push(fromid);}

              if($(this).attr('data-toid') != ''){toid = $(this).attr('data-toid'); cartData.push(toid);}
            
              if($(this).attr("class") == 'qnt'){quantity =$(this).html(); cartData.push(quantity);}

              i = i +1;
          });
          
          cartData = cartData.filter(item => item);
        
          if(i<5){
              alert("Please make a product list.");
              $(this).attr('disabled', true);
              return false;
          }
          
           var fieldValues = {};
           
           fieldValues.date = $('#inputDate').val();
           fieldValues.remarks = $('#inputRemarks').val();
           
           var formData = new FormData();
          
           formData.append('fieldValues', JSON.stringify(fieldValues)); 
           formData.append('cartData', JSON.stringify(cartData));
                  
           $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
           });
         
            $.ajax({
               url: "{{ URL::route('admin.pos.stock.transfer') }}",
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
                    $('#inputFromWareHouse').val("");
                    $('#inputToWareHouse').val("");
                    $('#inputQty2').val("");
                    $('#inputDate').val("");
                    $('.price-table td').remove();
                    $('.save').attr('disabled', false);
                    $("#wait").hide();
                    // location.reload();stock
                },
                success: function(data){
                    $('#inputFromWareHouse').val("");
                    $('#inputToWareHouse').val("");
                    $('#inputQty2').val("");
                    $('#inputDate').val("");
                    $('#inputRemarks').val("");
                    $('.price-table td').remove();
                    $('.save').attr('disabled', false);
                    $("#wait").hide();
                }
            });
            e.preventDefault();
        });
        
    });
    function selectProducts(id,name){
        $('#pid_hid').val(id);
        $('#search').val(name);
        $("#inputQty2").focus();
        $("#products_div").hide();
    }
</script>
@stop