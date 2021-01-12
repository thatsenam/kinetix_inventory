@extends('layouts.header')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section mb-50 section">
        <div class="page-banner-wrap row row-0 d-flex align-items-center">
            <!-- Page Banner -->
            <div class="col-lg-12 col-12 order-lg-2 d-flex align-items-center justify-content-center">
                <div class="page-banner">
                    <h1>Order Details</h1>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="/">HOME</a></li>
                            <li><a href="/myaccount">HOME</a></li>
                            <li><a href="">Order Details</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->
    <!-- Hero Section End -->
    <section class="category-section">
		<div class="container">
            <div class="row">
                <br>
                <div class="col-lg-3 mb-50">
                    <div class="breadcrumb">
                        <div class="panel-body" style="background:aliceblue">
                        <?php if($user_info->image == null){
                            $pro_image = '<img alt="Profile Image" src="/images/man.jpg" style="max-width:100%;">';
                        }else{
                            $pro_image = '<img alt="Profile Image" src="/images/{{$user_info->image}}" style="max-width:100%;">';
                        }
                        ?>
                            <div class=""> <?= $pro_image ?> </div>
                            <h4 class="mb-2 mt-2">{{$user_info->billing_name}}</h4>
                            <div class="buttons">
                                <a href="/myaccount" class="flw">Go To Account Settings</a>
                                <a href="/" class="msg">Go to shop</a>
                            </div>
                            <div class="mgtp-20">
                                <table class="table table-sm table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <td style="width:60%;">Status</td>
                                        <td><span class="badge badge-success">{{$user_info->status}}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Total Orders</td>
                                        <td><?= $ordCount; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Member Since</td>
                                        <td> {{ date('d-m-Y', strtotime($user_info->since))}} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
				</div>
                <div class="col-lg-9 mb-50">
                    <div class="card mb-3">
                        <div class="card-body">
                            <table style="width: 100%;">
                                <tr><td style="font-weight:700;">Order Number #{{$order_data[0]}}</td><td style="font-size: 12px; font-weight:700; text-align: right;">Placed On: {{$order_data[4]}}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" >
                            <div class="status-line status-active start offset-4">
                                <div class="status-ball"></div>
                                <span class="left"><?php if($status_array[0] != null){ echo $status_array[0]; }?></span>
                                <span class="right">Ordered </br> Thank you for your Order.</span>
                            </div>
                            <div class="status-line confirmed offset-4">
                                <div class="status-ball"></div>
                                <span class="left"><?php if($status_array[1] != null){ echo $status_array[1]; }?></span>
                                <span class="right"><?php if($status_array[1] != null){ echo "Confirmed </br> Your Order has been Confirmed.";}
                                    else{ echo "Waiting for Confirmation.";}
                                ?></span>
                            </div>
                            <div class="status-line shipped offset-4">
                                <div class="status-ball"></div>
                                <span class="left"><?php if($status_array[2] != null){ echo $status_array[2]; }?></span>
                                <span class="right"><?php if($status_array[2] != null){ echo "Shipped </br> Your Order has been Shipped.";}
                                    else{ echo "Waiting for shippment.";}
                                ?></span>
                            </div>
                            <div class="status-line delivered offset-4">
                                <div class="status-ball"></div>
                                <span class="left"><?php if($status_array[3] != null){ echo $status_array[3]; }?></span>
                                <span class="right"><?php if($status_array[3] != null){ echo "Delivered </br> Your Order has been Delivered.";}
                                    else{ echo "Waiting for delivery.";}
                                ?></span>
                                <div style="width: 15px; height: 15px; border-radius: 50%; background: #111; position: absolute; left: -10px; bottom: 0;">&nbsp;</div>
                            </div>
                            <div class="status-line canceled offset-4">
                                <div class="status-ball"></div>
                                <span class="left"><?php if($status_array[4] != null){ echo $status_array[4]; }?></span>
                                <span class="right"><?php if($status_array[4] != null){ echo "Canceled </br> Your Order has been Canceled.";}
                                    else{ echo "Sorry for the inconvenience this may caused.";}
                                ?></span>
                                <div style="width: 15px; height: 15px; border-radius: 50%; background: #111; position: absolute; left: -10px; bottom: 0;">&nbsp;</div>
                            </div>
                            
                            <div style="width: 100px; margin: 0 auto; color: #00F; font-size: 13px; cursor: pointer; margin-top: 20px;" class="view_more">View More</div>    
                            
                            <table style="" class="status_table">
                                <?php if($status_array[4] != null){ echo "<tr><td>".$status_array[4]."</td><td>Your Order has been canceled.</td></tr>"; }?>
                                <?php if($status_array[3] != null){ echo "<tr><td>".$status_array[3]."</td><td>Your Order has been Deliveded.</td></tr>"; }?>
                                <?php if($status_array[2] != null){ echo "<tr><td>".$status_array[2]."</td><td>Your Order has been Shipped.</td></tr>"; }?>
                                <?php if($status_array[1] != null){ echo "<tr><td>".$status_array[1]."</td><td>Your Order has been Confirmed.</td></tr>"; }?>
                                <?php if($status_array[0] != null){ echo "<tr><td>".$status_array[0]."</td><td>Thank you for your Order.</td></tr>"; }?>
                                <tr><td colspan="2" style="text-align: center;"><span style="color: blue; font-size:13px; width: 100px; margin: 0 auto; cursor: pointer;"  class="view_less">View Less</span></td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<style>
.status_table{width: 50%; margin: 20px auto;display: none;}
.status_table tr td{font-size: 13px}
.status-line{display: block; border-left: 5px solid #c5c5ce; height:150px; position: relative; text-align: center;font-size: 13px;}
.status-ball{width: 15px; height: 15px; border-radius: 50%; background: #111; position: absolute; left: -10px; top: -10px;}
.status-active{border-left-color: #111 !important;}
.status-cancel{border-left-color: red !important;}
.left {position: absolute;left: -20%;top: 50%;}
.right {position: absolute; left: 10%;top: 40%;}
.buttons {text-align: center;margin-bottom: 15px;display: inline-grid;width:100%;}
.buttons a {padding: 5px 20px;text-transform: capitalize;text-align: center;font-size: 12px;font-weight: 600;color: #111;max-width: 100%;height: 26px;margin: 8px 0;background: white;border: none;box-shadow: 0 0 0 1px #111;cursor: pointer;}
.buttons a:hover {background: #111;color: white;}
@media (max-width: 767px) {
    .left {left: -40%;}
    .status_table{width: 100%;}
    .pr-1{padding-right: 15px !important;}
    .p2{padding-right: 0 !important; margin-bottom: 15px;}
}
</style>

<script>
    $(document).ready(function(){
        var status_array0 = "<?php echo $status_array[0]; ?>";
        var status_array1 = "<?php echo $status_array[1]; ?>";
        var status_array2 = "<?php echo $status_array[2]; ?>";
        var status_array3 = "<?php echo $status_array[3]; ?>";
        var status_array4 = "<?php echo $status_array[4]; ?>";

        if(status_array1 != ''){
            $('.confirmed').addClass('status-active');
            $('.canceled').addClass('d-none');
        }
        
        if(status_array2 != ''){
            $('.shipped').addClass('status-active');
        }
        
        if(status_array3 != ''){
            $('.delivered').addClass('status-active');
        }
        if(status_array4 != ''){
            $('.canceled, .delivered, .shipped, .confirmed').addClass('status-cancel');
            $('.delivered, .shipped, .confirmed').addClass('d-none');
        }
        
        $('.view_more').click(function(){
            $('.status_table').show(500);
            $(this).hide(500);
            
        });
        
        $('.view_less').click(function(){
            $('.status_table').hide(500);
            $('.view_more').show(500);
        });
    });
</script>
@endsection