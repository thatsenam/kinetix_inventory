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

                                </div>
                                <div class="col-md-3">
                                    <div class="float-right">
                                        {{--                                        <img src="/images/theme/{{$settings->logo_big}}" alt="" width="120"--}}
                                        {{--                                             style="border-radius: 25px;">--}}
                                        <br>
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

                            @php
                                $is_product_base = $GenSettings->vat_type === 'Product Base';
                            @endphp
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Sub-unit</th>
                                            <th>Unit</th>
                                            <th>Price</th>
                                            <th>Subtotal</th>
                                            {{--                                            <th class="{{ !$is_product_base?'d-none':'' }}">Vat(%)</th>--}}
                                            <th>G.Total</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($details as $detail)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$detail->name}}</td>
                                                <td>{{$detail->box}}</td>
                                                <td>{{$detail->qnt}}</td>
                                                <td>{{$detail->price}}</td>
                                                <td><?php
                                                    $paid = $get_customer->payment;
                                                    $ship = 0;
                                                    $stotal = $detail->qnt * $detail->price;

                                                    echo number_format((float)$stotal, 2, '.', '');
                                                    ?></td>
                                                @if($is_product_base)
                                                    {{--                                                    <td>{{ ($detail->vat / $stotal) * 100 }} </td>--}}
                                                @endif
                                                @if($is_product_base)
                                                    <td>{{$detail->vat + $stotal }}</td>
                                                @else
                                                    <td>{{ $stotal }}</td>
                                                @endif

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
                                <div class="col-6 text-center">
                                    <br>
                                    <br>
                                    <br>
                                    <div class="signature">
                                        <span>N.B: This is computer generated invoice, no signature required</span>
                                    </div>
                                    <div class="thanks">
                                        <span style="color:#888888;">THANKS FOR YOUR BUSINESS</span>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td>{{ $get_customer->amount }}</td>
                                            </tr>

                                            <tr>
                                                <th>Total IVA</th>
                                                @if($is_product_base)
                                                    <td>{{ $get_customer->vat }}</td>
                                                @else
                                                    <td>{{ $get_customer->vat }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th>SCharge:</th>
                                                <td>{{ $get_customer->scharge }}</small></td>
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
                                <!-- /.col -->
                            </div>
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
        window.addEventListener("load", () => {
            window.print()
            setTimeout(()=>{
                window.location.href = "/dashboard/sales_invoice" ;
            },3000)

        });
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
