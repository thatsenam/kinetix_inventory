@extends('layouts.header')
@section('content')
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section">
        <div class="page-banner-wrap row row-0 d-flex align-items-center ">
            <!-- Page Banner -->
            <div class="col-12 order-lg-2 d-flex align-items-center justify-content-center">
                <div class="page-banner">
                    <h1>{{$brands->name}}</h1>
                    <div class="breadcrumb">
                        <ul>
                            <li><a href="/">HOME</a></li>
                            <li><a href="/shop/all">Brands</a></li>
                            <li><a href="#">{{$brands->name}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->
    <!-- Single Product Section Start -->
    <div class="product-section section mt-30 mb-90">
        <div class="container">
            <div class="row">
                <div class="col mb-50">
                    <div class="category-page-title"><h4>CATEGORIES - {{$brands->name}}</h4></div>
                </div>
            </div>
            <div class="row">
                @if($brandDatas->count() > 0)
                    @foreach($brandDatas as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 pb-30 pt-10">
                            <!-- Product Start -->
                            <div class="ee-product">
                                <!-- Image -->
                                <div class="image">
                                    @if($product->product_img == NULL)
                                        <a href="products/{{$product->id}}" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
                                    @else
                                        <a href="products/{{$product->id}}" class="img"><img src="/images/products/{{$product->product_img}}" alt="Product Image"></a>
                                    @endif
                                    <div class="wishlist-compare">
                                        <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
                                        <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
                                    </div>
                                    <a href="/products/{{$product->id}}" data-id="{{$product->id}}" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
                                </div>
                                <!-- Content -->
                                <div class="content">
                                    <!-- Category & Title -->
                                    <div class="category-title">
                                        <a href="#" class="cat">{{$product->bname}}</a>
                                        <h5 class="title"><a href="/products/{{$product->id}}">{{$product->name}}</a></h5>
                                    </div>
                                    <!-- Price & Ratting -->
                                    <div class="price-ratting">
                                        <h5 class="price">
                                            @if($product->after_pprice)
                                            BDT-{{$product->after_pprice}}
                                            @else
                                            BDT-{{$product->before_price}}
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
				@else
					<div class="card">
						<div style="border: 1px solid #111; padding: 8px 10px;">
							<div class="text-danger text-center"><b>No product found!</b></div>
						</div>
					</div>
				@endif
            </div>
                    
            <!-- Pagination -->
            <div class="col-12">
            {{$brandDatas->links()}}
            </div>
        </div>
    </div><!-- Single Product Section End -->
@endsection