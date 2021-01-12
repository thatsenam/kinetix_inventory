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
        if($ord->payment_status == null){
            $pstatus = "Pending";
        }else{
            $pstatus = "Completed";
        }
        if($ord->discount == null){
            $discount = 0;
        }else{
            $discount = $ord->discount;
        }
        if($ord->order_note == null){
            $ord_note = "No order note or special instruction giveb by the customer.";
        }else{
            $ord_note = $ord->order_note;
        }
        $order_date = $ord->created_at;
        $shipname = $ord->shipping_name;
        $shipphone= $ord->shipping_phone;
        $shipinfo= $ord->shipping_info;
        $name= $ord->name;
        $email= $ord->email;
        $phone= $ord->phone_no;
        $ptype= $ord->payment_method;
        $smethod= $ord->shipping_method;
        $scharge= $ord->delivery_charge;
    ?>
@endforeach
<!DOCTYPE html">
<html lang="en">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<title>Invoice #<?= $orderno ?></title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" type="text/css">
</head>

<body>

	<div id="page-wrap">
        <div id="button-div">
            <a href="" class="btn btn-success" id="print-btn" onclick="window.print()">Print Invoice</a>
            <a class="btn btn-info" href="{{ url()->previous() }}">Back to previous page</a>
        </div>
		<textarea id="header">INVOICE</textarea>
		
		<div class="row mb-5">
            <div class="col-7">
                <h3>{{$GenSettings->site_name}}.</h3>
                <span><b>Address</b> {{$GenSettings->site_address}}</span> <br>
                <span><b>Phone:</b> {{$GenSettings->phone}}</span> <br>
                <span><b>E-mail:</b> {{$GenSettings->email}}</span>
            </div>
            <div id="logo" class="col-5">
              <div id="logoctr">
                <a href="javascript:;" id="change-logo" title="Change logo">Change Logo</a>
                <a href="javascript:;" id="save-logo" title="Save changes">Save</a>
                |
                <a href="javascript:;" id="delete-logo" title="Delete logo">Delete Logo</a>
                <a href="javascript:;" id="cancel-logo" title="Cancel changes">Cancel</a>
              </div>
              <div id="logohelp">
                <input id="imageloc" type="text" size="50" value="" /><br />
                (max width: 540px, max height: 100px)
              </div>
              <img id="image" src="/images/theme/{{$GenSettings->logo_small}}" alt="logo" />
            </div>
		</div>
		
		<div style="clear:both"></div>
		
		<div id="customer" class="row">
            <div class="bill-to col-4 pr-0">
                <div class="card">
                    <div class="card-header">Bill To</div>
                    <div class="card-body">
                        <span><b>Name</b> <?= $name ?></span> <br>
                        <span><b>Phone:</b> <?= $phone ?></span> <br>
                        <span><b>E-mail:</b> <?= $email ?></span> <br>
                        <span><b>Address</b> <?= $shipinfo ?></span>
                    </div>
                </div>
            </div>
        
            <div class="ship-to col-4">
                <div class="card">
                    <div class="card-header">Ship To</div>
                    <div class="card-body">
                        <span><b>Name</b> <?= $shipname ?></span> <br>
                        <span><b>Phone:</b> <?= $shipphone ?></span> <br>
                        <span><b>E-mail:</b> <?= $email ?></span> <br>
                        <span><b>Address</b> <?= $shipinfo ?></span>
                    </div>
                </div>
            </div>
            <table id="meta" class="table table-sm">
                <tr>
                    <td class="meta-head">Invoice #</td>
                    <td><textarea><?= $orderno; ?></textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Date</td>
                    <td><textarea id=""><?= date('F d, Y h:i:s A', strtotime($order_date)) ?></textarea></td>
                </tr>
                <tr>
                    <td class="meta-head">Status</td>
                    <td><div class=""><?= $status; ?></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Shipp. Method</td>
                    <td><div class="due"><?= $smethod; ?></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Pay. Method</td>
                    <td><div class="due"><?= $ptype; ?></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Pay. Status</td>
                    <td><div class="due"><?= $pstatus; ?></div></td>
                </tr>
            </table>
		</div>

        <div class="row"><div class="col-12 mt-3"><div class="breadcrumb"><b>Order Note: </b> <?=  $ord_note; ?></div></div></div>
		
		<div class="order-table table-responsive">
            <table id="items" class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Products</th>
                <th>Unit Cost</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php 
                $i = 1; 
                $subtotal = 0;
                foreach($order_details as $ordD){
                    $subtotal = $subtotal + ($ordD->price * $ordD->qnt);
                }
            ?>
            @foreach($order_details as $ordD)
            <?php 
            
                $price = $ordD->price * $ordD->qnt; 
            
            ?>
            <tr class="item-row">
                <td class="item-name"><div class="delete-wpr"><span><?= $i++ ?></span></div></td>
                <td>
                    @if(!$ordD->image)
                    <img src="/images/no-image.jpg" alt="" width="70px" height="70px" style="border-radius: 100%;">
                    @else
                    <img src="/images/products/{{$ordD->image}}" alt="" width="70px" height="70px" style="border-radius: 100%;">
                    @endif
                </td>
                <td class="description">{{$ordD->name}}</td>
                <td><span class="cost">{{$ordD->price}}</span></td>
                <td><span class="qty">{{$ordD->qnt}}</span></td>
                <td><span class="price">৳<?= $price ?></span></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="4" class="blank"> </td>
                <td colspan="1" class="total-line">Subtotal</td>
                <td class="total-value"><div id="subtotal">৳<?= $subtotal ?></div></td>
            </tr>
            <tr>
                <td colspan="4" class="blank"> </td>
                <td colspan="1" class="total-line">Deliver Charge</td>
                <td class=""><div id="">৳<?= $scharge ?></div></td>
            </tr>
            <tr>
                <td colspan="4" class="blank"> </td>
                <td colspan="1" class="total-line">Discount</td>

                <td class="total-value">৳<?= $discount ?></td>
            </tr>
            <tr>
                <td colspan="4" class="blank"> </td>
                <td colspan="1" class="total-line balance">Grand Total</td>
                <td class="total-value balance"><div class="due">৳<?php 
                                $gtotal = ($subtotal - $discount) + $scharge;
                                echo $gtotal;
                            ?></div></td>
            </tr>
            </table>
        </div>
		
		<div id="terms">
		  <h5>Terms</h5>
		  <textarea>Finance Charge of 1.5% will be made on unpaid balances after 30 days.</textarea>
		</div>
        <div class="mt-2 mb-5 text-center text-info">Prepared by : <a class="" target="_blank" href="{{ route('home') }}">{{$GenSettings->site_name}}.</a></div>
	</div>
</body>

<style>
* { margin: 0; padding: 0; }
.table-responsive{overflow-x: visible;}
body { font: 15px/1.4 "Cairo", sans-serif;}
#page-wrap { width: 990px; margin: 30px auto; }
textarea { border: 0; font: 15px "Cairo", sans-serif; overflow: hidden; resize: none; }
table { border-collapse: collapse; }
table td, table th { border: 1px solid rgb(0 0 0 / 12%); padding: 5px; }

#header { height: 35px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }

#address { width: 100%; height: auto; float: left; }
#customer { overflow: hidden; }

#logo { text-align: right; float: right; position: relative; border: 1px solid #fff; max-width: 540px; max-height: 100px; overflow: hidden; margin: auto;}
#logo:hover, #logo.edit { border: 1px solid #000; margin-top: 0px; max-height: 125px; }
#logoctr { display: none; }
#logo:hover #logoctr, #logo.edit #logoctr { display: block; text-align: right; line-height: 25px; background: #eee; padding: 0 5px; }
#logohelp { text-align: left; display: none; font-style: italic; padding: 10px 5px;}
#logohelp input { margin-bottom: 5px; }
.edit #logohelp { display: block; }
.edit #save-logo, .edit #cancel-logo { display: inline; }
.edit #image, #save-logo, #cancel-logo, .edit #change-logo, .edit #delete-logo { display: none; }
#customer-title { font-size: 20px; font-weight: bold; float: left; }

#meta { margin-top: 1px; width: 310px; float: right; }
#meta td { text-align: right;  }
#meta td.meta-head { text-align: left; background: #eee; width: 130px;}
#meta td textarea { width: 100%; height: 20px; text-align: right; }

#items { clear: both; margin: 0px 0 0 0;}
#items th { background: #eee; }
#items tr.item-row td {vertical-align: center; }
/* #items td.description { min-width: 300px; }
#items td.item-name { width: 175px; } */
#items td.description textarea, #items td.item-name textarea { width: 100%; }
#items td.total-line {text-align: right; }
#items td.total-value { border-left: 0; padding: 10px; }
#items td.total-value textarea { height: 20px; background: none; }
#items td.balance { background: #eee; }

#terms { text-align: center; margin: 20px 0 0 0; }
#terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0; }
#terms textarea { width: 100%; text-align: center;}

textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#e4e6d8; }
@media print{
    #button-div {display: none;}
}
</style>

</html>