    <!-- Feature Section Start -->
    <div class="feature-section section mt-30 mb-20">
        <div class="container-fluid">
            <div class="row row-5">

                <div class="col-lg-3 col-md-6 col-12 mb-10">
                    <!-- Feature Start -->
                    <div class="feature-two feature-three" style="background-image: url(images/icons/feature-van-2-bg.png); background-color: #00ccce;">
                        <div class="feature-wrap">
                            <div class="icon"><img src="/images/icons/feature-van-2.png" alt="Feature"></div>
                            <h4>FREE SHIPPING</h4>
                            <p>Start from $100</p>
                        </div>
                    </div><!-- Feature End -->
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-10">
                    <!-- Feature Start -->
                    <div class="feature-two feature-three" style="background-image: url(images/icons/feature-walet-2-bg.png); background-color: #97c200;">
                        <div class="feature-wrap">
                            <div class="icon"><img src="/images/icons/feature-walet-2.png" alt="Feature"></div>
                            <h4>MONEY BACK GUARANTEE</h4>
                            <p>Back within 15 days</p>
                        </div>
                    </div><!-- Feature End -->
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-10">
                    <!-- Feature Start -->
                    <div class="feature-two feature-three" style="background-image: url(images/icons/feature-shield-2-bg.png); background-color: #f5d730;">
                        <div class="feature-wrap">
                            <div class="icon"><img src="/images/icons/feature-shield-2.png" alt="Feature"></div>
                            <h4>SECURE PAYMENTS</h4>
                            <p>Payment Security</p>
                        </div>
                    </div><!-- Feature End -->
                </div>

                <div class="col-lg-3 col-md-6 col-12 mb-10">
                    <!-- Feature Start -->
                    <div class="feature-two feature-three" style="background-image: url(images/icons/feature-support-2-bg.png); background-color: #08b8f4;">
                        <div class="feature-wrap">
                            <div class="icon"><img src="/images/icons/feature-support-2.png" alt="Feature"></div>
                            <h4>ONLINE SUPPORT 24/7</h4>
                            <p>Technical Support 24/7</p>
                        </div>
                    </div><!-- Feature End -->
                </div>

            </div>
        </div>
    </div>
    <!-- Feature Section End -->

    <!-- Footer Section Start -->
    <div class="footer-section section bg-ivory">
        <!-- Footer Top Section Start -->
        <div class="footer-top-section section pt-90 pb-50">
            <div class="container-fluid">
                <div class="row">
                    <!-- Footer Widget Start -->
                    <div class="col-lg-3 col-md-6 col-12 mb-40">
                        <div class="footer-widget">

                            <h4 class="widget-title">CONTACT INFO</h4>

                            <p class="contact-info">
                                <span>Address</span>
                                {{$GenSettings->site_address  ?? "N/A"}} <br>
                            </p>

                            <p class="contact-info">
                                <span>Phone</span>
                                <a href="tel:{{$GenSettings->phone  ?? "N/A"}}">{{$GenSettings->phone  ?? "N/A"}}</a>
                            </p>

                            <p class="contact-info">
                                <span>Web</span>
                                <a href="mailto:{{$GenSettings->phone  ?? "N/A"}}">{{$GenSettings->phone  ?? "N/A"}}</a>
                                <a href="/"><?php
                                    $host = request()->getHttpHost();
                                    echo $host;
                                ?></a>
                            </p>

                        </div>
                    </div><!-- Footer Widget End -->

                    <!-- Footer Widget Start -->
                    <div class="col-lg-3 col-md-6 col-12 mb-40">
                        <div class="footer-widget">

                            <h4 class="widget-title">CUSTOMER CARE</h4>

                            <ul class="link-widget">
                                <li><a href="#">About us</a></li>
                                <li><a href="/myaccount">My Account</a></li>
                                <li><a href="/cart">Cart</a></li>
                                <li><a href="/checkout">Checkout</a></li>
                                <li><a href="#">blog</a></li>
                                <li><a href="#">Contact</a></li>
                            </ul>

                        </div>
                    </div><!-- Footer Widget End -->

                    <!-- Footer Widget Start -->
                    <div class="col-lg-3 col-md-6 col-12 mb-40">
                        <div class="footer-widget">

                            <h4 class="widget-title">INFORMATION</h4>

                            <ul class="link-widget">
                                <li><a href="/myaccount">Track your order</a></li>
                                <li><a href="#">Locate Store</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Payment</a></li>
                                <li><a href="#">Shipping & Returns</a></li>
                                <li><a href="#">Gift coupon</a></li>
                                <li><a href="#">Special coupon</a></li>
                            </ul>

                        </div>
                    </div><!-- Footer Widget End -->

                    <!-- Footer Widget Start -->
                    <div class="col-lg-3 col-md-6 col-12 mb-40">
                        <div class="footer-widget">

                            <h4 class="widget-title">LATEST TWEET</h4>

                            <div class="footer-tweet"></div>

                        </div>
                    </div><!-- Footer Widget End -->

                </div>

            </div>
        </div><!-- Footer Bottom Section Start -->

        <!-- Footer Bottom Section Start -->
        <div class="footer-bottom-section section">
            <div class="container-fluid">
                <div class="row">
                    <!-- Footer Copyright -->
                    <div class="col-lg-6 col-12">
                        <div class="footer-copyright"><p>&copy; Copyright, All Rights Reserved by <a href="#">{{$GenSettings->site_name  ?? "N/A"}}.</a></p></div>
                    </div>
                    <!-- Footer Payment Support -->
                    <div class="col-lg-6 col-12">
                        <div class="footer-payments-image"><img src="/images/payment-support.png" alt="Payment Support Image"></div>
                    </div>
                </div>
            </div>
        </div><!-- Footer Bottom Section Start -->
    </div><!-- Footer Section End -->

    <!-- JS ============================================ -->

    <!-- jQuery JS -->
    <script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
    <!-- Popper JS -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Plugins JS -->
    <script src="{{ asset('js/plugins.js') }}"></script>
    <!-- Ajax Mail -->
    <script src="{{ asset('js/ajax-mail.js') }}"></script>
    <!-- Main JS -->
    <script src="{{ asset('js/main.js') }}"></script>
    </body>
</html>
