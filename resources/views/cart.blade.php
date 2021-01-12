@extends('layouts.header')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section">
        <div class="page-banner-wrap row row-0 d-flex align-items-center ">
            <!-- Page Banner -->
            <div class="col-12 order-lg-2 d-flex align-items-center justify-content-center">
                <div class="page-banner">
                    <h1>Cart</h1>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="/">HOME</a></li>
                            <li><a href="/shop/all">Shop</a></li>
                            <li><a href="">Cart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->
    <!-- Cart Page Start -->
    <div class="page-section section pt-30 pb-50">
        <div class="container">
            @if ($success = Session::get('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $success }}</strong>
                </div>
            @endif
            @if ($error = Session::get('flash_message_error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $error }}</strong>
                </div>
            @endif
            <div class="alert alert-success alert-block" id="CartMsg" style="display:none;"></div>
            <div class="row">
                <div class="col-md-8 p-0">
                    <div class="table-responsive">				
                        <!-- Cart Table -->
                        <div class="cart-table table-sm mb-40">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Image</th>
                                        <th class="pro-title">Product</th>
                                        <th class="pro-price">Price</th>
                                        <th class="pro-quantity">Quantity</th>
                                        <th class="pro-subtotal">Total</th>
                                        <th class="pro-remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($userCart as $cart)
                                    <tr>
                                        <td class="pro-thumbnail">
                                            <a href="#">
                                            @if(!$cart->image)
                                            <img src="/images/no-image.jpg" alt="Product">
                                            @else
                                            <img src="/images/products/{{$cart->image}}" alt="Product">
                                            @endif
                                            </a>
                                        </td>
                                        <td class="pro-title"><a href="/products/{{$cart->product_id}}">{{$cart->product_name}}</a></td>
                                        <td class="pro-price"><span>BDT {{$cart->price}}</span></td>
                                        <td class="pro-quantity">
                                            <input type="hidden" value="{{$cart->id}}" id="rowID{{$cart->id}}">
                                            <input type="number" value="{{$cart->quantity}}" id="upCart{{$cart->id}}" class="form-control" style="max-width:70px;">
                                        </td>
                                        <td class="pro-subtotal"><span>BDT {{$cart->price*$cart->quantity}}</span></td>
                                        <td class="pro-remove"><a href="{{ url('/cart/delete-product/'.$cart->id) }}"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-15">
                        <!-- Discount Coupon -->
                        <div class="discount-coupon">
                            <h4>Discount Coupon Code</h4>
                            <form action="{{url('cart/apply-coupon')}}" method="POST">
                            @csrf
                                <div class="row">
                                    <div class="col-md-7 pr-0 col-12 mb-25">
                                        <input type="text" name="inputCoupon" placeholder="Coupon Code">
                                    </div>
                                    <div class="col-md-5 col-12 mb-25">
                                        <input type="submit" value="Apply Code">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Cart Summary -->
                    <div class="mb-40">
                        <div class="cart-summary">
                            <div class="cart-summary-wrap">
                            <?php $total_amount = 0;
                                foreach($userCart as $item){
                                    $total_amount = $total_amount + ($item->price * $item->quantity);
                                }
                            ?>
                                <h4>Cart Summary</h4>
                                @if(!empty(Session::get('CouponAmount')))
                                <p>Sub Total <span>BDT <?= $total_amount ?></span></p>
                                <p>Coupon Discount <span>BDT <?= Session::get('CouponAmount') ?></span></p>
                                <h2>Grand Total <span>BDT <?= $total_amount - Session::get('CouponAmount') ?></span></h2>
                                @else
                                <p>Sub Total <span>BDT <?= $total_amount ?></span></p>
                                <h2>Grand Total <span>BDT <?= $total_amount ?></h2>
                                @endif
                            </div>
                            <div class="cart-summary-button">
                                <a href="{{ url('/checkout') }}" class="mr-2 btn btn-medium btn-circle">Proceed Checkout</a>
                                <a href="{{ url('/shop/all') }}" class="btn bg-warning btn-medium btn-circle">Update Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End --> 
<script>
    $(document).ready(function(){
        $("#CartMsg").hide();
        @foreach($userCart as $cart)
        $("#upCart{{$cart->id}}").on('change keyup', function(){
            var newQTY = $("#upCart{{$cart->id}}").val();
            var rowID = $("#rowID{{$cart->id}}").val();
            $.ajax({
                url:'{{url('/cart/update-cart')}}',
                data:'rowID=' + rowID + '&newQTY=' + newQTY,
                type:'get',
                success:function(response){
                    $("#CartMsg").show();
                    console.log(response);
                    $("#CartMsg").html(response);
                    window.setTimeout(function(){ 
                        location.reload();
                    } ,1000);
                },
                error: function(ts) {         
                    alert(ts.responseText);
                }
            });
        });
        @endforeach
    });
</script>
@endsection