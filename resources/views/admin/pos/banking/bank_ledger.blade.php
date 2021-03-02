@extends('admin.pos.master')
        
@section('content')

<div class="content-wrapper" >
    <div class="row">
      <div class="col-12">
             <div class="card">
                <div class="card-header">
                    <h3>Bank Ledger</h3>
                </div>
                <div class="card-body">
                    <form action="get_sales_report_date" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <label>Bank Name</label>
                                
                                <select name="bank_id" id="bank_id" class="form-control">
                                        <option>Select Bank</option>
                                        
                                        @foreach($bank_info as $row)
                                        
                                        <option value = {{$row->id}}>{{$row->name}}</option>
                                        
                                        @endforeach
                                        
                                </select>
                                
                            </div>
                            <div class="col-3">
                                <label>Account</label>
                                <select name="account_id" id="account_id" class="form-control">
                                        <option>Select Account</option>
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
                            <h4>Bank Ledger Report</h4>
                            <h5 id="bank_name_account"></h5>
                        </div>
    
                        <table class="table collection-table custom-table">
                            
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
        
        $( function() {
            $( "#stdate" ).datepicker({dateFormat: 'yy-mm-dd' });
        } );
        
        $( function() {
            $( "#enddate" ).datepicker({dateFormat: 'yy-mm-dd' });
        } );
        
        $('#bank_id').change(function(){
            
            var bank_id = $(this).val();
            
            var formData = new FormData();
        	    formData.append('bank_id', bank_id);
            
             $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('get_bank_acc') }}",
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
            		      
            		      //alert(ts.responseText)
            		      
                          $('#account_id option').remove();
                          $('#account_id').append(ts.responseText);
                          
                      },
                      success: function(data){
                         
                          alert(data);
                      }
            		   
                }); 
            
        });
        
        $('#search').on('click', function(e){
            
            e.preventDefault();
            
            var bank_name = $('#bank_id option:selected').text();
            var account_name = $('#account_id option:selected').text();
            $('#bank_name_account').append(bank_name + ' (' + account_name + ')');

            var stdate = $('#stdate').val();
            var enddate = $('#enddate').val();
            var check_type = $('#check_type').val();
        			
        	var formData = new FormData();
        	    formData.append('bank_name', bank_name);
        	    formData.append('account_name', account_name);
        	    formData.append('stdate', stdate);
        	    formData.append('enddate', enddate);
        	    formData.append('check_type', check_type);
        			
        	    $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
        			
                $.ajax({
            		  url: "{{ URL::route('get_bank_ledger') }}",
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
        

        
        $('body').on('click', '.clear', function(){
            

        var transid = $(this).closest('tr').attr('data-transid');
        
        var check_type = $('#check_type').val();
            
        var formData = new FormData();
            formData.append('transid', transid);
            formData.append('check_type', check_type);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
        $.ajax({
            url: "{{ URL::route('save_check_clearance') }}",
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
                
                
            },
            success: function(data){
            
                alert(data);
                
            }
            
        });            
        })
        
        $('.close').click(function(){
            
            $('#cust_add').html("");
            
            $("#prodlist td").remove();
            
            $(".footer-table td").remove();
            
            $('.pos_div').hide();
            
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

