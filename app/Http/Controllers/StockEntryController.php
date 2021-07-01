<?php

namespace App\Http\Controllers;

use App\Category;
use App\Products;
use App\PurchaseDetails;
use App\PurchasePrimary;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    public function entry(Request $request)
    {
        $categories = Category::query()->get();
        return view('admin.stock.stock-entry', compact('categories'));

    }

    public function products(Request $request, $category)
    {
        $products = Products::query()->where('cat_id', $category)
            ->get()
            ->map(function ($product) {
                $pd = PurchaseDetails::query()->where('pur_inv', 'OpeningStock')->where('pid', $product->id)->first();
                if ($pd) {
                    $product->qnt = $pd->qnt;
                    $product->price = $pd->price;
                }
                return $product;
            });
//        dd($products);
        return view('admin.stock.product-list', compact('products'));
    }

    public function saveStockEntry(Request $request)
    {


        $product_ids = $request->product_id;
        $product_qnt = $request->qnt;
        $prices = $request->price;
        $grand = 0;
        foreach ($product_ids as $index => $id) {
            $price = $prices[$index];
            $qnt = $product_qnt[$index];
            $totalPrice = $qnt * $price;
            $grand += $totalPrice;
            PurchaseDetails::query()->updateOrCreate(['pur_inv' => 'OpeningStock', 'pid' => $id], ['qnt' => $qnt, 'price' => $price, 'total' => $totalPrice]);
        }
        PurchasePrimary::query()->updateOrCreate(['pur_inv' => 'OpeningStock', 'supp_inv' => 'OpeningStock'],
            ['pur_inv' => 'OpeningStock', 'supp_inv' => 'OpeningStock', 'date' => today()->toDateString(), 'sid' => -1, 'amount' => $grand, 'total' => $grand]);

        return back()->with('success', 'Data Saved Successfully');
    }
}
