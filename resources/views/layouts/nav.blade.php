    <nav>
      <div class="container">
          <div class="mm-toggle-wrap">
            <div class="mm-toggle"><i class="fa fa-reorder"></i><span class="mm-label">Menu</span> </div>
          </div>
      
            <ul class="nav hidden-xs menu-item menu-item-left">
                <div class="top-search">
                  <div class="block-icon"> <a data-target=".bs-example-modal-lg" data-toggle="modal" class="search-focus dropdown-toggle links"> <i class="fa fa-search"></i> </a></div>
                </div>
              <li class="level0 parent drop-menu active"><a href="/"><span>Home</span></a>
              </li>
              <li class="level0 parent drop-menu"><a href="/"><span>Black Friday Sale</span></a>
              </li>
              <li class="level0 parent drop-menu"><a href="#">SKIN CARE</a>
                    <ul class="level1" style="display: none;">
                        @foreach($skinCare as $item)
                            <li><a href="/category/{{$item->url}}">{{ $item->name }}</a></li>
                        @endforeach
                    </ul>
              </li>
              <li class="mega-menu"><a href="#" class="level-top"><span>SKIN CONCERN</span></a>
                <div  style="left: 0px; display: none;" class="level0-wrapper dropdown-6col">
                  <div class="container">
                    <div class="level0-wrapper2">
                      <div class="nav-block nav-block-center">
                        <ul class="level0">
                          <li class="level1 nav-6-1 parent item"><a href="#"><span>SKIN TYPE</span></a>
                            <ul class="level1">
                              @foreach($skinType as $item)
                              <li class="level2 nav-6-1-1"><a href="/category/{{$item->url}}"><span>{{$item->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                          <li class="level1 nav-6-1 parent item"><a href="#"><span>SKIN CONCERNS</span></a>
                            <ul class="level1">
                              @foreach($skinConcerns as $item)
                              <li class="level2 nav-6-1-1"><a href="/category/{{$item->url}}"><span>{{$item->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                        </ul>
                      </div>
                      <!--level0-wrapper2-->
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <!-- Header Logo -->
            <div class="logo"><a title="RR Beauty" href="/"><img alt="RR World Vision" src="/images/logo.png" width="100px" style="border-radius: 4px;"></a></div>
            <!-- <div class="navbar-brand navbar-brand-centered">Brand</div> -->
            <!-- End Header Logo --> 
            <ul class="nav hidden-xs menu-item menu-item-right" style="margin-top: 50px;">
              <li class="level0 parent drop-menu"><a href="#">MAKEUP</a>
                    <ul class="level1" style="display: none;">
                      @foreach($makeup as $item)
                        <li class="level2 nav-6-1-1"><a href="/category/{{$item->url}}"><span>{{$item->name}}</span></a></li>
                      @endforeach
                    </ul>
              </li>
              <li class="level0 parent drop-menu"><a href="#">HAIR & BODY</a>
                    <ul class="level1" style="display: none;">
                      @foreach($hairBody as $item)
                        <li class="level2 nav-6-1-1"><a href="/category/{{$item->url}}"><span>{{$item->name}}</span></a></li>
                      @endforeach
                    </ul>
              </li>
              <li class="mega-menu"><a href="#" class="level-top"><span>BRANDS</span></a>
                <div  style="left: 0px; display: none;" class="level0-wrapper dropdown-6col">
                  <div class="container">
                    <div class="level0-wrapper2">
                      <div class="nav-block nav-block-center">
                        <ul class="level0">
                          <li class="level1 nav-6-1 parent item"><a href="#"><span># - B</span></a>
                            <ul class="level1">
                              @foreach($hashToB as $brand)
                              <li class="level2 nav-6-1-1"><a href="/brands/{{$brand->url}}"><span>{{$brand->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                          <li class="level1 nav-6-1 parent item"><a href="#"><span>C - H</span></a>
                            <ul class="level1">
                              @foreach($cToH as $brand)
                              <li class="level2 nav-6-1-1"><a href="/brands/{{$brand->url}}"><span>{{$brand->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                          <li class="level1 nav-6-1 parent item"><a href="#"><span>I - N</span></a>
                            <ul class="level1">
                              @foreach($iToN as $brand)
                              <li class="level2 nav-6-1-1"><a href="/brands/{{$brand->url}}"><span>{{$brand->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                          <li class="level1 nav-6-1 parent item"><a href="#"><span>O - T</span></a>
                            <ul class="level1">
                              @foreach($oToT as $brand)
                              <li class="level2 nav-6-1-1"><a href="/brands/{{$brand->url}}"><span>{{$brand->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                          <li class="level1 nav-6-1 parent item"><a href="#"><span>U - Z</span></a>
                            <ul class="level1">
                              @foreach($uToz as $brand)
                              <li class="level2 nav-6-1-1"><a href="/brands/{{$brand->url}}"><span>{{$brand->name}}</span></a></li>
                              @endforeach
                            </ul>
                          </li>
                        </ul>
                      </div>
                      <!--level0-wrapper2-->
                    </div>
                  </div>
                </div>
              </li>
              <li class="level0 parent drop-menu"><a href="/category/{{$giftSets->url}}"><span>GIFT SETS</span></a>
              <li class="end-menu">
                <ul class="nav justify-content-end" style="margin-top: 0;">
                  <li class="nav-item">
                    <a class="nav-link" href="/myaccount"><i class="glyphicon glyphicon-user"></i></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="/cart"><i class="glyphicon glyphicon-shopping-cart"></i></a>
                    @if($userCart->count() >0)
                      <span class="cart-link__bubble cart-link__bubble--visible"></span>
                    @endif
                  </li>
                </ul>
              </li>
              
            </ul>
      </div>
    </nav>
                  <div class="top-search">
                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><img src="/images/interstitial-close.png" alt="close"> </button>
                          </div>
                          <div class="modal-body">
                            <form action="{{ route('search') }}" method="post" class="navbar-form">
                              @csrf
                              <div id="search">
                                <div class="input-group">
                                  <input name="search" placeholder="Search" class="form-control" type="text">
                                  <button type="button" class="btn-search"><i class="fa fa-search"></i></button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>