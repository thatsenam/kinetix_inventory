<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Category;
use App\Products;
use App\Banner;
use App\Brands;
use App\Order_detail;
use Request;

class IndexController extends Controller
{
    public function index(){

        // $category = Category::where('parent_id', 0)->limit(10)->get();
        // $subMenuArray = array();
        
        // $src = substr (Request::root(), 7);
        // foreach($category as $cat){
        //     $catid = $cat['id'];
        //     $cat_image = $cat['image'];
        //     $subcategory = Category::where('parent_id', $catid)->limit(3)->get();

        //     $count = $subcategory->count();
        //     if($count > 0){
        //         $subMenuDiv = "<li class='menu-item-has-children'><a href='#'>".$cat['name']."</a>";
        //     }else{
        //         $subMenuDiv = "<li><a href='#'>".$cat['name']."</a>";
        //     }
            
        //     if($subcategory->count() > 0){
        //         $subMenuDiv .= "<ul id=sub-$catid class='category-mega-menu'>";
        //         foreach($subcategory as $subcat){
                
        //             $subcatid = $subcat['id'];
        //             $subcatname = $subcat['name'];
        //             $subcatparent = $subcat['parent_id'];
                        
        //             $subMenuDiv .= "<li class='menu-item-has-children'><a href='#'>".$subcatname."</a>";
                    
        //             $subMenuDiv .= "<ul>";
                    
        //             $subsubcategory = Category::where('parent_id', $subcatid)->limit(6)->get();
                    
        //             foreach($subsubcategory as $subsubcat){
                    
        //                 $subsubcatid = $subsubcat['id'];
        //                 $subsubcatname = $subsubcat['name'];
        //                 $subsubcatparent = $subcat['parent_id'];
                        
        //                 //$subMenuDiv .= "<li><a href= $src.'/".$subsubcatid."'>".$subsubcatname."</a></li>";
        //                 $subMenuDiv .= '<li><a href="'.$src."/subcategories/".$subsubcatid.'">'.$subsubcatname.'</a></li>';
        //             }
                    
        //             $subMenuDiv .= "</ul></li>";
                    
        //         }
    
        //         $subMenuDiv .= "</ul>";
        //     }
        //     $subMenuDiv .= "</li>";
            
        //     $subMenuArray[] = $subMenuDiv;
            
        // }

        // $categories = Category::where('parent_id', '!=', 0)->inRandomOrder()->limit(5)->get();
        // $featuredArray = array();
        // foreach($categories as $index=>$fcat){
        //     $fcatid = $fcat['id'];
        //     $products = Products::select('products.id', DB::raw('substr(product_name, 1, 50) as product_name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname','categories.url')
        //     ->join('categories', 'products.cat_id', '=', 'categories.id')->where('cat_id',$fcatid)->limit(10)->get();
        //     if($index === 0){
        //         $featuredProductDiv = '<div class="tab-pane fade active show" id="tab-'.$fcatid.'">';
        //     }else{
        //         $featuredProductDiv = '<div class="tab-pane fade" id="tab-'.$fcatid.'">';
        //     }
        //     $featuredProductDiv .= '<div class="product-slider-wrap product-slider-arrow-one">';
        //     $featuredProductDiv .= '<div class="product-slider product-slider-4">';
        //     foreach($products as $p){
        //         $featuredProductDiv .= '<div class="col pb-20 pt-10">';
        //         $featuredProductDiv .= '<div class="ee-product">';
        //         if($p->product_img == NULL){
        //             $featuredProductDiv .= '<div class="image">
        //             <a href="/products/'.$p->id.'" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
        //             <div class="wishlist-compare">
        //                 <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
        //                 <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
        //             </div>
        //             <a href="#" data-id="'.$p->id.'" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
        //             </div>';
        //         }else{
        //             $featuredProductDiv .= '<div class="image">
        //             <a href="/products/'.$p->id.'" class="img"><img src="/images/products/'.$p->product_img.'" alt="Product Image"></a>
        //             <div class="wishlist-compare">
        //                 <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
        //                 <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
        //             </div>
        //             <a href="#" data-id="'.$p->id.'" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
        //             </div>';
        //         }
        //         $featuredProductDiv .= '<div class="content">
        //         <div class="category-title">
        //             <a href="/category/'.$p->url.'" class="cat">'.$p->catname.'</a>
        //             <h5 class="title"><a href="/products/'.$p->id.'">'.$p->product_name.'</a></h5>
        //         </div>';
        //         if($p->after_pprice == NULL){
        //             $featuredProductDiv .= '<div class="price-ratting">
        //             <h5 class="price">BDT '.$p->before_price.'</h5>
        //             <div class="ratting">
        //                 <i class="fa fa-star"></i>
        //                 <i class="fa fa-star"></i>
        //                 <i class="fa fa-star"></i>
        //                 <i class="fa fa-star-half-o"></i>
        //                 <i class="fa fa-star-o"></i>
        //             </div>
        //         </div>';
        //         }else{
        //             $featuredProductDiv .= '<div class="price-ratting">
        //             <h5 class="price"><span>BDT '.$p->before_price.'</span>BDT '.$p->after_pprice.'</h5>
        //             <div class="ratting">
        //                 <i class="fa fa-star"></i>
        //                 <i class="fa fa-star"></i>
        //                 <i class="fa fa-star"></i>
        //                 <i class="fa fa-star-half-o"></i>
        //                 <i class="fa fa-star-o"></i>
        //             </div>
        //         </div>';
        //         }
        //         $featuredProductDiv .=  '</div>';
        //         $featuredProductDiv .= '</div></div>';
        //     }
        //     $featuredProductDiv .= '</div></div></div>';

        //     $featuredArray[] = $featuredProductDiv;
        // }

        // $featuredCats = Category::where('featured', 1)->inRandomOrder()->limit(3)->get();
        // $productsArray = array();
        // foreach($featuredCats as $index=>$pcat){
        //     $pcatid = $pcat['id'];
        //     $featuredProducts = Products::select('products.id', DB::raw('substr(product_name, 1, 50) as product_name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname','categories.url')
        //     ->join('categories', 'products.cat_id', '=', 'categories.id')->where('cat_id',$pcatid)->limit(12)->get();

        //     $productsArrayDiv ='<div class="collection section mb-30">
        //     <div class="container">
        //         <div class="col-12 mb-20">
        //             <div class="section-title-one" data-title="'.$pcat->name.'"><h1>'.$pcat->name.'</h1></div>
        //         </div>';
        //         $productsArrayDiv .= '<div class="col-12">
        //         <div class="row">';

        //         foreach($featuredProducts as $fp){
        //             $productsArrayDiv .= '<div class="col-6 text-center col-md-2 p-0">';
        //             $productsArrayDiv .= '<div class="ee-product">';
        //             if($p->product_img == NULL){
        //                 $productsArrayDiv .= '<div class="image">
        //                 <a href="/products/'.$fp->id.'" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
        //                 <div class="wishlist-compare">
        //                     <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
        //                     <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
        //                 </div>
        //                 <a href="#" data-id="'.$fp->id.'" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
        //                 </div>';
        //             }else{
        //                 $productsArrayDiv .= '<div class="image">
        //                 <a href="/products/'.$fp->id.'" class="img"><img src="/images/products/'.$fp->product_img.'" alt="Product Image"></a>
        //                 <div class="wishlist-compare">
        //                     <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
        //                     <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
        //                 </div>
        //                 <a href="#" data-id="'.$fp->id.'" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
        //                 </div>';
        //             }
        //             $productsArrayDiv .= '<div class="content">
        //             <div class="category-title">
        //                 <a href="/category/'.$fp->url.'" class="cat">'.$fp->catname.'</a>
        //                 <h5 class="title"><a href="/products/'.$p->id.'">'.$fp->product_name.'</a></h5>
        //             </div>';
        //             if($fp->after_pprice == NULL){
        //                 $productsArrayDiv .= '<div class="price-ratting">
        //                 <h5 class="price">BDT '.$fp->before_price.'</h5>
        //                 <div class="ratting">
        //                     <i class="fa fa-star"></i>
        //                     <i class="fa fa-star"></i>
        //                     <i class="fa fa-star"></i>
        //                     <i class="fa fa-star-half-o"></i>
        //                     <i class="fa fa-star-o"></i>
        //                 </div>
        //             </div>';
        //             }else{
        //                 $productsArrayDiv .= '<div class="price-ratting">
        //                 <h5 class="price"><span>BDT '.$fp->before_price.'</span>BDT '.$fp->after_pprice.'</h5>
        //                 <div class="ratting">
        //                     <i class="fa fa-star"></i>
        //                     <i class="fa fa-star"></i>
        //                     <i class="fa fa-star"></i>
        //                     <i class="fa fa-star-half-o"></i>
        //                     <i class="fa fa-star-o"></i>
        //                 </div>
        //             </div>';
        //             }
        //             $productsArrayDiv .=  '</div>';
        //             $productsArrayDiv .= '</div></div>';
        //         }

        //         $productsArrayDiv .= '</div></div>';

        //         $productsArrayDiv .= '<div class="col-12 mt-50 mb-50">';
        //         $productsArrayDiv .= '<a href="/category/'.$pcat->url.'" class="btn btn-medium btn-circle float-right bg-warning m-auto">Browse All Products</a>';
        //         $productsArrayDiv .= '</div>';

        //     $productsArrayDiv .= '</div></div>';

        //     $productsArray[] = $productsArrayDiv;
        // }

        
        // $banners = Banner::where('status',1)->where('type',"homeslider")->get();
        // $randomCats = Category::where('parent_id','!=' , 0)->inRandomOrder()->limit(12)->get();
        // $bestSelling = DB::table('products')->select('products.id', DB::raw('substr(product_name, 1, 50) as product_name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname','categories.url')
        // ->join('categories', 'products.cat_id', '=', 'categories.id')
        // ->leftJoin('purchase_details','products.id','=','purchase_details.pid')
        // ->selectRaw('products.*, COALESCE(sum(purchase_details.qnt),0) total')
        // ->groupBy('products.id')
        // ->orderBy('total','desc')
        // ->take(10)
        // ->get();
        // $mostPopular = DB::table('products')->select('products.id', DB::raw('substr(product_name, 1, 50) as product_name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname','categories.url')
        // ->join('categories', 'products.cat_id', '=', 'categories.id')
        // ->leftJoin('sales_invoice_details','products.id','=','sales_invoice_details.pid')
        // ->selectRaw('products.*, COALESCE(sum(sales_invoice_details.qnt),0) total')
        // ->groupBy('products.id')
        // ->orderBy('total','desc')
        // ->take(10)
        // ->get();

        // $topBrands = Brands::inRandomOrder()->limit(12)->get();

        // $all = Products::select('products.id', DB::raw('substr(product_name, 1, 50) as product_name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname','categories.url')
        // ->join('categories', 'products.cat_id', '=', 'categories.id')->where('cat_id',$fcatid)->limit(12)->get();
        
        // return view('welcome')->with(compact('banners','randomCats','bestSelling','mostPopular','category','categories','subMenuArray','featuredArray','topBrands','productsArray'));
        return redirect('/dashboard/pos');
    }


    public function products_loadmore(){          
        
        $products = DB::table('products')->select('products.id', DB::raw('substr(product_name, 1, 50) as name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname','categories.url')
        ->join('categories', 'products.cat_id', '=', 'categories.id')
        ->orderByRaw("RAND()")
        ->limit(60)
        ->get();
        
        $prod_array = array();
        $i = 1;
                    
        foreach($products as $prod){
            $id = $prod->id;
            $name = $prod->name;
            $bprice = $prod->before_price;
            $aprice = $prod->after_pprice;
            $image = $prod->product_img;
            $catname = $prod->catname;
            $url = $prod->url;
                        
            $src = "public/images/products/".$image;
            $href = "/products/".$id;
                        
            $prod_array[$i] = '<div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                <div class="single-product">
                    <div class="product-img">
                        <span class="pro-label new-label">new</span>
                        <a href="'.$href.'"><img src="'.$src.'" alt=""></a>
                        <div class="product-action clearfix">
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Wishlist"><i class="fa fa-heart"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Quick View"><i class="fa fa-search-plus"></i></a>
                            <a href="#" data-toggle="tooltip" data-placement="top" title="Add To Cart"><i class="fa fa-cart-plus"></i></a>
                        </div>
                    </div>
                    <div class="product-info clearfix">
                        <div class="fix">
                            <p class="floatright hidden-sm">'.$catname.'</p>
                            <h4 class="post-title text-left"><a href="'.$href.'">'.$name.'</a></h4>
                        </div>
                        <div class="fix">
                            <span class="pro-price text-left">£ '.$aprice.' <span>£ '.$bprice.'</span></span>
                            <span class="pro-rating float-right">
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star"></i></a>
                                <a href="#"><i class="fa fa-star-half-o"></i></a>
                                <a href="#"><i class="fa fa-star-half-o"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>';
            $prod_array[$i] = '<div class="col-6 text-center col-md-2 p-0">';
                $prod_array[$i] .= '<div class="ee-product">';
                    if($image == NULL){
                        $prod_array[$i] .= '<div class="image">
                        <a href="/products/'.$id.'" class="img"><img src="/images/no-image.jpg" alt="Product Image"></a>
                        <div class="wishlist-compare">
                            <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
                            <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
                        </div>
                        <a href="#" data-id="'.$id.'" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
                        </div>';
                    }else{
                        $prod_array[$i] .= '<div class="image">
                        <a href="/products/'.$id.'" class="img"><img src="/images/products/'.$image.'" alt="Product Image"></a>
                        <div class="wishlist-compare">
                            <a href="#" data-tooltip="Compare"><i class="ti-control-shuffle"></i></a>
                            <a href="#" data-tooltip="Wishlist"><i class="ti-heart"></i></a>
                        </div>
                        <a data-id="'.$id.'" class="add-to-cart"><i class="ti-shopping-cart"></i><span>ADD TO CART</span></a>
                        </div>';
                    }
                    $prod_array[$i] .= '<div class="content">
                    <div class="category-title">
                        <a href="/category/'.$url.'" class="cat">'.$catname.'</a>
                        <h5 class="title"><a href="/products/'.$id.'">'.$name.'</a></h5>
                    </div>';
                    if($aprice == NULL){
                        $prod_array[$i] .= '<div class="price-ratting">
                        <h5 class="price">BDT '.$bprice.'</h5>
                        <div class="ratting">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                    </div>';
                    }else{
                        $prod_array[$i] .= '<div class="price-ratting">
                        <h5 class="price"><span>BDT '.$bprice.'</span>BDT '.$aprice.'</h5>
                        <div class="ratting">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-half-o"></i>
                            <i class="fa fa-star-o"></i>
                        </div>
                    </div>';
                    }
                    $prod_array[$i] .=  '</div>';
                    $prod_array[$i] .= '</div></div>';
            $i = $i + 1;
        }
        return $prod_array;
    }
}
