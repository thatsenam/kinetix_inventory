@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper" >
    <div class="row">
      <div class="col-12">
             <div class="card">
                <div class="card-header">
                    <h3>Customer Ledger</h3>
                </div>
                <div class="card-body custom-table">
                    <form action="get_customer_ledger" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <label>Customer Name</label>
                                
                                <select name="customer_id" id="customer_id" class="form-control">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value = {{ $customer->id }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-2">
                                <label>Date From</label>
                                <input type="text" value="{{ date('Y-m-01') }}" class="form-control" name="stdate" id="stdate" autocomplete="off">
                            </div>
                            
                            <div class="col-2">
                                <label>Date To</label>
                                <input type="text" value="{{ date('Y-m-d') }}" class="form-control" name="enddate" id="enddate" autocomplete="off">
                            </div>

                            <div class="col-2 mt-auto">
                                <input type="submit" class="btn btn-success" id="search" value="Search" style="margin-top:20px;">
                            </div>
                        </div>
                    </form>
                </div>
             </div>

             <div class="card">
                 <div class="card-body">
                    <button onclick="makePDF()" class="btn btn-success rounded-0 mb-2">Print</button>
        
                    <div id="printSection">
                        <div class="text-center mb-2" id="heading" style="display: none">
                            <h1>{{ $setting->site_name ?? ''  }}</h1>
                            <h3 class="mb-4">{{ $setting->site_address ?? ''  }}, {{ $setting->phone ?? ''  }}, {{ $setting->email ?? ''  }}</h3>
                            <h4>Customer Ledger Report</h4>
                            <h5 id="customer_name"></h5>
                        </div>
    
                        <table class="table table-bordered collection-table custom-table">
                            
                        </table>
                    </div>
                 </div>
             </div>
        </div>
    </div>
</div>
@endsection

@section('page-js-script')

<script type="text/javascript">

    $(document).ready(function(){

        $('#customer_id').select2({
                placeholder: 'Select Customser',
        });
        
        $( function() {
            $( "#stdate" ).datepicker({dateFormat: 'yy-mm-dd' });
        });
        
        $( function() {
            $( "#enddate" ).datepicker({dateFormat: 'yy-mm-dd' });
        });
        
        $('#search').on('click', function(e){
                
            e.preventDefault();
            
            var customer_name = $('#customer_id option:selected').text();
            var customer_id = $('#customer_id').val();

            if(customer_id == '')
            {
                alert('Customer is Required!');
                return;
            }

            $('#customer_name').append(customer_name);

            var stdate = $('#stdate').val();
            var enddate = $('#enddate').val();

            if(stdate == '' || enddate == '')
            {
                alert('Date is Required!');
                return;
            }
                    
            var formData = new FormData();
            
            formData.append('customer_id', customer_id);
            formData.append('stdate', stdate);
            formData.append('enddate', enddate);
                
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                
            $.ajax({
                url: "{{ URL::route('get_customer_ledger') }}",
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
                //   $('.collection-table').show();
                    $('.collection-table tr').remove();
                    $('.collection-table').append(ts.responseText);
                    
                    //alert(ts.responseText)
                },
                success: function(data){
                    
                    alert(data);
                }
            }); 
        });
        
        
        $('.print').click(function(){
            
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

    function makePDF() {
        var head = document.getElementById("heading");
        head.style.display = "block";
        head.style.color = "black";
        var printContents = document.getElementById("printSection").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
    
</script>

@stop

{{-- <style>

.custom-table{

    width: 100%;
    border-collapse: collapse;
}

.custom-table tr th{
    padding: 5px;
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

</style> --}}