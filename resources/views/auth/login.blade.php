<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body  {
            background-image: url("/login-back.jpg");
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            background: #000046;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #1CB5E0, #000046);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #1CB5E0, #000046); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }
        .special-card {
            background-color: rgba(104, 168, 241, 0.4) !important;
        }
        .card {
            background-color: rgba(104, 168, 241, 0.4) !important;
            border-radius: 20px;
            width: 40%;
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="mt-5">
            <div class="card mx-auto text-white">
                <div class="card-body">
                    <div class="text-center">
                        <img src="/logo-com.png" width="30%" alt="">
                        <div  class="mb-3">
                            <small>Please Login to Your Account</small>
                        </div>
                    </div>
                    <div class="mx-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
        
                            <div class="form-group">
                                <div class="d-flex justify-content-between  align-items-center">
                                    <div>
                                        <label for="email" class="text-uppercase" style="letter-spacing: 3px;">Email</label>
                                    </div>
                                    <div>
                                        <div>*</div>
                                    </div>
                                </div>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Type Your Email Number">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
        
                            <div class="form-group">
                                <div class="d-flex justify-content-between  align-items-center">
                                    <div>
                                        <label for="password" class="text-uppercase" style="letter-spacing: 3px;">Password</label>
                                    </div>
                                    <div>
                                        <div>*</div>
                                    </div>
                                </div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Type Your Password Here">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <div class="d-flex justify-content-between  align-items-center">
                                    <div>
                                        <label for="random-number" class="text-uppercase">1 + 2 = </label>
                                    </div>
                                    <div>
                                        <div>*</div>
                                    </div>
                                </div>
                                <input id="random-number" type="number" class="form-control @error('random-number') is-invalid @enderror" name="random-number" value="{{ old('random-number') }}" autocomplete="random-number" placeholder="Type Result Here">
        
                                    @error('random-number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div> --}}
        
                            <div class="">
                                <button type="submit" class="btn text-white font-weight-bold btn-default btn-block" style="background-color: #4B0082 !important; opacity: .7 !important;">
                                    LOG IN
                                </button>
                            </div>
        
                            <div class="form-group d-flex justify-content-between">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
        
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                <div>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="text-uppercase font-weight-bold text-white text-center mt-2" style="letter-spacing: 3px;">
                {{-- <img src="/logo-com.png" width="10%" alt=""> --}}
                Powered By Kinetix BD
            </div>
        </div>
    </div>
</body>
</html>