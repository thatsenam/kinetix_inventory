@extends('admin.pos.master')
        
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Invoice</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
              <li class="breadcrumb-item">Invoice</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="hr mb-4 bg-info" style="height: 30px;"></div>
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> {{$GenSettings->site_name ?? " "}}
                    <small class="float-right">Date: <?php echo date('Y-m-d'); ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  Company
                  <address>
                    <strong>{{$GenSettings->site_name ?? " "}}</strong><br>
                    {{$GenSettings->site_address ?? " "}} <br>
                    {{$GenSettings->phone ?? " "}}<br>
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  To
                  <address>
                    @foreach($cust_details as $cust_detail)
                    <input type="hidden" name="custid" id="custid" value="{{$cust_detail->id}}">
                    <strong>{{$cust_detail->name}}</strong><br>
                    {{$cust_detail->address}}<br>
                    Phone: {{$cust_detail->phone}}<br>
                    @endforeach
                  </address>
                </div>
                <!-- /.col -->
                <div class="col-sm-4 invoice-col">
                  <br>
                  <b>Invoice #{{$get_customer->invoice_no}}</b><br>
                  <b>Payment Date:</b> {{ $get_customer->date }}<br>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Details</th>
                      <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>{{$get_customer->description}}</td>
                        <td>{{$get_customer->amount}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Payment Type</p>
                  {{$get_customer->method}}
                </div>
                <!-- /.col -->
                <div class="col-6">

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Total:</th>
                        <td>{{$get_customer->amount}}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              <br>
              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="javascript:window.print();" class="btn btn-default"><i class="fas fa-print"></i>Print</a>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection

@section('page-js-script')
<script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>
@stop
<style>
  .hr{display: none}
  @media print {
  .hr{display: block}
  .callout { display: none }
  .sidebar { display: none }
  .footer { display: none }
  .no-print { display: none }
  }
</style>