@extends('layouts.header')
@section('content')

    <!-- Product Section Start -->
    <div class="product-section section mb-40">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 col-lg-8 col-12 order-lg-2 mb-50">
                    <div class="row mb-10">
                        <div class="col">
                            <!-- Shop Top Bar Start -->
                            <div class="shop-top-bar with-sidebar">
                                <!-- Product View Mode -->
                                <div class="product-view-mode">
                                    <a class="active" href="#" data-target="grid"><i class="fa fa-th"></i></a>
                                    <a href="#" data-target="list"><i class="fa fa-list"></i></a>
                                </div>
                                <!-- Product Showing -->
                                <div class="product-showing">
                                    <p>Showing</p>
                                    <select onchange="window.location=this.value" name="showing" class="nice-select">
                                        <option value="/shop/sort=25">12</option>
                                        <option value="/shop/sort=25">25</option>
                                        <option value="/shop/sort=50">50</option>
                                    </select>
                                </div>

                                <!-- Product Short -->
                                <div class="product-short">
                                    <p>Short by</p>
                                    <select onchange="window.location=this.value" name="sortby" class="nice-select">
                                        <option value="/shop/sort=trending">Trending items</option>
                                        <option value="/shop/sort=popular">Most popular items</option>
                                        <option value="/shop/sort=newest">Newest items</option>
                                        <option value="/shop/sort=price-asc">Price: low to high</option>
                                        <option value="/shop/sort=price-desc">Price: high to low</option>
                                    </select>
                                </div>

                                <!-- Product Pages -->
                                <div class="product-pages">
                                    <p>Pages {{$products->currentPage()}} of {{$products->lastPage()}}</p>
                                </div>
                            </div><!-- Shop Top Bar End -->
                            
                        </div>
                    </div>
                    
                    <!-- Shop Product Wrap Start -->
                    <div class="shop-product-wrap grid with-sidebar row">
                        @if($products->count() > 0)
                        @foreach($products as $product)
                            <div class="col-xl-3 col-md-3 col-6 pb-30 pt-10">
                                <!-- Product Start -->
                                <div class="ee-product">
                                    <!-- Image -->
                                    <div class="image">
                                        @if($product->product_img == NULL)
                                            <a href="/products/{{$product->id}}" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
                                        @else
                                            <a href="/products/{{$product->id}}" class="img"><img src="/images/products/{{$product->product_img}}" alt="Product Image"></a>
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
                                            <a href="#" class="cat">{{$product->catname}}</a>
                                            <h5 class="title"><a href="/products/{{$product->id}}">{{$product->name}}</a></h5>
                                        </div>
                                        <!-- Price & Ratting -->
                                        <div class="price-ratting">
                                            <h5 class="price">
                                                @if($product->after_pprice)
                                                BDT {{$product->after_pprice}}
                                                @else
                                                BDT {{$product->before_price}}
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
                                <!-- Product List Start -->
                                <div class="ee-product-list">
                                    <!-- Image -->
                                    <div class="image">
                                        @if($product->product_img == NULL)
                                            <a href="/products/{{$product->id}}" class="img"><img src="/images/product/product-two-1.png" alt="Product Image"></a>
                                        @else
                                            <a href="/products/{{$product->id}}" class="img"><img src="/images/products/{{$product->product_img}}" alt="Product Image"></a>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="content">
                                        <!-- Category & Title -->
                                        <div class="head-content">
                                            <div class="category-title mb-20">
                                                <a href="#" class="cat">{{$product->catname}}</a>
                                                <h5 class="title"><a href="/products/{{$product->id}}">{{$product->name}}</a></h5>
                                            </div>
                                            <h5 class="price">@if($product->after_pprice)
                                                BDT {{$product->after_pprice}}
                                                @else
                                                BDT {{$product->before_price}}
                                                @endif</h5>
                                        </div>
                                        
                                        <div class="left-content">
                                            <div class="ratting">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-half-o"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                            <div class="desc">
                                                <p>{{strip_tags($product->product_desc)}}</p>
                                            </div>
                                            <div class="actions">
                                                <a href="/products/{{$product->id}}" data-id="{{$product->id}}" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
                                                <div class="wishlist-compare">
                                                    <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
                                                    <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="right-content">
                                            <div class="specification">
                                                <h5>Specifications</h5>
                                                <ul>
                                                    {{strip_tags($product->product_specs)}}
                                                </ul>
                                            </div>
                                            <span class="availability">Availability: @if($product->stock > 0)<span>In Stock</span> @else <span class="text-danger">Out of stock</span> @endif</span>
                                        </div>
                                    </div>
                                </div><!-- Product List End -->
                            </div>
                        @endforeach
                    @else
                        <div class="card">
                            <div style="border: 1px solid #111; padding: 8px 10px;">
                                <div class="text-danger text-center"><b>No product found!</b></div>
                            </div>
                        </div>
                    @endif
                    </div><!-- Shop Product Wrap End -->
                    
                    <!-- Pagination -->
                    <div class="col-12">
                    {{$products->links()}}
                    </div>
                </div>
                
                <div class="shop-sidebar-wrap col-xl-3 col-lg-4 col-12 order-lg-1 mb-15">
                    <div class="shop-sidebar mb-35">
                        <h4 class="title">CATEGORIES</h4>
                        <ul class="sidebar-category">
                            <li><a href="#">Tv & Audio System</a></li>
                            <li><a href="#">Computer & Laptop</a>
                                <ul class="children">
                                    <li><a href="#">Smartphone</a></li>
                                    <li><a href="#">headphone</a></li>
                                    <li><a href="#">accessories</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Phones & Tablets</a>
                                <ul class="children">
                                    <li><a href="#">Samsome</a></li>
                                    <li><a href="#">GL Stylus</a></li>
                                    <li><a href="#">Uawei</a></li>
                                    <li><a href="#">Cherry Berry</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Home Appliances</a></li>
                            <li><a href="#">Kitchen appliances</a>
                                <ul class="children">
                                    <li><a href="#">Power Bank</a></li>
                                    <li><a href="#">Data Cable</a></li>
                                    <li><a href="#">Power Cable</a></li>
                                    <li><a href="#">Battery</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Printer & Scanner</a>
                                <ul class="children">
                                    <li><a href="#">Desktop Headphone</a></li>
                                    <li><a href="#">Mobile Headphone</a></li>
                                    <li><a href="#">Wireless Headphone</a></li>
                                    <li><a href="#">LED Headphone</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Camera & CC Camera</a></li>
                            <li><a href="#">Smart Watch</a></li>
                            <li><a href="#">Accessories</a></li>
                        </ul>
                    </div>
                    
                    <div class="shop-sidebar mb-35">
                        <h4 class="title">Brand</h4>
                        <ul class="sidebar-brand">
                            <li><a href="#">Apple</a></li>
                            <li><a href="#">Sumsang</a></li>
                            <li><a href="#">Phillips</a></li>
                            <li><a href="#">Zeon</a></li>
                            <li><a href="#">Panasonic</a></li>
                            <li><a href="#">Uawei</a></li>
                        </ul>
                    </div>
                    
                    <div class="shop-sidebar mb-35">
                        <h4 class="title">Price</h4>
                        <div class="sidebar-price">
                            <div id="price-range"></div>
                            <input type="text" id="price-amount" readonly>
                        </div>
                    </div>
                    
                    <div class="shop-sidebar mb-35">
                    
                        <h4 class="title">Color</h4>
                        
                        <ul class="sidebar-brand">
                            <li><a href="#">White</a></li>
                            <li><a href="#">Black</a></li>
                            <li><a href="#">Cosmic Black</a></li>
                            <li><a href="#">Rose Gold</a></li>
                            <li><a href="#">Spacegrey</a></li>
                        </ul>
                        
                    </div>
                    
                    <div class="shop-sidebar mb-35">
                    
                        <h4 class="title">Tags</h4>
                        
                        <div class="sidebar-tags">
                            <a href="#">smartphone</a>
                            <a href="#">Iron</a>
                            <a href="#">Trimer</a>
                            <a href="#">Smart Watch</a>
                            <a href="#">Play Station</a>
                            <a href="#">Oven</a>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div><!-- Feature Product Section End -->



<script>
    $(document).ready(function(){
        $("body").on("click", "a.add-to-cart", function () {
            var id = $(this).data('id');
            var formData = new FormData();
            formData.append('id', id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "/ajaxCart",
                method: 'post',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $(this).addClass("added");
                },
                error: function(ts) {
                    alert(ts.responseText);
                },
            });
        });
    });
</script>
@endsection