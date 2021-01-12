@extends('layouts.header')
@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<div class="container">
    <div class="text-center page-section section pt-90 pb-50"> 
        <h3>CHECKOUT</h3> 
    </div>
    <div class="section">
        <div class="col-md-12">
            <form action="{{route('checkout.create')}}" method="POST" id="checkForm" style="margin-bottom: 75px;">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6" style="margin: 0 auto !important;">
                        <div class="checkout-box shipping-div">
                            <h2 style="background-color: #111; color: #fff;">Delivery Information</h2>
                            <div class="checkout-info">
                                <span>Phone Number</span>
                                <input type="text" class="form-control mb-2" id="s_phone" name="s_phone" placeholder=""/>
                                <span>Email (Optional)</span>
                                <input type="email" class="form-control mb-2" id="s_email" name="s_email" placeholder=""/>
                                <span>Name</span>
                                <input type="text" class="form-control mb-2" id="s_name" name="s_name" placeholder=""/>
                                <span class="">Address</span>
                                <textarea rows="4" class="form-control mb-2" id="s_address" name="s_address"> </textarea>
                                <span class="">Special Note or Message</span>
                                <textarea rows="4" class="form-control mb-2" id="s_note" name="s_note" placeholder="If you have any special instruction or any message you describe it here..."></textarea>
                            </div>
                        </div>
                        <div style="margin: 50px auto;text-align: center;">
                            <button class="btn btn-dark btn-lg next">CLICK TO CONTINUE</button>
                        </div>
                    </div>

                    <div class="col-md-6 other-half" style="display:none">
                        <div class="checkout-box shipandpay" style="display: none;">
                            <h2 style="background-color: #111; color: #fff;">Shipping and Payment</h2>
                            <div class="checkout-info">
                                <span class="">Payment Method</span>
                                <div class="form-group m-2">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="ShipMethod1" name="smethod" value="Inside City">
                                        <label for="ShipMethod1" class="custom-control-label">Home Delivery Within City</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="ShipMethod2" name="smethod" value="Outside City">
                                        <label for="ShipMethod2" class="custom-control-label">Transport Delivery (Country Wide)</label>
                                    </div>
                                </div>
                                <span class="">Payment Method</span>
                                <div class="form-group m-2">
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="PayMethod1" name="pmethod" value="Cash On Delivery">
                                        <label for="PayMethod1" class="custom-control-label">Cash On Delivery</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="PayMethod2" name="pmethod" value="PayPal">
                                        <label for="PayMethod2" class="custom-control-label">PayPal</label>
                                    </div>
                                    <div class="Box m-0 pt-3" style="display: none;">
                                        <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="shopping_cart_div" style="display: none;border-radius: 10px;">
                            <!-- Cart Total -->
                            <div class="mt-20 mb-20">
                                <h4 class="checkout-title">Cart Summary</h4>
                                <div class="checkout-cart-total">
                                    <h4>Product <span>Total</span></h4>
                                    <ul>
                                        @foreach($userCart as $cart)
                                        <li>{{$cart->product_name}} X {{$cart->quantity}} <span>BDT.{{$cart->price}}</span></li>
                                        @endforeach
                                    </ul>
                                    <?php $total_amount = 0;
                                        foreach($userCart as $cart){
                                            $total_amount = $total_amount + ($cart->price * $cart->quantity);
                                        }
                                    ?>
                                    <p>Sub Total <span>BDT.<?= $total_amount ?></span></p>
                                    @if(!empty(Session::get('CouponAmount')))
                                    <p>Discount <span>BDT <?= Session::get('CouponAmount') ?></span></p>
                                    <h4>Grand Total <span>BDT <?= $total_amount - Session::get('CouponAmount') ?></span></h4>
                                    @else
                                    <h4>Grand Total <span>BDT <?= $total_amount ?></span></h4>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="discount" id="discount" value="<?= Session::get('CouponAmount') ?>">
                        <input type="hidden" name="discountCode" id="discountCode" value="<?= Session::get('CouponCode') ?>">
                        <div class="save_div" style="margin: 0 auto; display: none;">
                            <div class="row">
                                <div class="col-md-6"><a href="{{route('cart')}}" class="btn btn-dark btn-block">Update Cart</a></div>
                                <div class="col-md-6">
                                    @if($userCart->count() >0)
                                    <input type="submit" name="save" id="save" class="btn btn-dark btn-block" value="PLACE ORDER">
                                    @else
                                    <div class="card">
                                        <div style="border: 1px solid #111; padding: 8px 10px;">
                                            <div class="text-danger text-center"><b>Your cart is empty!</b></div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function(){

    $('#s_phone').on('blur', function(e){
        var phone = $(this).val();
        var formData = new FormData();
        formData.append('phone', phone);
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ URL::route('get_details') }}",
            method: 'post',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                var str = JSON.stringify(response, null, 4)
                var obj = JSON.parse(str);

                $('#s_name').val(obj.name);
                $('#s_email').val(obj.email);
                $('#s_address').html(obj.address);
            },
            error: function (jqXHR) {
                // alert(jqXHR.responseText);
            }
        });
    });

    $('.next').click(function(e){

       e.preventDefault();

       if($('#s_name').val() == '' || $('#s_phone').val() == '' || $('#s_address').val() == ''){
           alert("Name and phone fields can't be empty! Please give delivery informatiion to continue.");
           return false;
       }
       $('.other-half').show();
       $(this).hide();
       $('.shipandpay').show();
       $('.shopping_cart_div').show();
       $('.save_div').show();

    });

    $('#save').click(function(e){

       e.preventDefault();

        var s_email = $("#s_email").val();
        var s_name = $("#s_name").val();
	    var s_phone = $("#s_phone").val();
	    var s_address = $("#s_address").val();
	    var s_note = $("#s_note").val();
	    var discount = $("#discount").val();
	    var discountCode = $("#discountCode").val();
        var smethod = $('input[name=smethod]:checked').val();
        var pmethod = $('input[name=pmethod]:checked').val();

		$.ajax({
			url: "{{ URL::route('checkout.create') }}",
			data:'s_email=' + s_email + '&s_name=' + s_name + '&s_phone=' + s_phone + '&s_name=' + s_name + '&s_address=' + s_address + '&s_note=' + s_note + '&discount=' + discount + '&discountCode=' + discountCode + '&smethod=' + smethod + '&pmethod=' + pmethod,
			type:'post',
			success:function(response){
				console.log(response);
                window.location.href = '/invoice';
			},
			error: function(ts) {
                alert(ts.responseText);
            }
		});

    });
});
</script>

<style>
.checkout-box {
    width: 100%;
    border: 1px solid #6666;
    margin-bottom: 12px;
}
.checkout-box > h2 {
    font-size: 20px;
    font-weight: 500;
    margin: 0;
    text-align: center;
    border-bottom: 1px solid #6666;
    padding: 5px;
}
.checkout-info {
    padding: 22px;
}
</style>

@endsection