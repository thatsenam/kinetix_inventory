@extends('admin.pos.master')

@section('content')

    <div class="content-wrapper">
        <section class="content">
            <h2 class="ml-2">Bank Transfer Report</h2>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body custom-table">
                        <div class="row">
                            <div class="col-3">
                                <label>Date From</label>
                                <input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="stdate" id="stdate" autocomplete="off">
                            </div>

                            <div class="col-3">
                                <label>Date To</label>
                                <input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="enddate" id="enddate" autocomplete="off">
                            </div>

                            <div class="col-3">
                                <input type="submit" class="btn btn-success btn-lg" id="search" value="Search"
                                    style="margin-top:20px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="report" class="table w-100">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>From Bank</th>
                                    <th>From Account</th>
                                    <th>To Bank</th>
                                    <th>To Account</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
@endsection

@section('page-js-script')

    <script type="text/javascript">
        $(document).ready(function() {

            $( "#stdate").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            $( "#enddate").datepicker({
                dateFormat: 'yy-mm-dd'
            });

            load_data ();

            function load_data(stdate = '', enddate = ''){
                $("#report").DataTable({
                    "responsive": true,
                    "autoWidth": false,
                    "precessing": true,
                    "serverSide": true,
                    "columnDefs": [
                        { "orderable": false, "targets": 0 },
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    "pageLength": 50,
                    "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                    };
        
                    // Grand Total
                    totalAmount = api
                        .column( 5 )
                        .data()
                        .reduce( function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0 );
        
                    // Update footer
                    
                    $( api.column( 5 ).footer() ).html(
                        totalAmount
                    );
                },
                    ajax: {
                        url: '{{ route("get_bank_transfer_report") }}',
                        data:{stdate:stdate, enddate:enddate},
                    },
                    columns: [
                        {data:'date',},
                        {data:'from_bank',},
                        {data:'from_acc',},
                        {data:'to_bank',},
                        {data:'to_acc',},
                        {data:'amount',},
                        {data: 'action'},
                    ]
                });
            }

            $('body').on('click', '.delete', function () {
                var invoice = $(this).attr("data-id");
                swal.fire({
                    title: "Are you sure to delete?",
                    text: "All related data will be deleted!!",
                    showCancelButton: !0,
                    confirmButtonText: "Yes!",
                    cancelButtonText: "No!",
                    reverseButtons: !0,
                }).then (function (e) {
                    if (e.value === true) {
                        var formData = new FormData();
                        formData.append('invoice', invoice);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('delete_bank_transfer')}}",
                            method: 'post',
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (results) {
                                if (results.success === true) {
                                    swal.fire("Success!", results.message, "success");
                                } else {
                                    swal.fire("Failed!", results.message, "error");
                                }
                                location.reload();
                            }
                        });
                    } else {
                        e.dismiss;
                    }
                }, function (dismiss) {
                    return false;
                });
            });


            $('#search').click(function(){
                var stdate = $('#stdate').val();
                var enddate = $('#enddate').val();
                if(stdate != '' &&  enddate != ''){
                    $('#report').DataTable().destroy();
                    load_data(stdate, enddate);
                }
                else{alert('Both Date is required');}
            });

            $('.close').click(function() {

                $('#cust_add').html("");

                $("#prodlist td").remove();

                $(".footer-table td").remove();

                $('.pos_div').hide();

            });

            $('.print').click(function() {

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

    </script>

@stop

