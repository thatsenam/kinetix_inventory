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
              <li class="breadcrumb-item active">Invoice</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <h5><i class="fas fa-info"></i> Note:</h5>
              This page has been enhanced for printing. Click the print button at the bottom of the invoice to print.
            </div>
            <div class="hr mb-2" style="height: 20px;"></div>
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <div class="col-12">
                  <h4>
                    <i class="fas fa-globe"></i> Beautyshop, Inc.
                    <small class="float-right">Date: <?php echo date('Y-m-d'); ?></small>
                  </h4>
                </div>
                <!-- /.col -->
              </div>
              <!-- info row -->
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                  From
                  <address>
                    <strong>Beautyshop, Inc.</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (804) 123-5432<br>
                    Email: info@beautyshop.com
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
                  <b>Order Date:</b> {{$get_customer->date}}<br>
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
                      <th>Image</th>
                      <th>Product</th>
                      <th>Qty</th>
                      <th>Price</th>
                      <th>Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr>
                            <td><img src="/images/products/{{$detail->image}}" alt="" width="30px"></td>
                            <td>{{$detail->name}}</td>
                            <td>{{$detail->qnt}}</td>
                            <td>{{$detail->price}}</td>
                            <td><?php 
                            $paid = $get_customer->payment;
                            $ship = 0;
                            $stotal = $detail->qnt * $detail->price;
                            echo number_format((float)$stotal, 2, '.', '');
                            ?></td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Payment Methods:</p>
                  <img src="../../dist/img/credit/visa.png" alt="Visa">
                  <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../../dist/img/credit/american-express.png" alt="American Express">
                  <img src="../../dist/img/credit/paypal2.png" alt="Paypal">
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?php $total_amount = 0;
								foreach($details as $item){
									$total_amount = $total_amount + ($item->price * $item->qnt);
								}
								echo number_format((float)$total_amount, 2, '.', '');
                            ?>
                        </td>
                      </tr>
                      <?php 
                        $total = ($total_amount + $ship) - $paid;
                      ?>
                      <tr>
                        <th>Paid</th>
                        <td><?= number_format((float)$paid, 2, '.', ''); ?></td>
                      </tr>
                      <tr>
                        <th>Shipping:</th>
                        <td><?= number_format((float)$ship, 2, '.', ''); ?></td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td><?= number_format((float)$total, 2, '.', ''); ?></td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <!-- this row will not appear when printing -->
              <div class="row no-print">
                <div class="col-12">
                  <a href="javascript:window.print();" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
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
//   window.addEventListener("load", window.print());
</script>
@stop
<style>
  .hr{display: none}
  @media print {
  .hr{display: block}
  .callout { display: none }
  }
</style>