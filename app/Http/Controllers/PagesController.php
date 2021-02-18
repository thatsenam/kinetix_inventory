<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Brands;
use App\Category;
use App\Products;
use App\Warehouse;
use App\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class PagesController extends Controller
{

    public function barcode($id){
        $products = Products::where('id',$id)->get();
        return view('barcode')->with(compact('products'));
    }

    public function printLabels(){
        $products = Products::where('client_id',auth()->user()->client_id)->get();
        return view('admin.print-labels')->with(compact('products'));
    }

    public function index($key = null){
        if($key == 'sort=25'){
            $products = DB::table('products')
                        ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
                        ->join('categories', 'products.cat_id', '=', 'categories.id')
                        ->orderBy('id','DESC')
                        ->paginate(24);
        }elseif($key == 'sort=50'){
            $products = DB::table('products')
                        ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
                        ->join('categories', 'products.cat_id', '=', 'categories.id')
                        ->orderBy('id','DESC')
                        ->paginate(48);
        }elseif($key == 'sort=trending'){$products = DB::table('products')
            ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->orderBy('id','DESC')
            ->paginate(12);
        }elseif($key == 'sort=popular'){
            $products = DB::table('products')
            ->select('products.id','products.product_name as name','products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->inRandomOrder()
            ->paginate(12);
        }elseif($key == 'sort=newest'){$products = DB::table('products')
            ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.created_at','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->orderBy('created_at','DESC')
            ->paginate(12);
        }elseif($key == 'sort=price-asc'){$products = DB::table('products')
            ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->orderBy('before_price','ASC')
            ->paginate(12);
        }elseif($key == 'sort=price-desc'){$products = DB::table('products')
            ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->orderBy('before_price','DESC')
            ->paginate(12);
        }else{
            $products = DB::table('products')
            ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.product_desc','products.product_specs','products.after_pprice','products.stock','categories.name as catname','categories.description as catdesc','categories.url')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->orderBy('id','DESC')
            ->paginate(12);
        }

        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        $latests = DB::table('products')->select('products.id',DB::raw('substr(product_name, 1, 40) as name'),'products.product_img','products.before_price','products.after_pprice')
            ->limit(5)
            ->get();

        return view('shop')->with(compact('categories','products','latests'));
    }

    public function warehouse_manage(){
        return view('admin.pos.warehouse.manage-warehouse');
    }

    public function stock_transfer(Request $request){
        $warehouses = Warehouse::get();
        $products = Products::get();

        if($request->isMethod('post')){
            $fieldValues = json_decode($request['fieldValues'], true);
            $inputDate = $fieldValues['date'];
            $inputRemarks = $fieldValues['remarks'];

            $take_cart_items = json_decode($request['cartData'], true);
            
            $count = count($take_cart_items);
            for($i = 0; $i < $count;){
                $j = $i;
                $j1 = $i+1;
                $j2 = $i+2;
                $j3 = $i+3;

                $getWnameFrom = DB::table('warehouses')->where('id',$take_cart_items[$j1])->first();
                $wnameFrom = $getWnameFrom->name;
                $getWnameTo = DB::table('warehouses')->where('id',$take_cart_items[$j3])->first();
                $wnameTo = $getWnameTo->name;

                Stock::create([
                    'date' => $inputDate,
                    'warehouse_id' => $take_cart_items[$j3],
                    'product_id' => $take_cart_items[$j],
                    'in_qnt' => $take_cart_items[$j2],
                    'out_qnt' => 0,
                    'particulars' => "stock transfer",
                    'remarks' => $inputRemarks." ".$wnameFrom." To ".$wnameTo,
                    'client_id' => auth()->user()->client_id,
                    'user_id' => auth()->user()->id
                ]);

                Stock::create([
                    'date' => $inputDate,
                    'warehouse_id' => $take_cart_items[$j1],
                    'product_id' => $take_cart_items[$j],
                    'in_qnt' => 0,
                    'out_qnt' => $take_cart_items[$j2],
                    'particulars' => "stock transfer",
                    'remarks' => $inputRemarks." ".$wnameFrom." To ".$wnameTo,
                    'client_id' => auth()->user()->client_id,
                    'user_id' => auth()->user()->id
                ]);

                $i = $i + 4;
            }

            return 'Stock Transfer Completed Successfully!';
        }

        return view('admin.pos.warehouse.stock-transfer')->with(compact('warehouses','products'));
    }

    public function stock_transfer_report(){
        return view('admin.pos.warehouse.stock-transfer-report');
    }

    public function stock_transfer_report_date(Request $req){
        $stdate = $req['from_date'];
        $enddate = $req['to_date'];

        if(!$stdate){
            $stdate = date('Y-m-d');
        }
        if(!$enddate){
            $enddate = date('Y-m-d');
        }

        $stocks = DB::table('stocks')->select('stocks.id','stocks.warehouse_id','stocks.product_id','stocks.in_qnt','stocks.out_qnt','stocks.particulars','stocks.remarks','stocks.date','stocks.client_id','products.product_name','warehouses.name')
        ->join('products', 'stocks.product_id', 'products.id')
        ->join('warehouses', 'stocks.warehouse_id', 'warehouses.id')
        ->whereBetween('stocks.date', [$stdate, $enddate])
        ->where('stocks.particulars', "stock transfer")
        ->where('stocks.client_id',auth()->user()->client_id)
        ->get();

        return datatables()->of($stocks)->make(true);
    }

    public function warehouse_report(){
        $stocks = DB::table('stocks')->select('warehouse_id')->groupBy('warehouse_id')->get();
        $trow = array();
        foreach($stocks as $key=>$value){
            $wid = $value->warehouse_id;
            $getWname = Warehouse::where('id',$wid)->first();
            $wname = $getWname->name;
            $getStocks = DB::table('stocks')
            ->select('stocks.product_id as pid','products.product_name')
            ->where('stocks.warehouse_id',$wid)
            ->where('stocks.client_id',auth()->user()->client_id)
            ->join('products','stocks.product_id', 'products.id')
            ->groupBy('products.id')->get();

            $getPriceType = DB::table('general_settings')->select('profit_clc')->first();
            $priceType = $getPriceType->profit_clc;

            $trowDiv = '<h4>'.$wname.'</h4>';
            $trowDiv .= '<div class="mb-4" style="overflow-x:auto;"><table><tr><th>#</th><th>Product</th><th>Price</th><th>Current Stock</th><th>Price</th></tr>';
                $TotalstockValue = 0;
            $i = 1;
            foreach($getStocks as $index=>$row){
                $pid = $row->pid;
                if($priceType = "1"){
                    $row->price = DB::table('purchase_details')->where('pid', $pid)->avg('price');
                    $row->price = round($row->price, 2);
                }else{
                    $getsprice = DB::table('purchase_details')->select('price')->where('pid', $pid)->latest('created_at')->first();
                    $row->price = $getsprice->price;
                    $row->price = round($row->price, 2);
                }
                $instock = DB::table('stocks')->where('warehouse_id',$wid)->where('product_id',$pid)->sum('in_qnt');
                $outstock = DB::table('stocks')->where('warehouse_id',$wid)->where('product_id',$pid)->sum('out_qnt');
                $row->stock = $instock - $outstock;
                $row->stockValue = ($row->price)*($row->stock);

                $TotalstockValue +=$row->stockValue;

                $trowDiv .= '<tr><td>'.$i++.'</td><td>'.$row->product_name.'</td><td>'.$row->price.'</td><td>'.$row->stock.'</td><td>'.$row->stockValue.'</td></tr>';
            }
            $trowDiv .= '<tr><td colspan="4" class="text-right">All Total =</td><td>'.$TotalstockValue.'</td></tr></table></div>';

            $trow[] = $trowDiv;

        }
        return view('admin.pos.warehouse.report')->with(compact('trow'));
    }

    public function products($url = null){

        $countCat = Category::where(['url' => $url])->first();
        if($countCat->count() < 0){
            abort(404);
        }

        $categories = Category::with('categories')->where(['parent_id'=>0])->get();
        $latests = DB::table('products')->select('products.id',DB::raw('substr(product_name, 1, 40) as name'),'products.product_img','products.before_price','products.after_pprice')
        ->limit(5)
        ->get();
        $catDatas = Category::with('categories')->where(['url' => $url])->first();

        $productDatas = Products::where(['cat_id' => $catDatas->id])
        ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.after_pprice','categories.name as catname')
        ->join('categories', 'products.cat_id', '=', 'categories.id')
        ->paginate(12);

        $taglessdesc = strip_tags($catDatas->description);
        $productCount = $productDatas->count();

        return view('category')->with(compact('categories','latests','countCat','taglessdesc','productDatas','productCount'));
    }

    public function brands($url = null){

        $countBrand = Brands::where(['url' => $url])->count();
        if($countBrand == 0){
            abort(404);
        }
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        $latests = DB::table('products')->select('products.id',DB::raw('substr(product_name, 1, 40) as name'),'products.product_img','products.before_price','products.after_pprice')
        ->limit(5)
        ->get();

        $brands = Brands::where(['url' => $url])->first();

        $brandDatas = Products::where(['brand_id' => $brands->id])
        ->select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.after_pprice','brands.name as bname', 'brands.image as bimage', 'brands.description as description')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->paginate(12);

        $taglessdesc = strip_tags($brands->description);
        $prodCount = $brandDatas->count();

        return view('brands')->with(compact('categories','latests','brandDatas','brands','taglessdesc','prodCount'));
    }

    public function general_settings(){

        $settings = GeneralSetting::where('client_id',auth()->user()->client_id)->first();
        return view('admin.settings')->with('settings', $settings);

    }

    public function general_settings_save(Request $req){

        $siteName    = $req['siteName'];
        $siteTagline = $req['siteTagline'];
        $phone       = $req['phone'];
        $siteAddress = $req['siteAddress'];
        $email       = $req['email'];
        $vat         = $req['vat'];
        $scharge     = $req['scharge'];
        $print_opt    = $req['print_opt'];
        $profit_clc  = $req['profit_clc'];

        $product_stock  = $req['product_stock'];
        $purchase_price  = $req['purchase_price'];
        $prevent_sale  = $req['prevent_sale'];

        $general_settings = GeneralSetting::firstWhere('client_id',auth()->user()->client_id);

        if ($general_settings ==null){
            $general_settings = new GeneralSetting();
        }

        $general_settings->site_name        = $siteName;
        $general_settings->site_tagline     = $siteTagline;
        $general_settings->site_address     = $siteAddress;
        $general_settings->phone            = $phone;
        $general_settings->email            = $email;
        $general_settings->vat              = $vat;
        $general_settings->scharge          = $scharge;
        $general_settings->print_opt        = $print_opt;
        $general_settings->profit_clc       = $profit_clc;
        $general_settings->product_stock    = $product_stock;
        $general_settings->purchase_price   = $purchase_price;
        $general_settings->prevent_sale     = $prevent_sale;

        if($req->hasFile('favicon')){
            $prev_img = $general_settings->favicon;
            $image_path = 'images/theme/'.$prev_img;
            if(file_exists($image_path)) {
                @unlink($image_path);
            }
            $file = $req->file('favicon');
            $basename = basename($file);
            $img_name = $basename.time().'.'.$file->getClientOriginalExtension();
            $file->move('images/theme/', $img_name);
            $general_settings->favicon = $img_name;
        }

        if($req->hasFile('logoSmall')){
            $prev_img = $general_settings->logo_small;
            $image_path = 'images/theme/'.$prev_img;
            if(file_exists($image_path)) {
                @unlink($image_path);
            }
            $file1 = $req->file('logoSmall');
            $basename1 = basename($file1);
            $img_name1 = $basename1.time().'.'.$file1->getClientOriginalExtension();
            $file1->move('images/theme/', $img_name1);
            $general_settings->logo_small = $img_name1;
        }
        if($req->hasFile('logoBig')){
            $prev_img = $general_settings->logo_big;
            $image_path = 'images/theme/'.$prev_img;
            if(file_exists($image_path)) {
                @unlink($image_path);
            }
            $file2 = $req->file('logoBig');
            $basename2 = basename($file2);
            $img_name2 = $basename2.time().'.'.$file2->getClientOriginalExtension();
            $file2->move('images/theme/', $img_name2);
            $general_settings->logo_big = $img_name2;
        }

        $general_settings->save();

        return back();
    }

    /////////////Search Products ////////////////

    public function get_products_simple(Request $request){

        $key = $request['s_text'];

        $src = substr (Request::root(), 7);
        $products = Products::select('id', 'image', 'name', 'slug', 'price', 'quantity')->where('status', 1)->where('name', 'like', '%'.$key.'%')->limit(9)->get(); ?>
        <ul class='product-list sugg-list'>
        <?php $i = 1;
        foreach($products as $prod){
            $pid = $prod['id'];
            $pname = $prod['name'];
            $slug = $prod['slug'];
            $image = $prod['image'];
            $src = "/images/products/".$image; ?>
            <li onclick = "selectProduct('<?php echo $pid; ?>', '<?php echo $pname; ?>', '<?php echo $slug; ?>')" tabindex = '<?php echo $i; ?>'  data-pid = '<?php echo $pid; ?>' data-pname = '<?php echo $pname;?>' data-slug = '<?php echo $slug; ?>'  style='font-size:13px; padding:5px; text-align: left;'><img src='<?php echo $src; ?>' style='width: 50px; height: 50px;'> &nbsp; &nbsp;<?php echo $pname; ?></li>
            <?php $i = $i + 1;
        } ?>
        </ul>
    <?php }

    public function send_product(Request $request){

        $s_text = $request['s_text'];
        $category = Category::where('name','like','%'.$s_text.'%')->limit(3)->get();
        $list = "<table class='search_table' style='width:100%'>";
        $list .= "<tr style='text-align: center; color:#0a0a0a; width:100%;'><td><b>Categories</b></td></tr>";

        $i = 1;

        foreach($category as $cat){
            $cat_name = $cat['name'];
            $cat_id = $cat['id'];
            $cat_slug = $cat['url'];

            $list .= "<tr><td tabindex = '$i'  data-pname = '$cat_name' style='font-size:13px; padding-left:10px;'>
            <a href = '/category/".$cat_slug."'>$cat_name</a></td></tr>";
        }
        $products = Products::where('product_name','like','%'.$s_text.'%')->limit(5)->get();

        $all_products = Products::where('product_name','like','%'.$s_text.'%')->get()->count();

        $list .= "</table>";

        $list .= "<table class='search_table' style='width:100%'>";

        $list .= "<tr><td colspan='3' style='text-align: center; color:#0a0a0a; width:100%;'><b>Product</b></td></tr>";

        foreach($products as $prod){
            $product_id = $prod['id'];
            $product_name = $prod['product_name'];
            $product_slug = $prod['slug'];
            $product_image = $prod['image'];
            $product_price = $prod['after_pprice'];
            if($product_price == ''){
                $product_price = $prod['before_price'];
            }

            $list .= "<tr class='product_tr'><td  data-slug = '$product_id' style='font-size:13px;'><img src='/images/products/".$product_image."' style='width:50px;'>
            </td><td><a href='/products/".$product_id."'>$product_name</a></td><td class='search_price'><span class = 'search_sticker' style=''>&#2547;$product_price</td></tr>";
        }

        $list .= "<tr><td colspan='3' style='text-align:center'><a href='/searchproducts/".$s_text."'>See all <span class = 'search_sticker'>".$all_products."</span> Products</a></td></tr>";

        $list .= "</table>";

        return $list;
    }

    public function searchResults($key){
        $q = $key;
        if($key != ""){
            $products = Products::select('products.id',DB::raw('substr(product_name, 1, 45) as name'),'products.product_img','products.before_price','products.after_pprice','categories.name as catname','categories.description as catdesc')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->where('products.product_name', 'LIKE', '%' . $key . '%' )->orWhere ( 'products.product_code', 'LIKE', '%' . $key . '%' )->paginate (12)->setPath ( '' );
            $pagination = $products->appends ( array (
                'q' => $key
            ) );
            $count = $products->count();
            if (count ( $products ) > 0)
            return view ( 'search-results' )->with(compact('products','q','count'));
        }
        return view ( 'search-results' )->with('flash_message_success', 'No product found. Try searching again!');
    }

}
