@yield("headr")
<!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="#"><img src="{{url('/')}}/images/Bazar%20AL%20Seeb%20final%20logo.png"/></a>
        <ul class="navbar-nav mr-auto">
            @if($active == "home")
            <li class="nav-item active">
            @else
            <li class="nav-item ">
            @endif
                    <a class="nav-link" href="{{route('home.get')}}">Home <span class="sr-only">(current)</span></a>
                </li>
            @if($active == "categories")
                <li class="nav-item dropdown active">
            @else
                <li class="nav-item dropdown ">
            @endif
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Categories
                    </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <div class="row categories justify-content-center">
                        @foreach($departmentsParents as $mainId => $subs)
                            <div class="col-md-3 col-xs-4 category">
                                <h3>{{$mainDep[$mainId]->name_en}}</h3>
                                <div class="dropdown-divider"></div>
                                @foreach($subs as $sub)
                                    <a class="dropdown-item" href="{{route('getShowAll',['productsType' => 'products-best-selling'])}}">{{$sub->name_en}}</a>
                                @endforeach
                            </div>
                        @endforeach
                        <!-- <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div>
                        <div class="col-md-3 col-xs-4 category">
                            <h3>Wear</h3>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                            <a class="dropdown-item">Clothing</a>
                            <a class="dropdown-item">Wallets</a>
                        </div> -->
                    </div>
                </div>
                </li>
                @if($active == "about")
                <li class="nav-item active">
                @else
                <li class="nav-item">
                @endif
                    <a class="nav-link" href="{{ url('/') }}/about-us">About Us</a>
                </li>
        </ul>
          <ul class="left-nav my-2 my-lg-0">
            @if(!session('login'))
                @if($active == "login")
                    <li class="active"><img src="{{url('/')}}/icons/login-24px.svg" class="filter-white"><a href="{{route('login')}}">Login</a></li>
                @else
                    <li class=""><img src="{{url('/')}}/icons/login-24px.svg" class="filter-white"><a href="{{route('login')}}">Login</a></li>
                @endif
                @if($active == "register")
                    <li class="active"><img src="{{url('/')}}/icons/how_to_reg-24px.svg" class="filter-white"><a href="{{route('reg')}}">Register</a></li>
                @else
                    <li class=""><img src="{{url('/')}}/icons/how_to_reg-24px.svg" class="filter-white"><a href="{{route('reg')}}">Register</a></li>
                @endif
            @else
                <li><img src="{{url('/')}}/icons/login-24px.svg" class="filter-white"><a href="{{route('logout')}}">logout</a></li>
            @endif
                <li class="lang-ch"><img src="{{url('/')}}/images/oman-flag-3d-icon-16.png"><a href="#">Ar</a>
                            <img src="{{url('/')}}/images/united-states-of-america-flag-icon-16.png"><a href="#" class="selected-lang">En</a>
                </li>
  
            </ul>
     
    </nav>
    <div class="nav-bottom">
        
        <div class="input-group">
            <input type="text" class="form-control" aria-label="Text input with dropdown button" placeholder="Type to search...">
            <form class="input-group-append">
                
                <input type="hidden" class="select-search-val" value="all">
                <button class="btn dropdown-toggle select-search" type="button" id="dropdownsearch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    All
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownsearch">
                    <a class="dropdown-item drop-mall" >Stores</a>
                    <a class="dropdown-item drop-product" >Products</a>
                    <a class="dropdown-item drop-department" >Category</a>
                    <a class="dropdown-item drop-all" >All</a>
                </div>
                <button class="btn search-btn" type="button" id="button-addon2" data-toggle="modal" data-target="#search-modal"><img src="{{url('/')}}/icons/search-24px.svg" class="filter-fairouzi"/></button>
            </form>
        </div>
        <div class="card-div" id="card-nav">
            <a href="{{route('check.get')}}">
                <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-orange">
            </a>
            <span>{{$sumQuantity}}</span>
        </div>
    </div>

    <!-- Search Result -->
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade search-modal" id="search-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-second">
            <!-- Stores -->
            <!-- <div class="stores">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/zara.jpg">
                                <div class="store-footer">
                                    <a href="#">ZARA</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/zara.jpg">
                                <div class="store-footer">
                                    <a href="#">ZARA</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                        </div>
                        
                    </div> -->
              <!-- Products -->
              <!-- <div class="products">
                <div class="row justify-content-center">
                    <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/banner_1.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                     <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/product_6.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                     <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/black-framed-eyeglasses-on-white-jacket-and-blue-denim-934070.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                     <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/pexels-markus-spiske-191158.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                     <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/gallery_1.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                     <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/4.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                    
                </div>
            </div> -->
            <!-- Categories -->
              <!-- <div class="cats">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                <img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-gray">
                                <div class="store-footer">
                                    <a href="#">Restaurants</a>
                                </div>
                            </div>
                        </div>
                        
                    </div> -->
              <!-- All Search -->
              <!-- if products -->
              <!-- Heading -->
               <!-- <div class="search-header-container">
                    <span class="search-header">Products</span>
                </div>
              <div class="products">
                <div class="row justify-content-center">
                    <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/banner_1.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                     <div class="col-md-2 col-sm-4 col-xs-6 product">
                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                        <img src="{{url('/')}}/images/product_6.jpg">
                        <div class="product-details">
                            <a href="#" class="product-name">
                                New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                            </a>
                            <span class="price">120.00 omr</span>
                            <span class="old-price"><del>150.00 omr</del></span>
                            <span class="discount">50%</span>
                            <div class="rating">
                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                <span class="rating-amount">(30)</span>
                            </div>
                            <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                        </div>
                    </div>
                    
                </div>
            </div>
              
            <hr>
              
            <!-- if stores -->
             <!--<div class="search-header-container">
                    <span class="search-header">Stores</span>
             </div>
              <div class="stores">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/zara.jpg">
                                <div class="store-footer">
                                    <a href="#">ZARA</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="{{url('/')}}/images/zara.jpg">
                                <div class="store-footer">
                                    <a href="#">ZARA</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>-->
              <!-- Start Loading -->
              <!--<div class="loading-overlay">
                <div class="spinner">
                  <div class="dot1"></div>
                  <div class="dot2"></div>
                </div>
              </div>-->
              <!-- End Loading -->
          </div>
        </div>
      </div>
    </div>

    <!-- End Navbar -->
    
    @yield("content")