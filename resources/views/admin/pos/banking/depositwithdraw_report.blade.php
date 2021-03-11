@extends('admin.pos.master')

@section('content')

    <div class="content-wrapper">
        <section class="content">
            <h2 class="ml-2">Bank Withdraw or Deposit Report</h2>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body custom-table">
                        <div class="row">
                            <div class="col-3">
                                <label>Select Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="deposit">Deposit</option>
                                    <option value="withdraw">Withdraw</option>
                                </select>
                            </div>
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
                        <table class="collection-table custom-table table table-bordered mt-4" style="display:none;">
                            
                            <tr><th>Date</th><th>Bank Name</th><th>Bank Account</th><th><th>Amount</th><th>Action</th></tr>
                            
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
                var type = $('#type').val();
        			
        	    var formData = new FormData();
        	    formData.append('stdate', stdate);
        	    formData.append('enddate', enddate);
        	    formData.append('type', type);
        			
        	    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
                    url: "{{ URL::route('get_bankdepowithdraw_report') }}",
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
                var type = $('#type').val();
                if(stdate != '' &&  enddate != ''){
                    $('#report').DataTable().destroy();
                    load_data(stdate, enddate);
                }
                else{alert('Both Date is required');}
            });

        });

    </script>

@stop

