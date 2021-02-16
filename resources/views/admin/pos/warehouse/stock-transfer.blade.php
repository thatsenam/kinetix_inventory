@extends('admin.pos.master')
@section('title', 'স্টক স্থানান্তর')
@section('content')

<div class="content-wrapper" style="min-height: 1662.75px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>স্টক স্থানান্তর</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">মূলপাতা</a></li>
              <li class="breadcrumb-item active">স্টক স্থানান্তর</li>
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
                        <h3 class="card-title">স্টক স্থানান্তর</h3>
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
                                        <label for="inputFromWareHouse">গুদাম থেকে</label>
                                        <select name="inputFromWareHouse" id="inputFromWareHouse" class="custom-select">
                                            <option selected="" disabled="" value="">গুদাম নির্বাচন করুন</option>
                                            @foreach($warehouses as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputProduct">পণ্য</label>
                                        <input type="text" class="form-control" placeholder="পণ্য খোঁজ করুন" id="search" autocomplete="off">
                                        <div id="products_div" style="display: none; position: absolute; top: 70px; left: 0; width: 100%; z-index: 999;"></div>
                                        <input type="hidden" name="pid_hid" id="pid_hid">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputQty">পরিমাণ</label>
                                        <input type="text" name="inputQty2" id="inputQty2" class="form-control" placeholder="পরিমাণ লিখুন..." required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputToWareHouse">গুদাম প্রতি</label>
                                        <select name="inputToWareHouse" id="inputToWareHouse" class="custom-select">
                                            <option selected="" disabled="" value="">গুদাম নির্বাচন করুন</option>
                                            @foreach($warehouses as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div id="prodlistDiv" class="col-12" style="height:auto; overflow-y: auto; ">
                                <div style="padding-right: 0 !important;">
                                    <table id="prodlist" class="price-table custom-table">
                                        <tr><th>#</th><th style="width: 100px;">পণ্য</th><th>থেকে</th><th>সাব একক</th><th>কেজি/পিস</th><th>প্রতি</th><th>ডিলিট</th></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputDate">তারিখ</label>
                                    <input type="text" value="{{ \App\Helpers\AppHelper::instance()->btoe(date('Y-m-d')) }}" id="inputDate" name="inputDate" class="form-control" placeholder="স্থানান্তরের তারিখ" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputRemarks">মন্তব্য</label>
                                    <input type="text" id="inputRemarks" name="inputRemarks" class="form-control" placeholder="মন্তব্য.....">
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
                <a href="" class="btn btn-secondary">বাতিল</a>
                <input value="সম্পন্ন করুন" class="btn btn-success float-right save">
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>

<div class="modal fade" id="square_foot_modal2" tabindex="-1" role="dialog" aria-labelledby="square_foot_modalLabel" aria-hidden="true">
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
                <label for="quantity2" class="col-sm-4 col-form-label">পরিমান</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="quantity2">
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
<script src="/js/conversion.js"></script>
<script>
    //Initialize Select2 Elements
    $(function () {
        $('.select2').select2()
    });
    $(document).ready(function(){
        $("#inputDate").datepicker({
            dateFormat: 'yy-mm-dd'
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
                $('.products-list').on('keyup', function(e){
                    if(e.which == 13){    
                        var id = $(this).find(".active").attr("data-id");    
                        var name = $(this).find(".active").attr("data-name");
                        var pbq = $(this).find(".active").attr("data-pbq");
                        unit = $(this).find(".active").attr("data-unit");
                        sub_unit = $(this).find(".active").attr("data-sub_unit");  
                        rem_qnt = $(this).find(".active").attr("data-rem_qnt");
                        if(pbq){
                            $('#search').val(name);
                            $('#square_foot_modal2').modal('toggle');
                            per_box_qty = pbq;
                            box=0;
                            $('#square_foot_modal2').on('shown.bs.modal', function () {
                                $('#quantity2').trigger('focus')
                            });
                        }else{
                            $('#search').val(name);
                            per_box_qty=0;
                            box=0;
                            $('#inputQty2').val('');
                        }
                                
                        $('#search').val(name);
                        $('#pid_hid').val(id);
                                            
                        $("#inputQty2").focus();
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

        $('#quantity2').on("keyup", function (e) {
            if (e.which == 13) {
                var qty_box = Number(btoe( $('#quantity2').val()));
                var total_kg = per_box_qty*qty_box;
                box=qty_box;
                fraction=0;
                $('#inputQty2').val(etob(total_kg));
                $('#quantity2').val('');
    
                $('#square_foot_modal2').modal('hide');
                $('#inputQty').focus();
            }
        });

        ///After Press Enter on Qty
        $('#inputQty2').on('keyup', function(e){
            
            e.preventDefault();
            if(e.which == 13){
                
                var id = $('#pid_hid').val();
                var name = $('#search').val();
                var qnt = Number(btoe($('#inputQty2').val()));
                var fromWareHouse = $('#inputFromWareHouse').val();
                var fromWareHouseT = $("#inputFromWareHouse option:selected" ).text();
                var toWareHouse = $('#inputToWareHouse').val();
                var toWareHouseT = $("#inputToWareHouse option:selected" ).text();

                if(fromWareHouse == null || toWareHouse == null){
                    alert('গুদাম সিলেক্ট করুন');
                    return ;
                }

                if(fromWareHouse == toWareHouse){
                    alert('একই গুদামে পণ্য স্থানান্তর সম্ভব না!');
                    return ;
                }

                if(id == 0){
                    alert("কোন পণ্য সিলেক্ট করা হয় নি!!");
                    return false;
                }

                if(id == '' || name == '' || qnt == ''){
                    alert("সকল ফিল্ড গুলো পূর্ণ করুন");
                    return false;
                }

                if(sub_unit){
                    var box_qty=parseInt(qnt/per_box_qty);
                    var fraction=qnt-( box_qty *per_box_qty);
                    if(fraction!=0){ 
                        var box_value= etob(box_qty)+" "+sub_unit+" "+etob(fraction)+unit;
                    }else{
                        var box_value= etob(box_qty)+" "+sub_unit;
                    }
                        
                }else{
                    var box_value= etob(qnt)+" "+unit;
                }
                
                add_product_to_table(id, name, qnt, box_value, fromWareHouse, fromWareHouseT, toWareHouse, toWareHouseT);
                $('#search').focus();
            }
        
        });

        var sl = 1;
        function add_product_to_table(id, name, qnt, box, fromWareHouse, fromWareHouseT, toWareHouse, toWareHouseT){
                
            var id = id;
            var name = name;
            
            $('.price-table').show();
            
            $('.price-table').append("<tr><td>"+etob(sl)+"</td><td data-prodid='"+id+"' style='width:150px;' class='name'>"+name+"</td><td data-fromid='"+fromWareHouse+"' class='from'>"+fromWareHouseT+"</td><td class='box'>"+box+"</td><td class='qnt'>"+etob(qnt)+"</td><td data-toid='"+toWareHouse+"' class='to'>"+toWareHouseT+"</td><td><i class='delete mdi mdi-delete'></i></td></tr>");

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

        function selectProducts(id, name, pbq, su,u,qnt){

            sub_unit=su;
            unit=u;
            rem_qnt=qnt;
            if(pbq){
                $('#search').val(name);
                $('#square_foot_modal2').modal('toggle');

                per_box_qty = pbq;
                box=0;

                $('#square_foot_modal2').on('shown.bs.modal', function () {
                    $('#quantity2').trigger('focus')
                });
            }else{
                $('#search').val(name);
                per_box_qty=0;
                box=0;
                $('#inputQty2').val('');
            }

            $('#pid_hid').val(id);
            $("#inputQty2").focus();
            $("#products_div").hide();
        }

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
            
              if($(this).attr("class") == 'box'){var box_item = $(this).html(); cartData.push(box_item);}

              i = i +1;
          });
          
          cartData = cartData.filter(item => item);
        
          if(i<5){
              alert("Please make a product list.");
              $(this).attr('disabled', true);
              return false;
          }
          
           var fieldValues = {};
           
           fieldValues.inputRemarks = $('#inputRemarks').val();
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
                    location.reload();
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
</script>
@stop