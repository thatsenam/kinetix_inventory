@extends('layouts.header')
@section('content')
    <!-- Hero Section Start -->
    <div class="hero-section section">
        <div class="container">
            <div class="row">
                <div class="col">
                    <!-- Header Category -->
                    <div class="hero-side-category">
                        <!-- Category Toggle Wrap -->
                        <div class="category-toggle-wrap">
                            <!-- Category Toggle -->
                            <button class="category-toggle"><i class="ti-menu"></i> Categories</button>
                        </div>

                        <!-- Category Menu -->
                        <nav class="category-menu category-menu-5">
                            <ul>
                                <?php
                                    $length = count($subMenuArray);
                                    for($i=0; $i< $length; $i++){
                                        echo $subMenuArray[$i];
                                    } 
                                ?>
                            </ul>
                        </nav>

                    </div><!-- Header Bottom End -->

                    <!-- Hero Slider Start -->
                    <div class="hero-slider hero-slider-five fix">
                        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                @foreach($banners as $banner)
                                    <div class="carousel-item @if($loop->first) active @endif">
                                        <img class="d-block w-100" src="/images/banners/{{$banner->image}}" alt="{{$banner->title}}">
                                    </div>
                                @endforeach
                            </div>
                          </div>

                    </div><!-- Hero Slider End -->
                </div>
            </div>
        </div>
    </div><!-- Hero Section End -->

    <!-- Feature Product Section Start -->
    <div class="categories section mt-50 mb-40">
        <div class="container">
            <div class="col-12 mb-20">
                <div class="section-title-one" data-title="FEATURED ITEMS"><h1>BROWSE CATEGORIES</h1></div>
            </div><!-- Section Title End -->
            <div class="col-12">
                <div class="dcategories">
                    <div class="row">
                        @foreach($randomCats as $cat)
                        <div class="col-md-2 col-6">
                            <div class="cat-inner">
                                <a href="/category/{{$cat->url}}" class="w-100"><div class="single-cat">
                                    <div class="cat-img">
                                        @if($cat->image != NULL)
                                        <img src="/images/categories/{{$cat->image}}" alt="">
                                        @else
                                        <img src="/images/no-image.jpg" alt="">
                                        @endif
                                    </div>
                                    <div class="cat-title">
                                        <p>{{$cat->name}}</p>
                                    </div>
                                </div>
                            </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Feature Product Section End -->

    <!-- Mobile Categories -->
    <div class="mobile_category_part section d-none">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ul><li class="mobile_category_item text-center"><a href="https://eorange.shop/new-arrivals"><span><img src="https://eorange.shop/assets/front/images/aside_svg/new.svg"></span>New Arrivals
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/product-request"><span><img src="https://eorange.shop/assets/front/images/aside_svg/shop.svg"></span>
                           Product Request
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/premium/chinabasket"><span><img src="https://eorange.shop/assets/front/images/aside_svg/deal.svg"></span>
                             Mega Deal
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/premium/frozen-fresh"><span><img src="https://eorange.shop/assets/front/images/aside_svg/gift.svg"></span>
                            Frozen &amp; Fresh
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/premium/gift-voucher"><span><img src="https://eorange.shop/assets/front/images/aside_svg/v.svg"></span>
                           Orange Voucher
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/premium/exclusive-offer"><span><img src="https://eorange.shop/assets/front/images/aside_svg/discount.svg"></span>
                            Exclusive Offer
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/category/orange-grocery"><span><img src="https://eorange.shop/assets/front/images/aside_svg/sale.svg"></span>
                            Orange Grocery
                        </a></li> <li class="mobile_category_item text-center"><a href="https://eorange.shop/premium/fishandmeat"><span><img src="https://eorange.shop/assets/front/images/aside_svg/meat.svg"></span>
                            Fish &amp; Meat
                        </a></li></ul></div></div></div></div>
    <!-- Mobile Categories -->

    <!-- Feature Product Section Start -->
    <div class="product-section section mt-50 mb-40">
        <div class="container">
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-12 mb-20">
                    <div class="section-title-one" data-title="FEATURED ITEMS"><h1>BEST SALE PRODUCT</h1></div>
                </div><!-- Section Title End -->
                <!-- Product Tab Filter Start -->
                <div class="col-12">
                    <!-- Product Slider Wrap Start -->
                    <div class="product-slider-wrap product-slider-arrow-two">
                        <!-- Product Slider Start -->
                        <div class="product-slider product-slider-4-full">
                            @foreach($bestSelling as $best)
                            <div class="col pb-20 pt-10">
                                <!-- Product Start -->
                                <div class="ee-product">
                                    <!-- Image -->
                                    <div class="image">
                                        @if($best->product_img == NULL)
                                        <a href="products/{{$best->id}}" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
                                        @else
                                        <a href="products/{{$best->id}}" class="img"><img src="/images/products/{{$best->product_img}}" alt="Product Image"></a>
                                        @endif
                                        <div class="wishlist-compare">
                                            <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
                                        </div>
                                        <a id="ajaxIID" href="/products/{{$best->id}}" data-id="{{$best->id}}" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
                                    </div>

                                    <!-- Content -->
                                    <div class="content">
                                        <!-- Category & Title -->
                                        <div class="category-title">
                                            <a href="/category/{{$best->url}}" class="cat">{{$best->catname}}</a>
                                            <h5 class="title"><a href="/products/{{$best->id}}">{{$best->product_name}}</a></h5>
                                        </div>
                                        <!-- Price & Ratting -->
                                        <div class="price-ratting">
                                            <h5 class="price">
                                                @if($best->after_pprice)
                                                BDT {{$best->after_pprice}}
                                                @else
                                                BDT {{$best->before_price}}
                                                @endif
                                            </h5>
                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- Product End -->
                            </div>
                            @endforeach
                        </div><!-- Product Slider End -->
                    </div>
                    <!-- Product Slider Wrap End -->
                </div><!-- Product Tab Filter End -->
            </div>
        </div>
    </div><!-- Feature Product Section End -->

    <!-- Most Popular Product Section Start -->
    <!-- <div class="product-section section mb-40">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-20">
                    <div class="section-title-one" data-title="FEATURED ITEMS"><h1>MOST POPULAR PRODUCT</h1></div>
                </div>
                <div class="col-12">
                    <div class="product-slider-wrap product-slider-arrow-two">
                        <div class="product-slider product-slider-4-full">
                            @foreach($mostPopular as $most)
                            <div class="col pb-20 pt-10">
                                <div class="ee-product">
                                    <div class="image">
                                        @if($most->product_img == NULL)
                                        <a href="products/{{$most->id}}" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
                                        @else
                                        <a href="products/{{$most->id}}" class="img"><img src="/images/products/{{$most->product_img}}" alt="Product Image"></a>
                                        @endif
                                        <div class="wishlist-compare">
                                            <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
                                        </div>
                                        <a href="/products/{{$most->id}}" data-id="{{$most->id}}" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
                                    </div>

                                    <div class="content">
                                        <div class="category-title">
                                            <a href="/category/{{$most->url}}" class="cat">{{$most->catname}}</a>
                                            <h5 class="title"><a href="/products/{{$most->id}}">{{$most->product_name}}</a></h5>
                                        </div>
                                        <div class="price-ratting">
                                            <h5 class="price">{{$most->after_pprice}}
                                                @if($most->after_pprice)
                                                BDT {{$most->after_pprice}}
                                                @else
                                                BDT {{$most->before_price}}
                                                @endif
                                            </h5>
                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Most Popular Product Section End -->

    <!-- Indivisual collection -->
    <?php
        $length = count($productsArray);
        for($i=0; $i< $length; $i++){
            echo $productsArray[$i];
        }
    ?>
    <!-- Indivisual collection End -->

    <!-- Feature Product Section Start -->
    <div class="product-section section mb-70">
        <div class="container">
            <div class="row">
                <!-- Section Title Start -->
                <div class="col-12 mb-40">
                    <div class="section-title-one" data-title="FEATURED ITEMS"><h1>FEATURED ITEMS</h1></div>
                </div><!-- Section Title End -->
                
                <!-- Product Tab Filter Start -->
                <div class="col-12 mb-30">
                    <div class="product-tab-filter">
                        <!-- Tab Filter Toggle -->
                        <button class="product-tab-filter-toggle">showing: <span></span><i class="icofont icofont-simple-down"></i></button>
                        
                        <!-- Product Tab List -->
                        <ul class="nav product-tab-list">
                            @foreach($categories as $cat)
                            <li><a class="@if($loop->first)active @endif" data-toggle="tab" href="#tab-{{$cat->id}}">{{$cat->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div><!-- Product Tab Filter End -->
                
                <!-- Product Tab Content Start -->
                <div class="col-12">
                    <div class="tab-content">
                        <!-- Tab Pane Start -->
                        <?php
                            $length = count($featuredArray);
                            for($i=0; $i< $length; $i++){
                                echo $featuredArray[$i];
                            } 
                        ?>
                        <!-- Tab Pane End -->
                    </div>
                </div><!-- Product Tab Content End -->
            </div>
        </div>
    </div><!-- Feature Product Section End -->

    <div class="collection section mb-80">
        <div class="container">
            <div class="col-12 mb-20">
                <div class="section-title-one" data-title="FACE MASKS"><h1>SEE MORE PRODUCTS</h1></div>
            </div>
            <div class="col-12">
                <div class="loadMoreDiv row"></div>
                <div class="text-center" style="padding: 20px 0;">
                    <button id="loadMore" class="btn btn-medium btn-">Load More</button>
                </div>
                <div id="wait" class="mb-3 d-none">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: rgb(255, 255, 255); display: block; shape-rendering: auto;" width="80px" height="80px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                    <g transform="translate(50,50)"><circle cx="0" cy="0" r="8.333333333333334" fill="none" stroke="#e15b64" stroke-width="4" stroke-dasharray="26.179938779914945 26.179938779914945">
                    <animateTransform attributeName="transform" type="rotate" values="0 0 0;360 0 0" times="0;1" dur="1s" calcMode="spline" keySplines="0.2 0 0.8 1" begin="0" repeatCount="indefinite"></animateTransform>
                    </circle><circle cx="0" cy="0" r="16.666666666666668" fill="none" stroke="#f47e60" stroke-width="4" stroke-dasharray="52.35987755982989 52.35987755982989">
                    <animateTransform attributeName="transform" type="rotate" values="0 0 0;360 0 0" times="0;1" dur="1s" calcMode="spline" keySplines="0.2 0 0.8 1" begin="-0.2" repeatCount="indefinite"></animateTransform>
                    </circle><circle cx="0" cy="0" r="25" fill="none" stroke="#f8b26a" stroke-width="4" stroke-dasharray="78.53981633974483 78.53981633974483">
                    <animateTransform attributeName="transform" type="rotate" values="0 0 0;360 0 0" times="0;1" dur="1s" calcMode="spline" keySplines="0.2 0 0.8 1" begin="-0.4" repeatCount="indefinite"></animateTransform>
                    </circle>
                    <circle cx="0" cy="0" r="33.333333333333336" fill="none" stroke="#abbd81" stroke-width="4" stroke-dasharray="104.71975511965978 104.71975511965978">
                    <animateTransform attributeName="transform" type="rotate" values="0 0 0;360 0 0" times="0;1" dur="1s" calcMode="spline" keySplines="0.2 0 0.8 1" begin="-0.6" repeatCount="indefinite"></animateTransform>
                    </circle><circle cx="0" cy="0" r="41.666666666666664" fill="none" stroke="#849b87" stroke-width="4" stroke-dasharray="130.89969389957471 130.89969389957471">
                    <animateTransform attributeName="transform" type="rotate" values="0 0 0;360 0 0" times="0;1" dur="1s" calcMode="spline" keySplines="0.2 0 0.8 1" begin="-0.8" repeatCount="indefinite"></animateTransform>
                    </circle></g></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Indivisual collection -->
    <!-- <div class="collection section mb-80">
        <div class="container">
            <div class="col-12 mb-20">
                <div class="section-title-one" data-title="BEST DEALS"><h1>WOMEN'S FASHION</h1></div>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-md-2 p-0" style="min-height: 190px;">
                        <div class="card bg-light rounded-0">
                            <div class="card-body p-2 product-slider-arrow-two">
                                <div class="product-slider category-slider">
                                    <div class="col text-center">
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-1.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-2.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-3.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-3.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                    </div>
                                    <div class="col text-center">
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-7.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-8.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                        <a href="#" class="col-12">
                                            <img src="images/brands/brand-9.png" title="" alt="" class="m-auto p-2" style="max-width:100%; height:70px;">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 p-0">
                        <img src="images/we.jpg" alt="" style="max-width: 100%;">
                    </div>
                    <div class="col-md-7 p-0" style="min-height: 190px;">
                        <div class="all-brands">
                            <div class="card rounded-0">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6 product-cat-box text-center col-md-4 p-0" style="border-right: 1px solid #edebef;border-bottom: 1px solid #edebef;">
                                            <a href="#" class="p-3">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="images/womens.png" alt="">
                                                <p>Hoodie</p>
                                            </a>
                                        </div>
                                        <div class="col-6 product-cat-box text-center col-md-4 p-0" style="border-right: 1px solid #edebef;border-bottom: 1px solid #edebef;">
                                            <a href="#" class="p-3">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="images/womens.png" alt="">
                                                <p>Pants</p>
                                            </a>
                                        </div>
                                        <div class="col-6 product-cat-box text-center col-md-4 p-0" style="border-right: 1px solid #edebef;border-bottom: 1px solid #edebef;">
                                            <a href="#" class="p-3">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="images/womens.png" alt="">
                                                <p>Hoodie</p>
                                            </a>
                                        </div>
                                        <div class="col-6 product-cat-box text-center col-md-4 p-0" style="border-right: 1px solid #edebef;border-bottom: 1px solid #edebef;">
                                            <a href="#" class="p-3">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="images/womens.png" alt="">
                                                <p>Hoodie</p>
                                            </a>
                                        </div>
                                        <div class="col-6 product-cat-box text-center col-md-4 p-0" style="border-right: 1px solid #edebef;border-bottom: 1px solid #edebef;">
                                            <a href="#" class="p-3">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="images/womens.png" alt="">
                                                <p>Hoodie</p>
                                            </a>
                                        </div>
                                        <div class="col-6 product-cat-box text-center col-md-4 p-0" style="border-right: 1px solid #edebef;border-bottom: 1px solid #edebef;">
                                            <a href="#" class="p-3">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="images/womens.png" alt="">
                                                <p>Hoodie</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Indivisual collection End -->

    <!-- Indivisual collection -->
    <div class="vv-section section pt-50 pb-50 bg-light">
        <div class="container">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-3 p-0">
                        <div class="brand-section-intro">
                            <div class="card rounded-0">
                                <div class="card-body pb-70 pt-70">
                                    <div class="text-center">
                                        <img src="images/brands/brand-1.png" alt=""> 
                                        <p class="mt-2"><strong>Brands of the Week</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 p-0">
                        <div class="all-brands">
                            <div class="card rounded-0">
                                <div class="col-12">
                                    <div class="row">
                                        @foreach($topBrands as $brand)
                                        <div class="col-6 col-md-2 p-0 border">
                                            @if(!$brand->image)
                                            <a href="/brands/{{$brand->url}}">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="/images/brands/brand-13.png" alt="">
                                            </a>
                                            @else
                                            <a href="/brands/{{$brand->url}}">
                                                <img style="max-width: 100%; padding: 10px 20px;" src="/images/brands/{{$brand->image}}" alt="">
                                            </a>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Indivisual collection eND-->
<script>
$(document).ready(function(){
    
    ///// Load More Section  
    var formData = new FormData();
        var loadmore_data = [];
        	
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            
        $.ajax({
            url: "{{ URL::route('loadmore') }}",
            method: 'get',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: "JSON",
            error: function(ts) {         
                alert(ts.responseText);
            },
            success: function(data){ 
            
                loadmore_data = data;
                for(i =1; i <= 12; i++){
                    var pdiv = data[i];
                    $('.loadMoreDiv').append(pdiv); 
                }
            }
            		   
        });
            
        var iterate = 7;
        var limit = 12;
        $('#loadMore').click(function(){
                
            jQuery.ajax({
                beforeSend: function() {
                    $("div").removeClass("d-none");
                    $("#wait").show();
                    $("#loadMore").hide();
                },
                success: function(data) {
                    $("#wait").hide();
                    for(i = iterate; i <= limit; i++){
                        var pdiv = loadmore_data[i];
                        $('.loadMoreDiv').append(pdiv);
                    }
                    $("#loadMore").show();
                }
            });
            iterate = (iterate + 6);
            limit = (limit + 6);
    
        });
});
</script>
@endsection
