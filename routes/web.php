<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\PosSalesController;
use App\Http\Controllers\PosSupplierController;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index')->name('home');
Route::get('/loadmore', 'IndexController@products_loadmore')->name('loadmore');
Route::get('/get_product', 'PagesController@get_product')->name('get_product');
Route::post('/get_product', 'PagesController@send_product')->name('send_product');
Route::get('/test_loadmore', 'IndexController@test_loadmore')->name('test_loadmore');
Route::post('/products/searchProducts', 'ProductsController@searchProducts')->name('products.searchProducts');

Route::get('/products/{id}', 'ProductsController@singleProduct')->name('singleProduct');
Route::get('/shop/{key}', 'PagesController@index')->name('shop');
Route::get('/category/{url}', 'PagesController@products')->name('category');
Route::get('/brands/{url}', 'PagesController@brands')->name('brands');
Route::get('/get-product-price', 'ProductsController@productPrice')->name('get-product-price');
Route::match(['get', 'post'], '/add-cart', 'ProductsController@addtoCart')->name('add-cart');
Route::match(['get', 'post'], '/ajaxCart', 'ProductsController@ajaxCart')->name('ajax-cart');
Route::match(['get', 'post'], '/ajax2Cart', 'ProductsController@ajaxAdd2Cart')->name('ajax-cart2');
Route::match(['get', 'post'], '/cart', 'ProductsController@cart')->name('cart');
Route::get('/cart/delete-product/{id}', 'ProductsController@deleteCartProduct')->name('deleteCartProduct');
Route::get('/cart/update-cart', 'ProductsController@updateCartProduct')->name('updateCartProduct');
Route::post('/cart/apply-coupon', 'ProductsController@applyCoupon')->name('applyCoupon');

//User Login and Register
Route::get('/login_register', 'UsersController@LoginRegister')->name('LoginRegister');
Route::post('/user_register', 'UsersController@register')->name('user_register');
Route::post('/user_logoin', 'UsersController@logoin')->name('user_logoin');
Route::get('/user_logout', 'UsersController@logout')->name('user_logout');

Route::any('/search', function (Request $request) {
    $q = $request->input('q');
    if ($q != "") {
        $products = Products::select('products.id', DB::raw('substr(product_name, 1, 45) as name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname', 'categories.description as catdesc')
            ->join('categories', 'products.cat_id', '=', 'categories.id')
            ->where('products.product_name', 'LIKE', '%' . $q . '%')->orWhere('products.product_code', 'LIKE', '%' . $q . '%')->paginate(12)->setPath('');
        $pagination = $products->appends(array(
            'q' => $request->input('q')
        ));
        $count = $products->count();
        if (count($products) > 0)
            return view('search')->with(compact('products', 'q', 'count'));
    }
    $products = Products::select('products.id', DB::raw('substr(product_name, 1, 45) as name'), 'products.product_img', 'products.before_price', 'products.after_pprice', 'categories.name as catname', 'categories.description as catdesc')
        ->join('categories', 'products.cat_id', '=', 'categories.id')
        ->where('products.product_name', 'LIKE', '%' . $q . '%')->orWhere('products.product_code', 'LIKE', '%' . $q . '%')->paginate(12)->setPath('');
    $pagination = $products->appends(array(
        'q' => $request->input('q')
    ));

    return view('search')->with('flash_message_success', 'No product found. Try to search again!')->with('q', $q)->with('products', $products);
});

Route::match(['get', 'post'], '/searchproducts/{key}', 'PagesController@searchResults')->name('search-results');

Route::group(['middleware' => ['userlogin']], function () {
    //User Account Page
    Route::get('/myaccount', 'UsersController@myaccount')->name('useraccount');
    Route::get('/invoice/{id}', 'UsersController@invoice')->name('Uinvoice');
    Route::match(['get', 'post'], '/users/cancel_order/{id}', 'UsersController@updateOrder');
    Route::post('/users/myaccount', 'UsersController@updateBilling')->name('updateBill');
    Route::post('/myaccount', 'UsersController@updateShipping')->name('updateShipp');
});

Auth::routes();

// Checkout Page Routes
Route::get('/checkout', 'CheckoutController@index')->name('checkout');
Route::match(['get', 'post'], '/placeorder', 'CheckoutController@create')->name('checkout.create');
Route::post('/get_details', 'CheckoutController@get_details')->name('get_details');
Route::get('/invoice', 'CheckoutController@invoice')->name('invoice');

//Paypal Integrations
Route::get('/test', 'CheckoutController@paypal')->name('paypal');
Route::get('/execute-payment', 'PaymentController@execute')->name('execute');
Route::post('/create-payment', 'PaymentController@create')->name('create-payment');

Route::group(['middleware' => ['auth']], function () {
    //Barcode Generation
    Route::get('/barcode/{id}', 'PagesController@barcode')->name('barcode');
    Route::get('/admin/print-labels', 'PagesController@printLabels')->name('labels.print');

    Route::get('/admin/settings', 'PagesController@general_settings')->name('general_settings');
    Route::post('/dashboard/general_settings/', 'PagesController@general_settings_save')->name('general_settings_save');

    //Categories Routes (Admin Panel)
    Route::match(['get', 'post'], '/admin/create_category', 'CategoryController@CreateCategory');
    Route::match(['get', 'post'], '/admin/edit_category/{id}', 'CategoryController@editCategory');
    Route::match(['get', 'post'], '/admin/delete_category/{id}', 'CategoryController@deleteCategory');
    Route::get('/admin/view_categories', 'CategoryController@viewCategories');
    Route::get('/dashboard', 'HomeController@index')->name('Dashboard');

    //Brands Routes (Admin Panel)
    Route::match(['get', 'post'], '/admin/create_brand', 'BrandsController@CreateBrand');
    Route::match(['get', 'post'], '/admin/edit_brand/{id}', 'BrandsController@editBrand');
    Route::match(['get', 'post'], '/admin/delete_brand/{id}', 'BrandsController@deleteBrand');
    Route::get('/admin/view_brands', 'BrandsController@viewBrands');

    //Products Routes (Admin Panel)
    Route::match(['get', 'post'], '/admin/add_product', 'ProductsController@addProduct');
    Route::match(['get', 'post'], '/admin/edit_product/{id}', 'ProductsController@editProduct');
    Route::match(['get', 'post'], '/admin/delete_product/{id}', 'ProductsController@deleteProduct');
    Route::get('/admin/view_products', 'ProductsController@ViewProducts');

    //Product Arribute (Admin Panel)
    Route::match(['get', 'post'], '/admin/create_attribute/{id}', 'ProductsController@createAttribute');
    Route::match(['get', 'post'], '/admin/delete_attribute/{id}', 'ProductsController@deleteAttribute');

    //Coupons Routes (Admin Panel)
    Route::match(['get', 'post'], '/admin/create_coupon', 'CouponsController@createCoupon');
    Route::match(['get', 'post'], '/admin/edit_coupon/{id}', 'CouponsController@editCoupon');
    Route::get('/admin/view_coupons', 'CouponsController@ViewCoupons');
    Route::match(['get', 'post'], '/admin/delete_coupon/{id}', 'CouponsController@deleteCoupon');

    //Order Management
    Route::match(['get', 'post'], 'orders/get_pending', 'OrderController@pendings')->name('orders.pending');
    Route::match(['get', 'post'], 'orders/get_confirmed', 'OrderController@getConfirmed')->name('admin.orders.confirmed');
    Route::match(['get', 'post'], 'orders/get_shipped', 'OrderController@getShipped')->name('admin.orders.shipped');
    Route::match(['get', 'post'], 'orders/get_delivered', 'OrderController@getDelivered')->name('admin.orders.delivered');
    Route::match(['get', 'post'], 'orders/get_canceled', 'OrderController@getCanceled')->name('admin.orders.canceled');
    Route::resource('orders', 'OrderController');

    Route::get('/admin/get_invoice/{id}', 'OrderController@getInvoice')->name('admin.orders.invoice');

    //Change Status
    Route::post('/admin/save_pending', 'OrderController@save_pending')->name('admin.orders.save_pending');
    Route::post('/admin/save_confirmed', 'OrderController@save_confirmed')->name('admin.orders.save_confirmed');
    Route::post('/admin/confirm-delivered', 'OrderController@ConfirmAsDelivered')->name('admin.orders.confirm-delivered');
    Route::post('/admin/confirm-cancel', 'OrderController@ConfirmAsCancel')->name('admin.orders.confirm-cancel');
    Route::post('/admin/confirm-paid', 'OrderController@ConfirmAsPaid')->name('admin.orders.confirm-paid');
    Route::post('/admin/shipped-delivered', 'OrderController@ShippedAsDelievred')->name('admin.orders.shipped-delivered');
    Route::post('/admin/shipped-paid', 'OrderController@ShippedAsPaid')->name('admin.orders.shipped-paid');
    Route::post('/admin/shipped-cancel', 'OrderController@ShippedAsCancel')->name('admin.orders.shipped-cancel');
    Route::post('/admin/delivered-paid', 'OrderController@DeliveredAsPaid')->name('admin.orders.delivered-paid');
    Route::post('/admin/canceled-confirm', 'OrderController@saveCanceled')->name('admin.orders.canceled-confirm');

    Route::post('/admin/cancel_pending', 'OrderController@cancel_pending')->name('admin.orders.cancel_pending');
    Route::post('/admin/saveas_paid', 'OrderController@saveas_paid')->name('admin.orders.saveas_paid');

    //Bank Loan Routes
    Route::match(['get', 'post'], '/admin/bank_loan_create', 'PosBankController@create_loan')->name('admin.pos.bank-loans.create');
    Route::match(['get', 'post'], '/admin/create_installment', 'PosBankController@create_installment')->name('admin.pos.bank-loans.create_installment');
    Route::match(['get', 'post'], '/admin/get_installment_amount', 'PosBankController@get_installment_amount')->name('get_installment_amount');
    Route::match(['get', 'post'], '/admin/bank_loan_report', 'PosBankController@bank_loan_report')->name('admin.pos.bank_loans.report');
    Route::match(['get', 'post'], '/admin/bank_loan_report_date', 'PosBankController@bank_loan_report_date')->name('bank_loan_report_date');
    Route::match(['get', 'post'], '/admin/installment_report', 'PosBankController@installment_report')->name('admin.pos.bank_loans.installment_report');
    Route::match(['get', 'post'], '/admin/installment_report_date', 'PosBankController@installment_report_date')->name('installment_report_date');

    //Admin Banners
    Route::match(['get', 'post'], '/admin/add_banner', 'BannersController@addBanner')->name('admin.add_banner');
    Route::match(['get', 'post'], '/admin/view_banners', 'BannersController@viewBanners')->name('admin.view_banners');
    Route::match(['get', 'post'], '/admin/edit_banner/{id}', 'BannersController@editBanner');
    Route::match(['get', 'post'], '/admin/delete_banner/{id}', 'BannersController@deleteBanner');

    //POS Customer Options
    Route::match(['get', 'post'], '/dashboard/customers', 'PosCustomerController@setCustomer')->name('set_customer');
    Route::match(['get', 'post'], '/dashboard/customers_phone', 'PosCustomerController@customers_phone')->name('customers_phone');
    Route::match(['get', 'post'], '/dashboard/supplier_phone', 'PosCustomerController@supplier_phone')->name('supplier_phone');
    Route::match(['get', 'post'], '/dashboard/update_cust', 'PosCustomerController@edit')->name('edit_customer');
    Route::match(['get', 'post'], '/dashboard/get_cust_details', 'PosCustomerController@custDetals')->name('get_cust_details');

    Route::match(['get', 'post'], '/dashboard/customerUp', 'PosCustomerController@updateCust')->name('updateCust');
    Route::match(['get', 'post'], '/dashboard/delete_cust/{id}', 'PosCustomerController@deleteCust');
    Route::match(['get', 'post'], '/dashboard/up_cust/{id}', 'PosCustomerController@UpCust');

    Route::match(['get', 'post'], '/dashboard/customer/{id}', 'PosCustomerController@getCustomer');
    Route::get('/customer/details/prev', [PosSupplierController::class, 'getPreviousBalance_customer'])->name('customer.details.previousBalance');

    Route::match(['get', 'post'], '/customer/details', 'PosCustomerController@filter_data')->name('customer.details');
    Route::resource('customer', 'PosCustomerController');

    Route::get('/dashboard/search_bank', 'PosCustomerController@search');
    Route::get('/dashboard/search_bank_acc', 'PosCustomerController@search_bank_acc');

    Route::post('/dashboard/add_payment', 'PosCustomerController@addPayment')->name('save_payment');

    Route::match(['get', 'post'], '/payment/invoice/{id}', 'PosCustomerController@payinvoice');
    Route::match(['get', 'post'], '/dashboard/sales_invoice/{id}', 'PosCustomerController@saleinvoice');
    Route::match(['get', 'post'], '/dashboard/sales_invoicemain/{id}', 'PosCustomerController@saleinvoicemain');

    Route::get('/dashboard/customers/due-report', 'PosCustomerController@customers_due_report');
    // Route::get('/dashboard/customers/due-collection','PosCustomerController@customers_due_collection');
    // Route::post('/dashboard/customers/store-due-collection','PosCustomerController@store_customers_due_collection');
    Route::get('/get_customer_due/{customer}', 'PosCustomerController@get_customer_due');
    Route::get('/dashboard/customers/due-collection-report', 'PosCustomerController@customers_due_collection_report');
    Route::match(['get', 'post'], '/dashboard/customers/get-due-collection-report', 'PosCustomerController@get_customers_due_collection_report')->name('get_customers_due_collection_report');
    Route::get('/dashboard/customers/customer_ledger', 'PosCustomerController@customer_ledger')->name('customer_ledger')->middleware('auth');
    Route::post('/dashboard/customers/get_customer_ledger', 'PosCustomerController@get_customer_ledger')->name('get_customer_ledger')->middleware('auth');

    //POS Suppliers Options
    Route::match(['get', 'post'], '/dashboard/supplier_group', 'PosSupplierController@setSupplierGroup')->name('set_supplier_group');
    Route::match(['get', 'post'], '/dashboard/update_supp_group', 'PosSupplierController@edit_group')->name('edit_supplier_group');
    Route::match(['get', 'post'], '/dashboard/supplierGroupUp', 'PosSupplierController@updateSuppGroup')->name('updateSuppGroup');
    Route::match(['get', 'post'], '/dashboard/delete_supp_group/{id}', 'PosSupplierController@deleteSuppGroup');
    Route::match(['get', 'post'], '/dashboard/suppliers', 'PosSupplierController@setSupplier')->name('set_supplier');
    Route::match(['get', 'post'], '/dashboard/update_supp', 'PosSupplierController@edit')->name('edit_supplier');
    Route::match(['get', 'post'], '/dashboard/supplierUp', 'PosSupplierController@updateSupp')->name('updateSupp');
    Route::match(['get', 'post'], '/dashboard/delete_supp/{id}', 'PosSupplierController@deleteSupp');
    Route::match(['get', 'post'], '/dashboard/change_status/{id}', 'PosSupplierController@changeStatus');
    Route::match(['get', 'post'], '/dashboard/get_supp_details', 'PosSupplierController@suppDetails')->name('get_cust_details');
    Route::match(['get', 'post'], '/dashboard/addS_payment', 'PosSupplierController@addPayment')->name('supp_payment');
    Route::match(['get', 'post'], '/dashboard/supplier/{id}', 'PosSupplierController@getSupplier');
    Route::get('/supplier/details/prev', [PosSupplierController::class, 'getPreviousBalance'])->name('supplier.details.previousBalance');


    Route::match(['get', 'post'], '/supplier/details', 'PosSupplierController@filter_data')->name('supplier.details');
    Route::get('/supplier/details/prev', [PosSupplierController::class, 'getPreviousBalance'])->name('supplier.details.previousBalance');
    Route::get('/bank/details/prev', [PosSupplierController::class, 'getPreviousBalanceBank'])->name('bank.details.previousBalance');
    Route::match(['get', 'post'], '/dashboard/purchase_invoice/{id}', 'PosSupplierController@purchaseinvoice');

    //POS General Ledger Report
    Route::match(['get', 'post'], '/dashboard/genaral_ledger', 'PosReportController@generalLedger')->name('ledgers');
    Route::match(['get', 'post'], '/general_ledger', 'PosReportController@filter_data')->name('general.ledger');
    Route::match(['get', 'post'], '/dashboard/trial_balance', 'PosReportController@trials')->name('trials');
    Route::match(['get', 'post'], '/trial_balance', 'PosReportController@trialBalance')->name('trial.balance');
    Route::match(['get', 'post'], '/prev_balance', 'PosReportController@prevBalance')->name('prev.balance');
    Route::match(['get', 'post'], '/dashboard/cash_flow', 'PosReportController@CashFlow')->name('cash.flow');
    Route::match(['get', 'post'], '/dashboard/sales-customer', 'PosReportController@SalesByCustomer')->name('salesby.customer');
    Route::match(['get', 'post'], '/dashboard/sales-product', 'PosReportController@SalesByProduct')->name('salesby.product');
    Route::match(['get', 'post'], '/dashboard/sales-category', 'PosReportController@SalesByCategory')->name('salesby.category');
    Route::match(['get', 'post'], '/dashboard/purchase-product', 'PosReportController@PurchaseByProduct')->name('purchaseby.product');
    Route::match(['get', 'post'], '/dashboard/purchase-supplier', 'PosReportController@PurchaseBySupplier')->name('purchaseby.supplier');

    // Loss/Profit Report
    Route::match(['get', 'post'], '/dashboard/reports/loss-profit', 'PosReportController@lossProfit')->name('reports.loss-profit');
    Route::match(['get', 'post'], '/dashboard/reports/stock-report', 'PosReportController@stockReport')->name('reports.stock-report');

    Route::match(['get', 'post'], 'reports/get_stocks', 'PosReportController@dateStocks')->name('reports.stocks');
    Route::resource('reports', 'PosReportController');

    Route::view('/dashboard/reports/loss-profit-report', 'admin.pos.reports.loss-profit-index');

    //Charts
    Route::get('/get-post-chart-data', 'ChartDataController@getMonthlyPostData')->name('orders.chart');

    Route::match(['get', 'post'], 'admin/voucher-entry', 'PosController@voucherEntry')->name('voucher.entry');

    Route::get('/admin/add-head', function () {
        return view('admin.pos.add-head');
    });

    Route::match(['get', 'post'], 'admin/voucher-entry', 'PosController@voucherEntry')->name('voucher.entry');

    Route::post('/admin/accounting/voucher', 'PosController@savevoucher')->name('admin.accounting.pages.savevoucher');
    Route::post('/admin/accounting/savehead', 'PosController@savehead')->name('admin.accounting.pages.savehead');

    //User Role Management
    Route::match(['get', 'post'], '/admin/users', 'UsersController@users')->name('all.users');
    Route::get('/dashboard/edit_user/{user}', 'UsersController@editUser')->name('users.edit');
    Route::post('/dashboard/users/update', 'UsersController@updateUser')->name('users.update');

    Route::post('/dashboard/update_user_info', 'UsersController@update_user_info')->name('update_user_info');

});


//////////////POS Section/////////////////////////

Route::get('/dashboard/pos', 'PosController@index')->name('pos_index')->middleware('auth');

Route::get('/dashboard/purchase_products', 'PosPurchaseController@purchase_products')->name('purchase_products')->middleware('auth');

Route::post('/dashboard/get_purchase_products', 'PosPurchaseController@get_purchase_products')->name('get_purchase_products')->middleware('auth');
Route::post('/dashboard/get_purchase_return_products', 'PosPurchaseController@get_purchase_return_products')->name('get_purchase_return_products')->middleware('auth');

Route::post('/dashboard/save_purchase_products', 'PosPurchaseController@save_purchase_products')->name('save_purchase_products')->middleware('auth');

Route::post('/dashboard/get_supplier', 'PosPurchaseController@get_supplier')->name('get_supplier')->middleware('auth');

Route::post('/dashboard/get_suppmemo', 'PosPurchaseController@get_suppmemo')->name('get_suppmemo')->middleware('auth');
Route::post('/dashboard/get_suppmemo_list', 'PosPurchaseController@get_suppmemo_list')->name('get_suppmemo_list')->middleware('auth');

Route::get('/dashboard/purchase_return', 'PosPurchaseController@purchase_return')->name('purchase_return')->middleware('auth');

Route::post('/dashboard/save_purchase_return', 'PosPurchaseController@save_purchase_return')->name('save_purchase_return')->middleware('auth');

Route::get('/dashboard/purchase_report_date', 'PosPurchaseController@purchase_report_date')->name('purchase_report_date')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_purchase_report_date', 'PosPurchaseController@get_purchase_report_date')->name('get_purchase_report_date')->middleware('auth');

Route::post('/dashboard/delete_purchase', 'PosPurchaseController@delete_purchase')->name('delete_purchase')->middleware('auth');

Route::get('/dashboard/purchase_return_report_date', 'PosPurchaseController@purchase_return_report_date')->name('purchase_return_report_date')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_purchase_return_report_date', 'PosPurchaseController@get_purchase_return_report_date')->name('get_purchase_return_report_date')->middleware('auth');

Route::post('/dashboard/delete_purchase_return', 'PosPurchaseController@delete_purchase_return')->name('delete_purchase_return')->middleware('auth');

Route::post('/dashboard/get_purchase_invoice_details', 'PosPurchaseController@get_purchase_invoice_details')->name('get_purchase_invoice_details')->middleware('auth');

Route::get('/dashboard/purchase_report_brand', 'PosPurchaseController@purchase_report_brand')->name('purchase_report_brand')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_purchase_report_brand', 'PosPurchaseController@get_purchase_report_brand')->name('get_purchase_report_brand')->middleware('auth');


Route::get('/dashboard/sales_invoice', 'PosSalesController@sales_invoice')->name('sales_invoice')->middleware('auth');

Route::get('/dashboard/sales_invoice_save', 'PosSalesController@sales_invoice_save')->name('sales_invoice_save')->middleware('auth');

Route::post('/dashboard/sales_invoice_save', 'PosSalesController@sales_invoice_save')->name('sales_invoice_save')->middleware('auth');

Route::post('/dashboard/get_customer', 'PosCustomerController@get_customer')->name('get_customer')->middleware('auth');

Route::post('/dashboard/get_products', 'PosSalesController@get_products')->name('get_products')->middleware('auth');
Route::post('/dashboard/get_return_products', 'PosSalesController@get_return_products')->name('get_return_products')->middleware('auth');

Route::post('/dashboard/get_barcode', 'PosSalesController@get_barcode')->name('get_barcode')->middleware('auth');

Route::post('/dashboard/get_bank', 'PosBankController@get_bank')->name('get_bank')->middleware('auth');

Route::post('/dashboard/delete_bank_transfer', 'PosBankController@delete_bank_transfer')->name('delete_bank_transfer')->middleware('auth');

Route::post('/dashboard/get_bank_account', 'PosBankController@get_bank_account')->name('get_bank_account')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/sales_report_date', 'PosSalesController@sales_report_date')->name('sales_report_date')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_sales_report_date', 'PosSalesController@get_sales_report_date')->name('get_sales_report_date')->middleware('auth');

Route::post('/dashboard/delete_sales_invoice', 'PosSalesController@delete_sales_invoice')->name('delete_sales_invoice')->middleware('auth');

Route::get('/dashboard/sales_return', 'PosSalesController@sales_return')->name('sales_return')->middleware('auth');

Route::post('/dashboard/sales_return_save', 'PosSalesController@sales_return_save')->name('sales_return_save')->middleware('auth');

Route::post('/dashboard/get_invoice', 'PosSalesController@get_invoice')->name('get_invoice')->middleware('auth');

Route::get('/dashboard/get_invoice/{invoice}', 'PosSalesController@get_invoice_products')->name('get_invoice_products')->middleware('auth');
Route::get('/dashboard/get_purchase_invoice/{invoice}', 'PosSalesController@get_purchase_invoice_products')->name('get_purchase_invoice_products')->middleware('auth');

Route::post('/dashboard/get_invoice_details', 'PosSalesController@get_invoice_details')->name('get_invoice_details')->middleware('auth');

Route::get('/dashboard/sales_return_report_date', 'PosSalesController@sales_return_report_date')->name('sales_return_report_date')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_sales_return_report_date', 'PosSalesController@get_sales_return_report_date')->name('get_sales_return_report_date')->middleware('auth');

Route::post('/dashboard/delete_sales_return', 'PosSalesController@delete_sales_return')->name('delete_sales_return')->middleware('auth');

Route::get('/get_serial/{product}', 'PosSalesController@get_serial');
Route::get('/all/get_serial/{product}', 'PosSalesController@get_serial_all');
Route::get('/get_serial_sold/{product}', 'PosSalesController@get_serial_sold');
Route::get('/get_serial_purchased/{product}', 'PosPurchaseController@get_serial_purchased');

Route::get('/dashboard/damage_products', 'PosPurchaseController@damage_products')->name('damage_products')->middleware('auth');

Route::post('/dashboard/save_damage_products', 'PosPurchaseController@save_damage_products')->name('save_damage_products')->middleware('auth');

Route::post('/dashboard/delete_damage_product', 'PosPurchaseController@delete_damage_product')->name('delete_damage_product')->middleware('auth');


Route::match(['get', 'post'], '/dashboard/sales_report_brand', 'PosSalesController@sales_report_brand')->name('sales_report_brand')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_sales_report_brand', 'PosSalesController@get_sales_report_brand')->name('get_sales_report_brand')->middleware('auth');

Route::get('/dashboard/damage_report_date', 'PosPurchaseController@damage_report_date')->name('damage_report_date')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_damage_report_date', 'PosPurchaseController@get_damage_report_date')->name('get_damage_report_date')->middleware('auth');

Route::post('/dashboard/delete_damage', 'PosPurchaseController@delete_damage')->name('delete_damage')->middleware('auth');


Route::get('/dashboard/check_clearance', 'PosBankController@check_clearance')->name('check_clearance')->middleware('auth');

Route::post('/dashboard/get_check_clearance', 'PosBankController@get_check_clearance')->name('get_check_clearance')->middleware('auth');

Route::post('/dashboard/save_check_clearance', 'PosBankController@save_check_clearance')->name('save_check_clearance')->middleware('auth');

Route::get('/dashboard/bank_deposit', 'PosBankController@bank_deposit')->name('bank_deposit')->middleware('auth');

Route::post('/dashboard/save_bank_deposit', 'PosBankController@save_bank_deposit')->name('save_bank_deposit')->middleware('auth');

Route::post('/dashboard/get_bank_acc', 'PosBankController@get_bank_acc')->name('get_bank_acc')->middleware('auth');

Route::post('/dashboard/get_bank_balance', 'PosBankController@get_bank_balance')->name('get_bank_balance')->middleware('auth');

Route::get('/dashboard/bank_withdraw', 'PosBankController@bank_withdraw')->name('bank_withdraw')->middleware('auth');

Route::post('/dashboard/save_bank_withdraw', 'PosBankController@save_bank_withdraw')->name('save_bank_withdraw')->middleware('auth');

Route::get('/dashboard/bank_ledger', 'PosBankController@bank_ledger')->name('bank_ledger')->middleware('auth');

Route::post('/dashboard/get_bank_ledger', 'PosBankController@get_bank_ledger')->name('get_bank_ledger')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/add_bank', 'PosBankController@add_bank')->name('add_bank')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/edit_bank/{id}', 'PosBankController@edit_bank')->middleware('auth');

Route::get('/dashboard/view_banks', 'PosBankController@view_banks')->middleware('auth');

Route::get('/dashboard/bank_transfer', 'PosBankController@bank_transfer')->name('bank_transfer')->middleware('auth');

Route::post('/dashboard/save_bank_transfer', 'PosBankController@save_bank_transfer')->name('save_bank_transfer')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/bank_transfer_report', 'PosBankController@bank_transfer_report')->name('bank_transfer_report')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_bank_transfer_report', 'PosBankController@get_bank_transfer_report')->name('get_bank_transfer_report')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/bankdepowithdraw_report', 'PosBankController@bankdepowithdraw_report')->name('bankdepowithdraw_report')->middleware('auth');

Route::match(['get', 'post'], '/dashboard/get_bankdepowithdraw_report', 'PosBankController@get_bankdepowithdraw_report')->name('get_bankdepowithdraw_report')->middleware('auth');

// Warranty Management

Route::view('/dashboard/warranty-management/receive-from-customer', 'admin.pos.warranty-management.receive-from-customer');
Route::view('/dashboard/warranty-management/send-to-supplier', 'admin.pos.warranty-management.send-to-supplier');
Route::view('/dashboard/warranty-management/receive-from-supplier', 'admin.pos.warranty-management.receive-from-supplier');
Route::view('/dashboard/warranty-management/delivery-to-customer', 'admin.pos.warranty-management.delivery-to-customer');
Route::view('/dashboard/warranty-management/warranty-report', 'admin.pos.warranty-management.warranty-report');


// Accounting Route

Route::get('/accounting/acc-heads', 'AccountingPageController@acc_head_index')->name('acc.heads');
Route::get('/accounting/voucher-entry', 'AccountingPageController@voucher_entry_index');
Route::get('/cost-entry', 'AccountingPageController@cost_entry_index')->name('cost-entry');
Route::get('/accounting/voucher-history', 'AccountingPageController@voucher_history_index');
Route::get('/accounting/income-statement', 'AccountingPageController@income_statement_index');
Route::get('/accounting/balance-sheet', 'AccountingPageController@balance_sheet_index');
Route::get('/accounting/cash-book', 'AccountingPageController@cash_book_index');
Route::get('/accounting/ledger', 'AccountingPageController@ledger_index');
Route::get('/accounting/trial-balance', 'AccountingPageController@trial_balance_index');

//Warehouse Routes
Route::match(['get', 'post'], '/admin/manage_warehouse', 'PagesController@warehouse_manage')->name('admin.pos.warehouse.manage');
Route::match(['get', 'post'], '/admin/stock_transfer', 'PagesController@stock_transfer')->name('admin.pos.stock.transfer');
Route::match(['get', 'post'], '/admin/stock-transfer-report', 'PagesController@stock_transfer_report')->name('admin.pos.stock-transfer-report');
Route::match(['get', 'post'], '/admin/stock_transfer_report_date', 'PagesController@stock_transfer_report_date')->name('stock_transfer_report_date');
Route::match(['get', 'post'], '/admin/warehouse_report', 'PagesController@warehouse_report')->name('admin.pos.warehouse_report');

// Servicing

Route::view('/dashboard/servicing/receive-from-customer', 'admin.pos.servicing.receive-from-customer');
Route::view('/dashboard/servicing/delivery-to-customer', 'admin.pos.servicing.delivery-to-customer');
Route::view('/dashboard/servicing/servicing-report', 'admin.pos.servicing.servicing-report');

//Categories Routes (Admin Panel)
Route::match(['get', 'post'], '/admin/create_cost', 'CostController@CreateCost');
Route::post('/admin/add_cost', 'CostController@add_cost')->name('add_cost');
Route::match(['get', 'post'], '/admin/edit_cost/{id}', 'CostController@editCost');
Route::match(['get', 'post'], '/admin/delete_cost/{id}', 'CostController@deleteCost');
Route::match(['get', 'post'], '/admin/deleteBank/{id}', 'PosBankController@deleteBank');
Route::get('/admin/cost_reports', 'CostController@cost_reports')->name('admin.cost_reports');


Route::get('/dashboard/get_sales_info/{invoice_no}', [PosSalesController::class, 'get_sales_info'])->name('get_sales_info')->middleware('auth');
Route::get('/dashboard/get_purchase_info/{invoice_no}', [PosSalesController::class, 'get_purchase_info'])->name('get_purchase_info')->middleware('auth');

Route::get('change-password', [ChangePasswordController::class, 'index'])->name('change-password');
Route::post('change-password', [ChangePasswordController::class, 'store'])->name('change.password');

Route::get('supplier/is-phone-unique/{phone}/{id}', function ($phone, $id) {
    $supplier = \App\Supplier::query()->where('id', '!-', $id)->where('phone', $phone)->get();
    return ['unique' => count($supplier) == 0];
})->name('is-phone-unique');
