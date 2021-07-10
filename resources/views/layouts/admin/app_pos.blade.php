<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$GenSettings->site_name ??  " "}} - @yield('title') | POS Dashboard</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/images/theme/{{$GenSettings->favicon ?? " "}}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
          href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Datetime picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.5.55/css/materialdesignicons.min.css">

    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    @livewireStyles
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('pos_index') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ url('/admin/settings') }}" class="nav-link">Settings</a>
            </li>
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                    <a href="{{ route('change-password') }}" class="nav-link">
                        <p>Change Password</p>
                    </a>
                    <br>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>


                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary mb-5 elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('pos_index') }}" class="brand-link">
            <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
                 class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">{{$GenSettings->site_name ?? "N/A"}}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{asset('dist/img/Profile_gray.png')}}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>


            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item {{ (request()->is('dashboard/pos*')) ? 'active menu-open' : '' }}">
                        <a href="{{ route('pos_index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/add_product', 'admin/view_products', 'admin/print-labels')) ? 'active menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fab fa-product-hunt nav-icon"></i>
                            <p>
                                Product Options
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">6</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/admin/add_product')}}"
                                   class="nav-link {{ (request()->is('admin/add_product')) ? 'active' : '' }}">
                                    <i class="fas fa-plus-square nav-icon"></i>
                                    <p>Add New Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/admin/view_products')}}"
                                   class="nav-link {{ (request()->is('admin/view_products')) ? 'active' : '' }}">
                                    <i class="fas fa-clipboard-list nav-icon"></i>
                                    <p>View All Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/admin/create_category')}}"
                                   class="nav-link {{ (request()->is('admin/create_category')) ? 'active' : '' }}">
                                    <i class="fas fa-plus-square nav-icon"></i>
                                    <p>Add New Category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/admin/view_categories')}}"
                                   class="nav-link {{ (request()->is('admin/view_categories')) ? 'active' : '' }}">
                                    <i class="fas fa-clipboard-list nav-icon"></i>
                                    <p>View All Categories</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/admin/create_brand')}}"
                                   class="nav-link {{ (request()->is('admin/create_brand')) ? 'active' : '' }}">
                                    <i class="fas fa-plus-square nav-icon"></i>
                                    <p>Add New Brand</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/admin/view_brands')}}"
                                   class="nav-link {{ (request()->is('admin/view_brands')) ? 'active' : '' }}">
                                    <i class="fas fa-clipboard-list nav-icon"></i>
                                    <p>View All Brands</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item {{ (request()->is('dashboard/sales_invoice', 'dashboard/sales_return', 'dashboard/sales_report_date', 'dashboard/sales_return_report_date', 'dashboard/sales_report_brand', 'dashboard/sales-customer', 'dashboard/sales-product')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>
                                Sales
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">7</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('sales_invoice')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'sales_invoice' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales Invoice</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('sales_return')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'sales_return' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales Return</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('sales_report_date')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'sales_report_date' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales Report Date</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('sales_return_report_date')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'sales_return_report_date' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales Return Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('salesby.product')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'salesby.product' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales Report By Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('sales_report_brand')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'sales_report_brand' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales Report By Brand</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('salesby.customer')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'salesby.customer' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Sales By Customer</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/purchase_products', 'dashboard/purchase_return', 'dashboard/purchase_report_date', 'dashboard/purchase_return_report_date', 'dashboard/purchase_report_brand', 'dashboard/damage_report_date', 'dashboard/purchase-product', 'dashboard/purchase-supplier')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Purchase
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">7</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('purchase_products')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchase_products' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('purchase_return')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchase_return' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase Return</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('purchase_report_date')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchase_report_date' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('purchase_return_report_date')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchase_return_report_date' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase Return Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('purchase_report_brand')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchase_report_brand' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase Report By Brand</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{route('purchaseby.product')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchaseby.product' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase By Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('purchaseby.supplier')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'purchaseby.supplier' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Purchase By Supplier</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ (request()->is( 'dashboard/damage_products', 'dashboard/damage_report_date')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Damage Entry
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">2</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="/dashboard/damage_products"
                                   class="nav-link {{ (request()->is('dashboard/damage_products')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Damage Products</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('damage_report_date')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'damage_report_date' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Damage Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ (request()->is('dashboard/add_bank', 'dashboard/view_banks', 'dashboard/check_clearance', 'dashboard/bank_deposit', 'dashboard/bank_withdraw', 'dashboard/bank_ledger', 'dashboard/bank_transfer', 'dashboard/bank_transfer_report')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-check-alt"></i>
                            <p>
                                Banking
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">9</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/dashboard/add_bank')}}"
                                   class="nav-link {{ (request()->is('dashboard/add_bank')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Add New Bank</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/dashboard/view_banks')}}"
                                   class="nav-link {{ (request()->is('dashboard/view_banks')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>View Bank Info</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('check_clearance')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'check_clearance' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Check Clearance</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('bank_deposit')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'bank_deposit' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Bank Deposit</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('bank_withdraw')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'bank_withdraw' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Bank Withdraw</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('bankdepowithdraw_report')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'bankdepowithdraw_report' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Bank Withdraw/Deposit Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('bank_ledger')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'bank_ledger' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Bank Ledger</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('bank_transfer')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'bank_transfer' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Bank Transfer</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('bank_transfer_report')}}"
                                   class="nav-link  {{ Route::currentRouteName() == 'bank_transfer_report' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Bank Transfer Report</p>
                                </a>
                            </li>
                            {{--              <li class="nav-item">--}}
                            {{--                  <a href="{{route('admin.pos.bank-loans.create')}}"--}}
                            {{--                      class="nav-link {{ Route::currentRouteName() == 'admin.pos.bank-loans.create' ? 'active' : '' }}">--}}
                            {{--                      <i class="fas fa-angle-right nav-icon"></i>--}}
                            {{--                      <p>Bank Loan </p>--}}
                            {{--                  </a>--}}
                            {{--              </li>--}}
                            {{--              <li class="nav-item">--}}
                            {{--                  <a href="{{route('admin.pos.bank_loans.report')}}"--}}
                            {{--                      class="nav-link {{ Route::currentRouteName() == 'admin.pos.bank_loans.report' ? 'active' : '' }}">--}}
                            {{--                      <i class="fas fa-angle-right nav-icon"></i>--}}
                            {{--                      <p>Bank Loan Report</p>--}}
                            {{--                  </a>--}}
                            {{--              </li>--}}
                            {{--              <li class="nav-item">--}}
                            {{--                  <a href="{{route('admin.pos.bank-loans.create_installment')}}"--}}
                            {{--                      class="nav-link {{ Route::currentRouteName() == 'admin.pos.bank-loans.create_installment' ? 'active' : '' }}">--}}
                            {{--                      <i class="fas fa-angle-right nav-icon"></i>--}}
                            {{--                      <p>Bank Loan Installments</p>--}}
                            {{--                  </a>--}}
                            {{--              </li>--}}
                            {{--              <li class="nav-item">--}}
                            {{--                  <a href="{{route('admin.pos.bank_loans.installment_report')}}"--}}
                            {{--                      class="nav-link {{ Route::currentRouteName() == 'admin.pos.bank_loans.installment_report' ? 'active' : '' }}">--}}
                            {{--                      <i class="fas fa-angle-right nav-icon"></i>--}}
                            {{--                      <p>Installment Reports</p>--}}
                            {{--                  </a>--}}
                            {{--              </li>--}}
                        </ul>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/customers', 'dashboard/customers/customer_ledger', 'dashboard/customers/due-report', 'dashboard/customers/due-collection-report')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Customers
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">3</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('set_customer')}}"
                                   class="nav-link {{ (request()->is('dashboard/customers')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Customer List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/customers/customer_ledger"
                                   class="nav-link {{ (request()->is('dashboard/customers/customer_ledger')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Customer Ledger</p>
                                </a>
                            </li>
                            {{--              <li class="nav-item">--}}
                            {{--                <a href="/dashboard/customers/due-report" class="nav-link {{ (request()->is('dashboard/customers/due-report')) ? 'active' : '' }}">--}}
                            {{--                  <i class="fas fa-angle-right nav-icon"></i>--}}
                            {{--                  <p>Due Report</p>--}}
                            {{--                </a>--}}
                            {{--              </li>--}}
                            <li class="nav-item">
                                <a href="/dashboard/customers/due-collection-report"
                                   class="nav-link {{ (request()->is('dashboard/customers/due-collection-report')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Due Collection Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/suppliers')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>
                                Suppliers
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">1</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('set_supplier')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'set_supplier' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Supplier List</p>
                                </a>
                            </li>
                            {{--              <li class="nav-item">--}}
                            {{--                <a href="{{route('set_supplier_group')}}"--}}
                            {{--                  class="nav-link {{ Route::currentRouteName() == 'set_supplier_group' ? 'active' : '' }}">--}}
                            {{--                  <i class="fas fa-angle-right nav-icon"></i>--}}
                            {{--                  <p>Supplier Group</p>--}}
                            {{--                </a>--}}
                            {{--              </li>--}}
                        </ul>
                    </li>

                    {{-- Accounting --}}
                    <li class="nav-item {{ (request()->is('accounting/acc-heads', 'accounting/voucher-entry', 'accounting/voucher-history', 'accounting/ledger', 'accounting/trial-balance', 'accounting/income-statement', 'accounting/cash-book', 'accounting/balance-sheet', 'add_cost', 'admin/cost_reports')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-calculator"></i>
                            <p>
                                Accounting
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">8</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/accounting/acc-heads"
                                   class="nav-link {{ (request()->is('accounting/acc-heads')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Account Head</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/voucher-entry"
                                   class="nav-link {{ (request()->is('accounting/voucher-entry')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Voucher Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/voucher-history"
                                   class="nav-link {{ (request()->is('accounting/voucher-history')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Voucher History</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/ledger"
                                   class="nav-link {{ (request()->is('accounting/ledger')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Ledger Reports</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/trial-balance"
                                   class="nav-link {{ (request()->is('accounting/trial-balance')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Trial Balance</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/income-statement"
                                   class="nav-link {{ (request()->is('accounting/income-statement')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Income Statement</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/cash-book"
                                   class="nav-link {{ (request()->is('accounting/cash-book')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Cash Book</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/accounting/balance-sheet"
                                   class="nav-link {{ (request()->is('accounting/balance-sheet')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Balance Sheet</p>
                                </a>
                            </li>
                        <!-- <li class="nav-item">
                <a href="{{route('cost-entry')}}" class="nav-link {{ Route::currentRouteName() == 'cost-entry' ? 'active' : '' }}">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>নতুন ব্যয় যুক্ত করুন</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('admin.cost_reports')}}" class="nav-link {{ Route::currentRouteName() == 'admin.cost_reports' ? 'active' : '' }}">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>ব্যয়ের তালিকা</p>
                </a>
              </li> -->
                        </ul>
                    </li>

                    <li class="nav-item {{ (request()->is('admin/manage_warehouse', 'admin/warehouse_report', 'dashboard/reports/stock-report', 'admin/stock_transfer', 'admin/stock-transfer-report')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-hourglass-end"></i>
                            <p>Stock
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">8</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{route('stock-entry')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'stock-entry' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Opening Stock Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('adjustments.adjustment.index')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'adjustments.adjustment.index' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Stock Adjustment</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('adjustments.adjustment.details')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'adjustments.adjustment.details' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Stock Adjustment Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.pos.warehouse.manage')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'admin.pos.warehouse.manage' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Warehouse Management</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.pos.warehouse_report')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'admin.pos.warehouse_report' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Stock Report By Warehouse</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('reports.stock-report')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'reports.stock-report' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Stock Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.pos.stock.transfer')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'admin.pos.stock.transfer' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Stock Transfer</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('admin.pos.stock-transfer-report')}}"
                                   class="nav-link {{ Route::currentRouteName() == 'admin.pos.stock-transfer-report' ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Stock Transfer Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="/dashboard/reports/loss-profit-report"
                           class="nav-link {{ (request()->is('dashboard/reports/loss-profit-report')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sort-alpha-up"></i>
                            <p>Loss Profit Reports</p>
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/warranty-management/receive-from-customer', 'dashboard/warranty-management/send-to-supplier', 'dashboard/warranty-management/receive-from-supplier', 'dashboard/warranty-management/delivery-to-customer', 'dashboard/warranty-management/warranty-report')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Manage Warranty
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">5</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/dashboard/warranty-management/receive-from-customer"
                                   class="nav-link {{ (request()->is('dashboard/warranty-management/receive-from-customer')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Receive From Customer</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/warranty-management/send-to-supplier"
                                   class="nav-link {{ (request()->is('dashboard/warranty-management/send-to-supplier')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Send To Supplier</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/warranty-management/receive-from-supplier"
                                   class="nav-link {{ (request()->is('dashboard/warranty-management/receive-from-supplier')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Receive From Supplier</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/warranty-management/delivery-to-customer"
                                   class="nav-link {{ (request()->is('dashboard/warranty-management/delivery-to-customer')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Delivery To Customer</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/warranty-management/warranty-report"
                                   class="nav-link {{ (request()->is('dashboard/warranty-management/warranty-report')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Warranty Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item {{ (request()->is('dashboard/servicing/receive-from-customer', 'dashboard/servicing/delivery-to-customer', 'dashboard/servicing/servicing-report')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Servicing
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">3</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/dashboard/servicing/receive-from-customer"
                                   class="nav-link {{ (request()->is('dashboard/servicing/receive-from-customer')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Receive From Customer</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/servicing/delivery-to-customer"
                                   class="nav-link {{ (request()->is('dashboard/servicing/delivery-to-customer')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Delivery To Customer</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/dashboard/servicing/servicing-report"
                                   class="nav-link {{ (request()->is('dashboard/servicing/servicing-report')) ? 'active' : '' }}">
                                    <i class="fas fa-angle-right nav-icon"></i>
                                    <p>Servicing Report</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ (request()->is('admin/settings')) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon nav-icon fas fa-cog"></i>
                            <p>
                                General Settings
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right">1</span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/admin/settings')}}"
                                   class="nav-link {{ (request()->is('admin/settings')) ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Site Settings</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item mb-5">
                        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    @if ( Route::current()->uri == 'admin/settings' || Route::current()->uri == 'accounting/acc-heads' )

        @yield('content')

    @elseif($AccHeads <= 0 || $GenSettings == null)

        <div class="content-wrapper">
            <div class="card">
                <div class="card-body text-center text-danger">
                    <h5>Please, Configure <a class="text-primary" href="{{ route('general_settings') }}">General
                            Settings</a>
                        and Create <a href="{{ route('acc.heads') }}">Accounting Heads</a> Before Proceed!</h5>
                </div>
            </div>
        </div>

    @else

        @yield('content')

    @endif
    <div class="d-none">
        <div id="printHeader" class=" mx-auto text-center">
            <h2><?php echo e($GenSettings->site_name??''); ?></h2>
            <h5><?php echo e($GenSettings->site_address??''); ?></h5>
            <h4><?php echo e($GenSettings->phone??''); ?> - <?php echo e($GenSettings->email??''); ?></h4>
            <br>
            <h3 id="reportName"></h3>
            <br>
        </div>
    </div>

    <script>
        function getReportHeader(title) {
            document.getElementById('reportName').innerText = title
            return document.getElementById('printHeader').outerHTML
        }
    </script>
    @yield('page-js-files')
    @yield('page-js-script')

    <footer class="main-footer">
        <strong>Copyright &copy; 2014-
            <script>document.write(new Date().getFullYear())</script>
            <a href="https://kinetixbd.com">Kinetix BD</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.1.0-pre
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- jquery-validation -->
<script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
    $(document).ready(function () {
        $('.searchable').select2({placeholder: "-- Select --"});
    });
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Popper JS -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datetimepicker -->
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
<!-- Datatable Helpers -->
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
@livewireScripts
</body>
</html>

