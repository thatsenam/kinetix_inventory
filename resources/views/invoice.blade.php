<?php 

$status = '';

?>
<?php $discount = ''; ?>

@foreach($order as $ord)
    <?php
        $orderno = $ord->order_number; 
        if($ord->is_paid == 0){
            $status = "Due";
        }else{
            $status = "Paid";
        }
        if($ord->discount == null){
            $discount = 0;
        }else{
            $discount = $ord->discount;
        }
        $order_date = $ord->created_at;
        $shipname = $ord->shipping_name;
        $shipphone= $ord->shipping_phone;
        $shipinfo= $ord->shipping_info;
        $name= $ord->name;
        $ptype= $ord->payment_method;
        $phone= $ord->phone_no;
        $scharge= $ord->delivery_charge;
    ?>
@endforeach

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KineCom. | Inovice No. <?= $orderno ?></title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" type="text/css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css"> -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
</head>
<body style="font-family: 'Dosis', sans-serif;">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-12 m-auto">
            <div id="print-btn" class="mb-3 mt-3">
                <button type="button" class="btn btn-sm btn-outline-info" onclick="window.print()" style="padding: 10px;">Print Invoice</button>
            </div>
            <div id="print-div" class="card">
                <div class="card-body p-0">
                    <div class="row p-3 mt-20 mb-20">
                        <div class="col-md-4 col-6 m-auto">
                            <img src="/images/logo.png" width="100" class="mb-3">
                        </div>
                        <br>
                        <div class="col-md-3 mt-20 col-7 m-auto">
                            <div class="row">
                                <form action="{{ route('create-payment') }}" method="post">
                                    @csrf
                                    <input type="submit" class="btn-block btn btn-info" value="Pay Now">
                                </form>
                                <div class="btn btn-danger ml-2"><?= $status; ?></div>
                            </div>
                        </div>
                        <div class="col-md-5 pt-4 col-12 m-auto text-right p1">
                            <p class="font-weight-bold mb-1">ORDER NO: #<?= $orderno ?></p>
                            <p class="text-muted">ORDER DATE: <?= $order_date ?></p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row p-3">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-4">Shipping Information</p>
                            <span><b>Name:</b> <?= $shipname ?></span> <br>
                            <span><b>Phone:</b> <?= $shipphone ?></span> <br>
                            <span><b>Address:</b> <?= $shipinfo ?></span>
                        </div>

                        <div class="col-md-6 text-right p2">
                            <p class="font-weight-bold mb-4">Payment Details</p>
                            <p class="mb-1"><span class="text-muted">NAME: </span> <?= $name ?></p>
                            <p class="mb-1"><span class="text-muted">DISCOUNT: </span> <?= $discount ?></p>
                            <p class="mb-1"><span class="text-muted">Payment Type: </span> <?= $ptype ?></p>
                            <p class="mb-1"><span class="text-muted">Payment Status: </span> <?= $status ?></p>
                        </div>
                    </div>

                    <div class="row p-3">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="border-0 text-uppercase small font-weight-bold">#</th>
                                        <th class="border-0 text-uppercase small font-weight-bold mobile">Image</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Product Name</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Quantity</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Unit Cost</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; 
                                      $subtotal = 0;
                                      foreach($order_details as $ordD){
                                        $subtotal = $subtotal + ($ordD->price * $ordD->qnt);
                                      }
                                ?>
                                @foreach($order_details as $ordD)
                                  <?php $price = $ordD->price * $ordD->qnt; ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td class="mobile">
                                            @if(!$ordD->image)
                                            <img src="/images/no-image.jpg" alt="" width="50px" height="50px" style="border-radius: 100%;">
                                            @else
                                            <img src="/images/products/{{$ordD->image}}" alt="" width="50px" height="50px" style="border-radius: 100%;">
                                            @endif
                                        </td>
                                        <td>{{$ordD->pname}} <br> <span>{{$ordD->filter}}: {{$ordD->filter_value}}</span></td>
                                        <td>{{$ordD->qnt}}</td>
                                        <td>{{$ordD->price}}</td>
                                        <td><?= $price ?></td>
                                    </tr>
                                @endforeach
                                    <tr>
                                        <td colspan="5" class="text-right">Sub - Total amount</td>
                                        <td>BDT <?= $subtotal ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">(-) Discount</td>
                                        <td>BDT <?= $discount ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">(+) Shipping Charge</td>
                                        <td>BDT <?= $scharge ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><b>Grand Total</b></td>
                                        <td><b>BDT <?php 
                                            $gtotal = ($subtotal - $discount) + $scharge;
                                            echo $gtotal;
                                        ?></span></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-5 mb-5 text-center text-info small">by : <a class="" target="_blank" href="/">KineCom.</a></div>

</div>

<style>
@media only screen and (max-width: 767px) {
  .p1{
    text-align: center !important;
    margin-top: 20px;
  }
  .p2{
    text-align: left !important;
    margin-top: 30px;
  }
}
@media only screen and (max-width: 600px) {
  .mobile {
    width: 20px;
  }
}
@media print{
    #print-btn {display: none;}
    #print-div{margin-top: 50px;}
}
</style>

</body>
</html>

<?php 

// Session::forget('order_number');
Session::forget('CouponAmount');
Session::forget('session_id');

?>
