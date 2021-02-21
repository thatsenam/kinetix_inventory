<?php

namespace App\Providers;

use App\AccHead;
use App\GeneralSetting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
////        $GenSettings = GeneralSetting::firstWhere('client_id',auth()->user()->client_id)->get() ;
//        $GenSettings = GeneralSetting::where('client_id',auth()->user()->client_id??-1)->first() ;
//        $GenSettings = GeneralSetting::first();

        view()->composer('*', function ($view)
        {
            $GenSettings = GeneralSetting::where('client_id',auth()->user()->client_id??-1)->first();
            $AccHeads=AccHead::where('client_id',auth()->user()->client_id??-1)->count();

            //...with this variable
            $view->with('GenSettings',$GenSettings)->with('AccHeads',$AccHeads);
        });
//        dd(auth()->user());
//        view()->share('GenSettings',$GenSettings);
    }
}
