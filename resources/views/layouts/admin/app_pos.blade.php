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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.5.55/css/materialdesignicons.min.css">

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
        <a href="/dashboard/pos" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ url('/admin/settings') }}" class="nav-link">Settings</a>
      </li>
      <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          {{ Auth::user()->name }} <span class="caret"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="/dashboard/pos/" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fab fa-product-hunt nav-icon"></i>
              <p>
                Product Options
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">3</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/add_product')}}" class="nav-link">
                  <i class="fas fa-plus-square nav-icon"></i>
                  <p>Add New Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/view_products')}}" class="nav-link">
                  <i class="fas fa-clipboard-list nav-icon"></i>
                  <p>View All Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('labels.print')}}" class="nav-link">
                  <i class="fas fa-print nav-icon"></i>
                  <p>Print Labels</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>
                Category Options
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/create_category')}}" class="nav-link">
                  <i class="fas fa-plus-square nav-icon"></i>
                  <p>Add New Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/view_categories')}}" class="nav-link">
                  <i class="fas fa-clipboard-list nav-icon"></i>
                  <p>View All Categories</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fab fa-bandcamp"></i>
              <p>
                Brand Options
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/create_brand')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add New Brand</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/view_brands')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>View All Brands</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-file-invoice-dollar"></i>
              <p>
                Sales
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">3</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('sales_invoice')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Sales Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sales_report_date')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Sales Report Date</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sales_return')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Sales Return</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('sales_return_report_date')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Sales Return Report</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-file-invoice"></i>
              <p>
                Purchase
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('purchase_products')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Purchase Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchase_return')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Purchase Return</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchase_report_date')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Purchase Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchase_return_report_date')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Purchase Return Report</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-money-check-alt"></i>
              <p>
                Banking
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('check_clearance')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Check Clearance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('bank_deposit')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Bank Deposit</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('bank_withdraw')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Bank Withdraw</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('bank_ledger')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Bank Ledger</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-user-shield"></i>
              <p>
                Contacts
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">5</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('set_customer')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Customers</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{route('set_supplier')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Suppliers</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-file-invoice-dollar"></i>
              <p>
                Accounts
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">5</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('ledgers')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Ledger Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('trials')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Trial Balance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('reports.loss-profit')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Loss/Profit Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('cash.flow')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Cash Flow</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-sort-alpha-up"></i>
              <p>
                General Reports
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">5</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('reports.stock-report')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Stock Reports</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('salesby.customer')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Sales By Customer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('salesby.product')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Sales By Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchaseby.product')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Purchase By Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('purchaseby.supplier')}}" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Purchas By Supplier</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                General Settings
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">2</span>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/settings')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Site Settings</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

@yield('content')
@yield('page-js-files')
@yield('page-js-script')

<footer class="main-footer">
    <strong>Copyright &copy; 2014-<script>document.write(new Date().getFullYear())</script> <a href="https://kinetixbd.com">Kinetix BD</a>.</strong>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.js"></script>
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

