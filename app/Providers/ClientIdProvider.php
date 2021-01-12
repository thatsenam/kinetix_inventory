<?php

namespace App\Providers;

use App\AccHead;
use App\AccTransaction;
use App\BankAcc;
use App\BankInfo;
use App\BankTransaction;
use App\BankTransfer;
use App\Banner;
use App\Brands;
use App\Cart;
use App\Category;
use App\Coupon;
use App\Customer;
use App\GeneralSetting;
use App\Order;
use App\Order_detail;
use App\PaymentInvoice;
use App\ProductAttributes;
use App\ProductImages;
use App\Products;
use App\ProductsImages;
use App\PurchaseDetails;
use App\PurchasePrimary;
use App\PurchaseReturns;
use App\Sales;
use App\SalesInvoice;
use App\SalesInvoiceDetails;
use App\SalesReturn;
use App\Settings;
use App\Supplier;
use App\UserProfiles;
use Illuminate\Support\ServiceProvider;

class ClientIdProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Category::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;
        });

        Products::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });
        Brands::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });
        ProductAttributes::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        ProductImages::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        AccHead::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        AccTransaction::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        BankAcc::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        BankInfo::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        BankTransaction::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        BankTransfer::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Banner::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Brands::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Cart::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Category::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Coupon::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Customer::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        GeneralSetting::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Order::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Order_detail::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        PaymentInvoice::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        ProductAttributes::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        ProductImages::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Products::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        ProductsImages::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        PurchaseDetails::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        PurchasePrimary::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        PurchaseReturns::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Sales::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        SalesInvoice::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        SalesInvoiceDetails::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        SalesReturn::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Settings::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        Supplier::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

        UserProfiles::creating(function ($model) {
            $model->client_id = auth()->user()->client_id;
            $model->user_id = auth()->user()->id;

        });

    }
}
