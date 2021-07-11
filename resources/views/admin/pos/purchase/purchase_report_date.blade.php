@extends('admin.pos.master')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Purchase Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="/dashboard/pos">POS</a></li>
                            <li class="breadcrumb-item active">Purchase Reports</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                        <div class="justify-content-center d-flex mb-2">
                            <div>
                                <label class="text-dark"><b>From Date</b></label>
                                <input class="form-control" name="from_date" id="from_date" type="text" required
                                       autocomplete="off">
                            </div>
                            <p class="mr-4"></p>
                            <div>
                                <label class="text-dark"><b>To Date</b></label>
                                <input class="form-control" name="to_date" id="to_date" type="text" required
                                       autocomplete="off">
                            </div>
                            <div class="mt-auto ml-2">
                                <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh
                                </button>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table id="reports" class="table table-sm table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Memo</th>
                                        <th>Serial</th>
                                        <th>Supplier</th>
                                        <th>Supp Invocie</th>
                                        <th>Discount</th>
                                        <th>Vat</th>
                                        <th>Amount</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Due</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th colspan="7">Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2"></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="row pos_div"
             style="position: absolute; top: 10px;width: 60%; margin: 0 auto; background: #FFF; height:600px; overflow-y:auto; padding: 10px; z-index: 999999; display: none;">
            <div class="col-12" style="position:relative">
                <button class="btn btn-primary close" style="position:absolute; top: 10px; right: 10px;">X</button>

                <!-------------------------------->

                <div id="printdiv" style="font-family:Franklin Gothic Medium; ">

                    <table id="print_add"
                           style="width: margin: 0px auto; padding: 10px; text-align:left; display:none;">
                        <tr>
                            <td style='width:70%;'>

                                <span id="company" style='font-size:42px'><?php echo "Company Name";?></span><br/>

                                <span style='font-size:16px' id="company_add"><?php echo "Street Name"; ?></span><br/>

                                <!--<span style='font-size:14px'><b>Contact: 01XXXXXXXXX, Contact: 01XXXXXXXXX</b></span>-->

                            </td>
                            <td id="logoimage" style='width:30%; text-align:right;'>

                                <!--<img src='/images/logo_ccb.png' style='width:100px; height:auto;'>-->

                            </td>
                        </tr>
                    </table>


                    <table id="mid_section" style="width:100%; font-size:16px; display:none;">

                        <tr>
                            <td style="text-align:center; font-size:22px" colspan="2"><b>SUPPLIER BILL</b></td>
                        </tr>

                        <tr>
                            <td id="cust_add" style="width: 50%; padding-left:10px;"></td>
                            <td id="others_info" style="text-align: right;"></td>
                        </tr>

                    </table>

                    <div id="prodlistDiv" class="row" style="margin: 10px 0;">
                        <div class="col-12" style="padding-right: 0 !important; padding-left: 0 !important;">
                            <table id="prodlist" class="price-table custom-table" style="width: 100%">
                                <tr>
                                    <th style="width: 40%;">Item</th>
                                    <th style="width: 10%;">Price</th>
                                    <th style="width: 10%;">Sub-unit</th>
                                    <th style="width: 10%;">Unit</th>
                                    <th style="width: 10%;">Vat</th>
                                    <th style="width: 10%;">Total</th>
                                    <th style="width: 10%;">Grand Total</th>
                                </tr>
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
                                Note: warranty voide if sticker removed item, burn case and physical damage of goods,
                                warranty not cover mouse, keyboard, cable adopter and powe supply unit of casing.
                            </td>
                        </tr>
                    </table>


                </div>
                <!--------------------------------->

                {{-- <button class="btn btn-success btn-lg print" style="margin-top: 20px;"> Print</button>     --}}

                @php
                    $ledger = false;
                    $pos = false;
                    $settings = \App\GeneralSetting::where('client_id', auth()->user()->client_id)->first();
                    if($settings)
                    {
                        if($settings->print_opt == 1)
                        {
                            $ledger = true;
                        }
                        else
                        {
                            $pos = true;
                        }
                    }
                @endphp

                @if($pos)
                    <button class="btn btn-success btn-lg print" style="margin-top: 20px;"> Print</button>
                @else
                    <button onclick="ledgerPrint()" class="btn btn-success btn-lg" style="margin-top: 20px;">Print
                    </button>
                @endif

            </div>
        </div>

    </div>
@endsection

@section('page-js-script')

    <script type="text/javascript">

        $(document).ready(function () {

            $("#from_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $("#to_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            load_data();

            function load_data(from_date = '', to_date = '') {
                $("#reports").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "precessing": true,
                    "serverSide": true,
                    "columnDefs": [
                        {"orderable": false, "targets": 0},
                        {"bSearchable": true, "bVisible": false, "aTargets": [2]},
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 50,
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };

                        // Total over all pages
                        amount = api
                            .column(7)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        amount = amount.toFixed(2);

                        total = api
                            .column(8)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        total = total.toFixed(2);

                        // Payment Total
                        payTotal = api
                            .column(9)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        payTotal = payTotal.toFixed(2);

                        due = api
                            .column(10)
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);
                        due = due.toFixed(2);

                        // Update footer
                        $(api.column(7).footer()).html(
                            amount
                        );
                        $(api.column(8).footer()).html(
                            total
                        );
                        $(api.column(9).footer()).html(
                            payTotal
                        );
                        $(api.column(10).footer()).html(
                            due
                        );
                    },
                    ajax: {
                        url: '{{ route("get_purchase_report_date") }}',
                        data: {from_date: from_date, to_date: to_date},
                    },
                    columns: [
                        {data: 'date',},
                        {data: 'pur_inv',},
                        {data: 'serial',},
                        {data: 'name',},
                        {data: 'supp_inv',},
                        {data: 'discount',},
                        {data: 'vat_amount',},
                        {data: 'amount',},
                        {data: 'total',},
                        {data: 'payment',},
                        {data: 'due',},
                        {data: 'action', name: 'action', class: 'purinv', orderable: false, searchable: false},
                    ]
                });
            }

            $('#filter').click(function () {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                if (from_date != '' && to_date != '') {
                    $('#reports').DataTable().destroy();
                    load_data(from_date, to_date);
                } else {
                    alert('Both Date is required');
                }
            });
            $('#refresh').click(function () {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#reports').DataTable().destroy();
                load_data();
            });

            $('body').on('click', '.delete', function () {

                if (confirm("Are you Sure to Delete?")) {

                    var purinv = $(this).data("id");

                    var formData = new FormData();
                    formData.append('purinv', purinv);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: "{{ URL::route('delete_purchase')}}",
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
                            alert("Purchase Deleted Successfully!!");
                            location.reload();
                        },
                        success: function (data) {
                            alert("Purchase Deleted Successfully!!");
                            location.reload();
                        }
                    });

                } else {
                    e.preventDefault();
                }

            });


            $('body').on('click', '.edit', function () {
                var purinv = $(this).data("id");
                window.location.replace('/dashboard/purchase_products_edit/'+purinv)

            });


            $('body').on('click', '.view', function () {

                // var s_text = $(this).find('.purinv').html();
                var s_text = $(this).data("id");

                var formData = new FormData();
                formData.append('s_text', s_text);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ URL::route('get_purchase_invoice_details') }}",
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

                        alert(ts.responseText);

                    },
                    success: function (data) {

                        var obj = JSON.parse(JSON.stringify(data));

                        var invoice = obj.invoice;
                        var trow = obj.trow;
                        var company = obj.company;
                        var company_add = obj.company_add;
                        var tcname = obj.supp_name;
                        var tcphone = obj.supp_phone;
                        var amount = obj.amount;
                        var discount = obj.discount;
                        var total = obj.total;
                        var vat_amount = obj.vat_amount;
                        var payment = obj.payment;
                        var date = obj.date;

                        var due = total - payment;

                        ////////////////////////////////////////

                        $('.pos_div').show();

                        $('#print_add').css('width', '100%').css('text-align', 'center');

                        $('#print_add').show();

                        $('#prodlist td').remove();
                        $('.footer-table td').remove();

                        $('#logoimage').css("display", "none");

                        $('#company').css("font-size", "26px");

                        $('#company').html(company);

                        $('#company_add').html(company_add);

                        $("#mid_section").show();

                        $("#cust_add").show();

                        $("#mid_section").append("<table class='footer-table'><tr><td>Supplier: </td><td>" + tcname + "</td></tr><tr><td>Phone: </td><td>" + tcphone + "</td></tr><tr><td>Memo: </td><td>" + invoice + "</td></tr></table>");

                        $("#prodlist").css('border-collapse', 'collapse');

                        //   $("#prodlist tbody tr").each(function() {

                        //         $(this).find("th:eq(4)").remove();
                        //   });

                        $("#prodlist").append(trow);

                        $("#prodlist th").css('font-size', '14px');

                        $("#prodlist td").css('font-size', '14px').css('border', '1px solid #000');

                        $('#prodlistDiv').css("height", "").css("clear", "float").css("background", "#FFF").css("overflow", "");


                        $('#printdiv').append("<table class='footer-table' style='border-collapse: collapse; width:100%;' border='1'><tr><td>Total Tk: </td><td>" + amount + "</td><td> Discount: </td><td>" + discount + "</td></tr><tr></tr><tr><td>Vat: </td><td>" + vat_amount + "</td><td>All Total: </td><td>" + total + "</td></tr><tr><td> Payment: </td><td>" + payment + "</td><td> Due: </td><td>" + due + "</td></tr><tr><td> Date: </td><td>" + date + "</td><td></td><td></td></tr></table>");


                        $("#printRest tr td").css('font-size', '12px').css('border', '1px solid #000').css('border-collapse', 'collapse');


                    }

                });


            });

            $('.close').click(function () {

                $('#cust_add').html("");

                $("#prodlist td").remove();

                $(".footer-table td").remove();

                $('.pos_div').hide();

            });

            $('.print').click(function () {

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


        function Print() {

            //////////////printReceipt///////////

            var prtContent = document.getElementById("printdiv");
            var WinPrint = window.open('', '', 'left=0,top=0, toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        }

        function ledgerPrint() {

            document.getElementById("prodlist").style.width = "100%";

            var printContents = document.getElementById("printdiv").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

    </script>

@stop
