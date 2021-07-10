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
                            This page has been enhanced for printing. Click the print button at the bottom of the
                            invoice to print.
                        </div>
                        <div class="hr mb-2" style="height: 20px;"></div>
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                </div>
                                <div class="col-md-3">
                                    <img src="/images/theme/{{$settings->logo_small}}" alt="" width="120"
                                         style="border-radius: 25px;">
                                </div>
                                <div class="col-md-6 text-center">
                                    <span>Transport Copy</span>
                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">
{{--                                        <img src="/images/theme/{{$settings->logo_big}}" alt="" width="120" style="border-radius: 25px;"><br>--}}
                                        <small>Date: <?php echo date('Y-m-d'); ?></small>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    From
                                    <address>
                                        <strong>{{  $settings->site_name }}</strong><br>
                                        {{  $settings->site_address }}<br>
                                        Phone: {{  $settings->phone }}<br>
                                        Email: {{  $settings->email }}
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
                                            <th>Product</th>
                                            <th>Qty</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($details as $detail)
                                            <tr>
                                                <td>{{$detail->name}}</td>
                                                <td>{{$detail->qnt}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right">Total Quantity:</td>
                                            <td>{{$total}}</td>
                                        </tr>
                                        <br>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->

                        <!-- <div class="row">
                accepted payments column
                <div class="col-6">
                  <p class="lead">Payment Methods:</p>
                  <img src="../../dist/img/credit/visa.png" alt="Visa">
                  <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../../dist/img/credit/american-express.png" alt="American Express">
                  <img src="../../dist/img/credit/paypal2.png" alt="Paypal">
                </div>
                /.col
                <div class="col-6">
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{ $get_customer->amount }}</td>
                      </tr>

                      <tr>
                        <th>IVA</th>
                        <td>{{ $get_customer->vat }}</td>
                      </tr>
                      <tr>
                        <th>SCharge:</th>
                        <td>{{ $get_customer->scharge }}</td>
                      </tr>
                      <tr>
                        <th>Discount:</th>
                        <td>{{ $get_customer->discount }}</td>
                      </tr>
                      <tr>
                        <th>Total:</th>
                        <td>{{ $get_customer->gtotal }}</td>
                      </tr>
                      <tr>
                        <th>Payment:</th>
                        <td>{{ $get_customer->payment }}</td>
                      </tr>
                      <tr>
                        <th>Due:</th>
                        <td>{{ $get_customer->due }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                /.col
              </div> -->
                            <!-- /.row -->

                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <a href="javascript:window.print();" class="btn btn-default"><i
                                            class="fas fa-print"></i> Print</a>
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
    .hr {
        display: none
    }

    @media print {
        .hr {
            display: block
        }

        .callout {
            display: none
        }

        .main-footer {
            display: none;
        }
    }
</style>
