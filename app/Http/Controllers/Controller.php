<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Category;
use App\Brands;
use Illuminate\Support\Facades\DB;
use Session;
use App\GeneralSetting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function navCats(){
        $navCats = Category::get();
        $skinCare = Category::where('parent_id',1)->get();
        $skinType = Category::where('parent_id',13)->get();
        $skinConcerns = Category::where('parent_id',18)->get();
        $makeup = Category::where('parent_id',26)->get();
        $hairBody = Category::where('parent_id',31)->get();
        $giftSets = Category::where('id',38)->first();
        View::share(compact('skinCare','skinType','skinConcerns','makeup','hairBody','giftSets'));
        return $navCats;
    }

    public static function navBrands(){
        $navBrands = Brands::query()->orderBy('name')->get();
            $hashToB = [];
            $cToH = [];
            $iToN = [];
            $oToT = [];
            $uToz = [];

            foreach(range('[','`') as $letter){
                foreach($navBrands as $brand){
                   $isMatched =  Str::startsWith(strtolower($brand->name),strtolower($letter));
                    // dump(strtolower($brand->name),strtolower($letter),$isMatched);
                   if($isMatched){
                       array_push($hashToB,$brand);
                   }
                }
            }
            foreach(range('#','B') as $letter){
                foreach($navBrands as $brand){
                   $isMatched =  Str::startsWith(strtolower($brand->name),strtolower($letter));
                    // dump(strtolower($brand->name),strtolower($letter),$isMatched);
                   if($isMatched){
                       array_push($hashToB,$brand);
                   }
                }
            }
            foreach(range('C','H') as $letter){
                foreach($navBrands as $brand){
                   $isMatched =  Str::startsWith(strtolower($brand->name),strtolower($letter));
                    // dump(strtolower($brand->name),strtolower($letter),$isMatched);
                   if($isMatched){
                       array_push($cToH,$brand);
                   }
                }
            }
            foreach(range('I','N') as $letter){
                foreach($navBrands as $brand){
                   $isMatched =  Str::startsWith(strtolower($brand->name),strtolower($letter));
                    // dump(strtolower($brand->name),strtolower($letter),$isMatched);
                   if($isMatched){
                       array_push($iToN,$brand);
                   }
                }
            }
            foreach(range('O','T') as $letter){
                foreach($navBrands as $brand){
                   $isMatched =  Str::startsWith(strtolower($brand->name),strtolower($letter));
                    // dump(strtolower($brand->name),strtolower($letter),$isMatched);
                   if($isMatched){
                       array_push($oToT,$brand);
                   }
                }
            }
            foreach(range('U','Z') as $letter){
                foreach($navBrands as $brand){
                   $isMatched =  Str::startsWith(strtolower($brand->name),strtolower($letter));
                    // dump(strtolower($brand->name),strtolower($letter),$isMatched);
                   if($isMatched){
                       array_push($uToz,$brand);
                   }
                }
            }
                  
        // dd($hashToB,$cToH,range('#','b'));
        View::share(compact(['hashToB','cToH','iToN','oToT','uToz']));
        return $navBrands;
    }
    public static function cartData(){
        $session_id = Session::get('session_id');
        $userCart = DB::table('cart')->where(['session_id'=>$session_id])->get();
        return $userCart;
    }
    public static function generalSettings(){
        $settings = GeneralSetting::first();
        return $settings;
    }
}
