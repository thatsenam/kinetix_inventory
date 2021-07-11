@extends('admin.pos.master')

@section('title','Sales Return')

@section('content')


    <div class="main-panel">
        <div class="content-wrapper">
            <!-- Page Title Header Starts-->

            <section class="content">
                <h2 class="ml-3">Sales Return</h2>

                <img id="wait" src="{{asset('images/wait2.gif')}}"
                     style="width:300px; margin: 0 auto; position:fixed; top:200px; left:40%; z-index:999; display:none">


                <div class="box-body">
                    <div class="row">
                        <div class="col-12" style="position: relative;">
                            <form action="{{route('sales_return_save')}}" method="POST">
                                @csrf
                                <div class="card" style="min-height: 500px;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="row">
                                                    @if($warehouses->count()>1)
                                                        <div class="col-4">
                                                            <select name="warehouse_id" id="warehouse_id"
                                                                    class="form-control">
                                                                <option value="" disabled selected>Select Warehouse
                                                                </option>
                                                                @foreach($warehouses as $warehouse)
                                                                    <option
                                                                        value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_phone" id="cust_phone"
                                                                       class="form-control" placeholder="Customer Phone"
                                                                       autocomplete="off">
                                                                <div id="cust_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>

                                                                <input type="hidden" name="cust_id" id="cust_id"
                                                                       value="0" class="form-control">

                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_name" id="cust_name"
                                                                       class="form-control" placeholder="Customer Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-2">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="invoice" id="invoice"
                                                                       class="form-control" placeholder="Invoice">
                                                                <div id="memo_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <input type="hidden" name="warehouse_id" id="warehouse_id"
                                                               value="{{ $warehouse_id }}">
                                                        <div class="col-4">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_phone" id="cust_phone"
                                                                       class="form-control"
                                                                       placeholder="Customer Phone">
                                                                <div id="cust_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>

                                                                <input type="hidden" name="cust_id" id="cust_id"
                                                                       value="0" class="form-control">

                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group" style="position: relative;">
                                                                <input type="text" name="cust_name" id="cust_name"
                                                                       class="form-control" placeholder="Customer Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group" style="position: relative;">
                                                                <select name="invoice" id="invoice"
                                                                        class="form-control searchable">
                                                                    <option></option>
                                                                    @foreach($invoices as $invoice)
                                                                        <option>{{ $invoice }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <div id="memo_div"
                                                                     style="width: 100%; display: none; position: absolute; top: 30px; left: 0; z-index: 999;"></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div>
                                                        <div class="form-group" style="position: relative;display: none">
                                                            <input type="text" name="barcode" id="barcode"
                                                                   class="form-control" placeholder="Barcode">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group" style="position: relative;">
                                                            <input type="text" class="form-control"
                                                                   placeholder="Search Product" id="search">
                                                            <div id="products_div"
                                                                 style="display: none; position: absolute; top: 30px; left: 0; width: 100%; z-index: 999;"></div>
                                                            <input type="hidden" name="pid_hid" id="pid_hid">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="height:350px; overflow-y: auto; ">
                                                    <div class="col-12" style="padding-right: 0 !important;">
                                                        <table class="price-table custom-table" style="">
                                                            <tr>
                                                                <th>Item</th>
                                                                <th>Sub-unit</th>
                                                                <th>Unit</th>
                                                                <th>Price</th>
                                                                <th>IVA</th>
                                                                <th>Total</th>
                                                                <th>Delete</th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-5">

                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="price" id="price"
                                                                   class="form-control" placeholder="Price">

                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="qnt" id="qnt" class="form-control"
                                                                   placeholder="Quantity">

                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" name="date" id="date"
                                                                   class="form-control" placeholder="date"
                                                                   value="<?php echo date("Y-m-d");?>"
                                                                   style="padding: 0.94rem 0.5rem;">

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Total</label>
                                                            <input type="text" name="total" id="total"
                                                                   class="form-control" placeholder="" value="0">
                                                            <input type="hidden" name="hid_total" id="hid_total"
                                                                   class="form-control" placeholder="" value="0">

                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Total Vat</label>
                                                            <input type="text" name="total_vat" id="total_vat"
                                                                   class="form-control" readonly="true" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Grand Total</label>
                                                            <input type="text" name="gtotal" id="gtotal"
                                                                   class="form-control" readonly="true" value="0">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Payment</label>
                                                            <input type="text" name="payment" id="payment"
                                                                   class="form-control" placeholder="" value="0">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-12">

                                                        <label>Remarks</label>
                                                        <textarea class="form-control" id="remarks" name="remarks"
                                                                  rows='5'></textarea>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-6">
                                                        <div style="width: 120px; margin: 50px auto;">
                                                            <input type="button" class="btn btn-danger btn-lg"
                                                                   id="cancel" value="Cancel">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div style="width: 120px; margin: 50px auto;">
                                                            <input type="button" class="btn btn-success btn-lg"
                                                                   id="save" value="Save">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <!-- Modal -->
                            <div class="modal fade" id="serial_modal" tabindex="-1" role="dialog"
                                 aria-labelledby="serial_modalLabel" aria-hidden="true">
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
                                                    <button type="button" class="btn btn-outline-danger"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                    <button type="button" id="serial_save" class="btn btn-primary">
                                                        SAVE
                                                    </button>
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
        </section>
        <!-- content-wrapper ends -->
        <!-- ///////////// -->
        <div class="modal fade" id="square_foot_modal" tabindex="-1" role="dialog"
             aria-labelledby="square_foot_modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title float-center" id="square_foot_modalLabel">Quantity  -  <span id="qty_type"></span> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <label for="quantity" class="col-sm-4 col-form-label">Quantity</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="quantity">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
                        {{-- <button type="button" class="btn btn-primary">OK</button> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-panel ends -->

@endsection

@section('page-js-script')

    <script type="text/javascript">

        var product_id;
        var product_serial;
        var serial_qty;
        var serial_array = {};
        var per_box_qty;
        var sub_unit;
        var unit;
        var box = 0;
        var fraction = 0;

        var allowedProducts = [];

        $(document).ready(function () {

            $('#date').datepicker({dateFormat: 'yy-mm-dd'});


            $("#cust_phone").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#cust_phone").blur();

                    $('.customer-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.customer-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.customer-list').scrollTop($this.index() * $this.outerHeight());
                    });

                    $('.customer-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.customer-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var phone = $(this).find(".active").attr("data-phone");
                            var name = $(this).find(".active").attr("data-name");
                            var id = $(this).find(".active").attr("data-id");

                            $('#cust_phone').val(phone);
                            $('#cust_name').val(name);
                            $('#cust_id').val(id);

                            // $('#invoice').click()
                            $("#invoice").select2("open");

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
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {

                        $('#cust_div').show();
                        $('#cust_div').html(ts.responseText);
                        //alert((ts.responseText));
                    },
                    success: function (data) {

                        $('#cust_div').show();
                        $('#cust_div').html(ts.responseText);
                        //alert(data);

                    }

                });

            });

            $('#invoice').on('change', () => $('#search').focus())

            $("#barcode").keypress(function (e) {

                if (e.which == 13) {

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
                        beforeSend: function () {
                            //$("#wait").show();
                        },
                        error: function (ts) {

                            //alert(ts.responseText);

                            /* $('#search').val(name);
                            $('#pid_hid').val(id);
                            $('#price').val(price);

                            $("#price").focus(); */
                        },
                        success: function (data) {

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


            $("#search").keyup(function (e) {

                if (e.which == 40 || e.which == 38) {

                    $("#search").blur();

                    $('.products-list').find("li:first").focus().addClass('active').siblings().removeClass();

                    $('.products-list').on('focus', 'li', function () {
                        $this = $(this);
                        $this.addClass('active').siblings().removeClass();
                        $this.closest('.products-list').scrollTop($this.index() * $this.outerHeight());
                    });

                    $('.products-list').on('keydown', 'li', function (e) {

                        $this = $(this);
                        if (e.keyCode == 40) {

                            $this.next().focus();

                            return false;
                        } else if (e.keyCode == 38) {
                            $this.prev().focus();
                            return false;
                        }
                    });

                    $('.products-list').on('keyup', function (e) {
                        if (e.which == 13) {

                            var id = $(this).find(".active").attr("data-id");
                            var name = $(this).find(".active").attr("data-name");
                            var price = $(this).find(".active").attr("data-price");
                            product_id = id;
                            product_serial = $(this).find(".active").attr("data-serial");
                            product_vat = $(this).find(".active").attr("data-vat");
                            var pbq = $(this).find(".active").attr("data-pbq");
                            sub_unit = $(this).find(".active").attr("data-sub_unit");
                            unit = $(this).find(".active").attr("data-unit");

                            if (!sub_unit) {
                                $('#qty_type').text(unit)

                            } else {
                                $('#qty_type').text(sub_unit)
                            }
                            if (sub_unit) {
                                $('#search').val(name);

                                $('#square_foot_modal').modal('toggle');

                                per_box_qty = pbq;
                                box = 0;
                                $('#square_foot_modal').on('shown.bs.modal', function () {
                                    $('#quantity').trigger('focus')
                                });
                            } else {
                                $('#search').val(name);
                                per_box_qty = 0;
                                box = 0;
                                $('#qnt').val('');
                            }

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
                formData.append('products', JSON.stringify(allowedProducts || '[]'));

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_return_products') }}",
                    method: 'post',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    beforeSend: function () {
                        //$("#wait").show();
                    },
                    error: function (ts) {

                        $('#products_div').show();
                        $('#products_div').html(ts.responseText);
                        //alert((ts.responseText));
                    },
                    success: function (data) {

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

                for (i = 0; i < serial_qty; i++) {
                    var ser = $('#serial-' + i).val();
                    if (ser == '') {
                        $('#serial-' + i).addClass("is-invalid");
                        access = 1;
                    } else {
                        $('#serial-' + i).removeClass("is-invalid");
                    }
                }
                if (access == 1) {
                    return;
                }
                for (i = 0; i < serial_qty; i++) {
                    var ser = $('#serial-' + i).val();

                    val[i] = ser;
                }
                serial_array[product_id] = val;

                $('#serial_modal').modal('hide');

                console.log(JSON.stringify(serial_array));
            });

            $('#quantity').on("keypress", function (e) {
                if (e.which == 13) {
                    var qty_box = Number($('#quantity').val());
                    var total_kg = per_box_qty * qty_box;
                    box = qty_box;
                    fraction = 0;
                    $('#qnt').val(total_kg);
                    // $('#quantity').val('');

                    $('#square_foot_modal').modal('hide');
                    $('#price').focus();
                }
            });


            $('#qnt').on('keyup', function (e) {

                e.preventDefault();

                if (e.which == 13) {

                    var id = $('#pid_hid').val();
                    var cust_id = $('#cust_id').val();
                    var name = $('#search').val();
                    var qnt = Number($(this).val());
                    var price = Number($('#price').val());
                    var totalPrice = Number($('#hid_total').val());
                    var pp = Number($('#quantity').val());

                    serial_qty = qnt;

                    var warehouse_id = $('#warehouse_id').val();
                    if (warehouse_id == null) {
                        alert('Please Select Warehouse');
                        return;
                    }

                    if (product_serial == 1) {
                        $("#serial_input").empty();
                        for (i = 0; i < qnt; i++) {
                            $('#serial_input').append(
                                "<div class='form-group row'>" +
                                "<label for='serial-" + i + "' class='col-3 col-form-label'>Serial " + (i + 1) + "</label>" +
                                "<div class='col-9'>" +
                                "<input list='serial_suggest' type='text' class='form-control' id='serial-" + i + "' required>" +
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
                            beforeSend: function () {
                            },
                            error: function (ts) {
                            },
                            success: function (response) {
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
                                    $('#serial-' + 0).trigger('focus')
                                });
                            }
                        });
                    }

                    if (cust_id == 0) {
                        alert("No customer selected. Please select a customer & try again!");
                        return false;
                    }

                    if (name == '' || qnt == '' || price == '') {

                        alert("Please Fillup All Fields ");
                        return false;
                    }

                    if (sub_unit) {
                        if (pp == 0) {
                            price_per_kg = price;
                            totalPrice = (qnt * price);
                            var box_qty = parseInt(qnt / per_box_qty);
                            var fraction = qnt - (box_qty * per_box_qty);
                            if (fraction != 0) {
                                var box_value = box_qty + " " + sub_unit + " " + fraction + unit;
                            } else {
                                var box_value = box_qty + " " + sub_unit;
                            }
                        } else {
                            var box_qty = parseInt(qnt / per_box_qty);
                            var fraction = qnt - (box_qty * per_box_qty);
                            price_per_kg = price / per_box_qty;
                            if (fraction != 0) {
                                var price_per_kg = price / per_box_qty;
                                totalPrice = (box_qty * price) + (price_per_kg * fraction);
                                var box_value = box_qty + " " + sub_unit + " " + fraction + unit;
                            } else {
                                totalPrice = (box_qty * price);
                                var box_value = box_qty + " " + sub_unit;
                            }
                        }

                    } else {
                        totalPrice = (qnt * price);
                        var box_value = qnt + " " + unit;
                        price_per_kg = price / per_box_qty;
                    }

                    add_product_to_table(id, name, qnt, price, totalPrice, 0, 0, price_per_kg, box_value);

                }

            });


            $('#cancel').click(function () {

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
            $('input[name="qnt"]').focus(function () {
                $("#save").removeAttr('disabled');
            });

            $('#save').click(function (e) {


                var i = 0;

                var cartData = [];

                var warehouse_id = $('#warehouse_id').val();
                if (warehouse_id == null) {
                    alert('Please Select Warehouse');
                    return;
                }

                $(this).attr('disabled', true);

                $('.price-table tr td').each(function () {

                    var take_data = $(this).html();

                    if ($(this).attr('data-prodid') != '') {
                        prodid = $(this).attr('data-prodid');
                        cartData.push(prodid);
                    }

                    if ($(this).attr("class") == 'qnty') {
                        quantity = $(this).html();
                        cartData.push(quantity);
                    }

                    if ($(this).attr("class") == 'uprice') {
                        uprice = $(this).html();
                        cartData.push(uprice);
                    }

                    if ($(this).attr("class") == 'totalPriceTd') {
                        totalPriceTd = $(this).html();
                        cartData.push(totalPriceTd);
                    }

                    if ($(this).attr("class") == 'prod_vat') {
                        prod_vat = $(this).html();
                        cartData.push(prod_vat);
                    }

                    if ($(this).attr("class") == 'box') {
                        var box_item = $(this).html();
                        cartData.push(box_item);
                    }

                    i = i + 1;
                });

                cartData = cartData.filter(item => item);

                if (i < 5) {
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
                fieldValues.total_vat = $('#total_vat').val();
                fieldValues.date = $('#date').val();

                if (cust_id <= 0) {
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
                    beforeSend: function () {
                        // $("#wait").show();
                    },
                    error: function (ts) {

                        // alert(ts.responseText);

                        $('.price-table td').remove();

                        $("#wait").hide();

                        $('#save').attr('disabled', false);

                        alert('Sales Return Successfull!');

                        location.reload();

                    },
                    success: function (data) {

                        alert('Sales Return Successfull!');
                        location.reload();
                    }
                });

                e.preventDefault();
            });

            $('body').on('click', '.delete', function (e) {

                var totalPriceTd = Number($(this).closest('tr').find('.totalPriceTd').html());
                var productID = Number($(this).closest('tr').find("td").attr('data-prodid'));
                var prod_vat = Number($(this).closest('tr').find('.prod_vat').html());

                delete serial_array[productID];

                var totalPrice = $('#hid_total').val();
                var total_vat = Number($('#total_vat').val());
                var gtotal = Number($('#gtotal').val());

                totalPrice = Number(totalPrice - totalPriceTd);
                $('#total_vat').val(Number(total_vat - prod_vat));
                $('#gtotal').val(Number(gtotal - totalPriceTd - prod_vat));

                $('#hid_total').val(totalPrice);

                $('#total').val(totalPrice);

                $(this).closest('tr').remove();

            });

            $('body').on('click', function () {

                $('#products_div').hide();
                $('#cust_div').hide();

            });
        });

        function selectCustomer(id, phone, name) {

            $('#cust_phone').val(phone);
            $('#cust_name').val(name);
            $('#cust_id').val(id);

            $("#invoice").select2("open");
            $("#cust_div").hide();
        }

        function selectProducts(id, name, price, serial, warranty, stock, vat, pbq, su, u) {
            sub_unit = su;
            unit = u;
            if (!sub_unit) {
                $('#qty_type').text(unit)

            } else {
                $('#qty_type').text(sub_unit)
            }
            if (sub_unit) {
                $('#search').val(name);
                $('#square_foot_modal').modal('toggle');

                per_box_qty = pbq;
                box = 0;

                $('#square_foot_modal').on('shown.bs.modal', function () {
                    $('#quantity').trigger('focus')
                });
            } else {
                $('#search').val(name);
                per_box_qty = 0;
                box = 0;
                $('#qnt').val('');
            }

            $('#pid_hid').val(id);
            $('#price').val(price);
            product_id = id;
            product_serial = serial;
            product_vat = vat;

            $("#price").focus();
            $("#products_div").hide();

        }

        $('#invoice').on('change', function () {
            fetch('/dashboard/get_sales_info/' + $('#invoice').val())
                .then(async (response) => {
                    let customer = await response.json();
                    $('#cust_phone').val(customer.phone)
                    $('#cust_name').val(customer.name)
                    $('#cust_id').val(customer.id)
                    console.log(customer)
                });

            setTimeout(() => {
                console.log($('#search').focus())
            }, 100)
            fetchInvoice($(this).val());
        })


        function fetchInvoice(invoice) {
            if (invoice == '') {
                return;
            }
            fetch("{{ url('/dashboard/get_invoice') }}" + "/" + invoice).then(response => response.json())
                .then(products => {
                    allowedProducts = products;
                    console.log(products)
                });
        }

        function add_product_to_table(id, name, qnt, price, totalPrice, pvat, vat, price_per_kg, box) {

            var id = id;
            var name = name;
            var qnt = Number(qnt);
            var price = Number(price);
            var pvat = Number(pvat);
            var vat = Number(vat);
            var totalPrice = Number(totalPrice);

            calculate_vat = ((price * product_vat) / 100) * qnt;
            calculate_vat = Number(calculate_vat.toFixed(2));

            vat = (vat + calculate_vat);

            var total = (price * qnt);

            $('.price-table').show();

            $('.price-table').append("<tr data-vat='" + calculate_vat + "'><td data-prodid='" + id + "' style='width:200px;'>" + name + "</td><td class='box'>" + box + "</td><td class='qnty'>" + qnt + "</td><td class='uprice'>" + price + "</td><td class='prod_vat'>" + calculate_vat + "</td><td class='totalPriceTd'>" + total + "</td><td><i class='delete mdi mdi-delete'></i></td></tr>");

            var totalPriceVal = Number($('#hid_total').val());
            var totalVatFieldVal = Number($('#total_vat').val());

            totalPrice = Number(totalPriceVal + Number(total));

            vatField = Number(totalVatFieldVal + calculate_vat);
            $('#total_vat').val(vatField)

            $('#hid_total').val(totalPrice);
            var showGTotal = totalPrice + vatField;
            showGTotal = Number(showGTotal.toFixed(2));
            $('#gtotal').val(showGTotal);

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

    .sugg-list li {
        width: 100%;
        border-bottom: #FFF;
        color: #6a6a6a;
        list-style-type: none;
        padding: 5px;
    }

    .sugg-list li:hover {
        width: 100%;
        background-color: #006fd2;
        color: #FFF;
        padding: 0;
    }

    .custom-table {

        width: 100%;
        border-collapse: collapse;
    }

    .custom-table tr th {
        background-color: #1bcfb4;
        color: #FFF;
        text-align: center;
        padding: 5px;
    }

    .custom-table tr td {
        padding: 5px;
        border: 1px solid #e6e6e6;
        text-align: center;
        font-size: 14px;
    }

    .custom-text {

        width: 150px;
        border: 1px solid #e6e6e6;
        border-radius: 2px;
        outline: none;
        padding: 5px;
        box-sizing: border-box;
        text-align: center;
    }

    .custom-text:focus {
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

    .active {
        color: #FF0;
        background-color: #FFF;
    }

    #tblTotal tr td {
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

    input:focus {
        outline: none !important;
        border: 1px solid #fce306 !important;
        box-shadow: 0 0 1px #f102b6;
        background: yellow !important;
        background-color: #bcf841 !important;
        color: white;
    }


</style>
