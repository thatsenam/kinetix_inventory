<?php 
  use App\Http\Controllers\Controller;
  $items = Controller::navCats();
  $userCart = Controller::cartData();
  $navBrands = Controller::navBrands();
  $settings = Controller::generalSettings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <!--[if IE]>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <![endif]-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Beauty Korea is trusted Authentic K-Beauty Korean Cosmetics Online shop with Best Price. Next Day Delivery. Shop the latest Korean Beauty trends &amp; Top brands like Somebymi, Purito, Cosrx, Nature Republic, Innisfree, Etude House, Neogen etc.">
  <meta name="author" content="">

  <!-- Favicons Icon -->
  <title>RR World Vision &amp; Aristocracy In Beauty</title>

  <!-- Mobile Specific -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSS Style -->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.css')}}" media="all">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/simple-line-icons.css')}}" media="all">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/owl.carousel.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/owl.theme.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.bxslider.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.mobile-menu.css')}}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/revslider.css')}}" >
  <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css')}}" media="all">
  <link rel="stylesheet" type="text/css" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">

  <!-- Google Fonts -->
  <link href='https://fonts.googleapis.com/css?family=Raleway:400,100,200,300,500,600,700,800,900' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Dosis&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300&display=swap" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300italic,300,600,600italic,400italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="{{ asset('js/jquery.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
</head>

<body class="inner-page">
  <div id="page">
    <!-- Header -->
    <header>
      <div class="header-container">
        @include('layouts.top-header')
      </div>
    </header>
    <!-- end header -->
    <!-- Navbar -->
    @include('layouts.nav')
    <!-- end nav --> 

    <div class="app-main">
        @yield('content')
    </div>

    @include('layouts.footer')
  </div>
  @include('layouts.mobile-menu')

  <!-- End Footer --> 

  <!-- JavaScript --> 
  <script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/parallax.js')}}"></script>  
  <script type="text/javascript" src="{{ asset('js/common.js')}}"></script> 
  <script type="text/javascript" src="{{ asset('js/countdown.js')}}"></script> 
  <script type="text/javascript" src="{{ asset('js/jquery.flexslider.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/owl.carousel.min.js')}}"></script> 
  <script type="text/javascript" src="{{ asset('js/jquery.mobile-menu.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/revolution-slider.js')}}"></script> 
  <script type="text/javascript" src="{{ asset('js/revolution.extension.js')}}"></script> 
  <script type="text/javascript" src="{{ asset('js/cloud-zoom.js')}}"></script>

  <script type="text/javascript">
    $(document).ready(function(){
      $('.notices').owlCarousel({
        items:1,
        loop:true,
        margin:10,
        autoPlay:true,
        autoPlayTimeout:1000,
        autoPlayHoverPause:true
      });
    });
  </script>
</body>
</html>