@extends('layouts.header')
@section('content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <ul class="breadcrumb">
              <li><a href="/">Home / </a></li>
              <li><a href="#"> Login / Register</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Breadcrumbs End -->
    <section id="form"><!--form-->
		<div class="container">
			<div class="col-12 p-0" style="margin: 50px auto;">
                    @if ($success = Session::get('flash_message_success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $success }}</strong>
                        </div>
                    @endif
                    @if ($error = Session::get('flash_message_error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $error }}</strong>
                        </div>
                    @endif
                <div class="tabs">
                    <div class="tab-2">
                        <label for="tab2-1">LOGIN</label>
                        <input id="tab2-1" name="tabs-two" type="radio" checked="checked">
                        <div>
                            <div class="login-form"><!--login form-->
                                <h4 class="mb-3">Login to your account</h4>
                                <form action="{{ url('/user_logoin') }}" method="POST" id="LoginForm" class="needs-validation mt-2" novalidate>
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" name="logEmail" placeholder="Email Address" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="password" name="logPass" placeholder="Enter Password" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-medium btn-circle">Submit Form</button>
                                </form>
                            </div><!--/login form-->
                        </div>
                    </div>
                    <div class="tab-2">
                        <label for="tab2-2">REGISTER</label>
                        <input id="tab2-2" name="tabs-two" type="radio">
                        <div>
                        <div class="signup-form"><!--sign up form-->
                                <h4 class="mb-4">New User? Signup!</h4>
                                <form action="{{ url('/user_register') }}" method="POST" id="RegisterForm" class="needs-validation mt-2" novalidate>
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="inputName" id="inputName" placeholder="Name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="inputEmail" id="inputEmail" placeholder="Email Address" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="phone" name="inputPhone" id="inputPhone" placeholder="Phone Number" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="inputAddress" id="inputAddress" placeholder="House No, Road No. or Name, Location Name, District Name-79658" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="inputPass" id="inputPass" placeholder="Password" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-medium btn-circle">Signup Now</button>
                                </form>
                            </div><!--/sign up form-->
                        </div>
                    </div>
                    </div>
            </div>
		</div>
	</section>

<style>
    button:focus, input:focus, textarea:focus, select:focus {
        outline: none;
    }
    .tabs {
        display: block;
        display: -webkit-flex;
        display: -moz-flex;
        display: flex;
        -webkit-flex-wrap: wrap;
        -moz-flex-wrap: wrap;
        flex-wrap: wrap;
        margin: 0 auto;
        overflow: hidden;
        border: 1px solid;
        max-width: 500px;
    }
    .tabs [class^="tab"] label, .tabs [class*=" tab"] label {
        color: #000;
        cursor: pointer;
        display: block;
        font-size: 18px;
        font-weight: 700;
        line-height: 1em;
        padding: 1.2rem 0;
        text-align: center;
    }
    .form-check-label{display:inline-block !important; padding: 1rem 0;}
    .tabs [class^="tab"] [type="radio"], .tabs [class*=" tab"] [type="radio"] {
        border-bottom: 1px solid rgba(239, 237, 239, 0.5);
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        display: block;
        width: 100%;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        margin: 0;
    }
    .tabs [class^="tab"] [type="radio"]:hover, .tabs [class^="tab"] [type="radio"]:focus, .tabs [class*=" tab"] [type="radio"]:hover, .tabs [class*=" tab"] [type="radio"]:focus {
        border-bottom: 1px solid #fd264f;
    }
    .tabs [class^="tab"] [type="radio"]:checked, .tabs [class*=" tab"] [type="radio"]:checked {
        border-bottom: 2px solid #111;
    }
    .tabs [class^="tab"] [type="radio"]:checked + div, .tabs [class*=" tab"] [type="radio"]:checked + div {
        opacity: 1;
    }
    .tabs [class^="tab"] [type="radio"] + div, .tabs [class*=" tab"] [type="radio"] + div {
        display: block;
        opacity: 0;
        padding: 2rem 0;
        width: 90%;
        -webkit-transition: all 0.3s ease-in-out;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    .tabs .tab-2 {
        width: 50%;
    }
    .tabs .tab-2 [type="radio"] + div {
        width: 200%;
        margin-left: 200%;
    }
    .tabs .tab-2 [type="radio"]:checked + div {
        margin-left: 0;
        padding: 20px;
    }
    .tabs .tab-2:last-child [type="radio"] + div {
        margin-left: 100%;
    }
    .tabs .tab-2:last-child [type="radio"]:checked + div {
        margin-left: -100%;
    }
 
</style>
@endsection