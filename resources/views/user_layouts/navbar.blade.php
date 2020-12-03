@yield("headr")
<!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="{{route('home.get')}}"><img src="{{url('/')}}/images/Bazar%20AL%20Seeb%20final%20logo.png"/></a>
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
                        <?php $subDepsFromBlade = []; ?>
                        @foreach($departmentsParents as $mainId => $parent)
                            <div class="col-md-3 col-xs-4 category">
                                <h3>{{$parent->name_en}}</h3>
                                <div class="dropdown-divider"></div>
                                @foreach($parent->child as $sub)
                                    <a class="dropdown-item" href="{{route('getStoreByDepartment',['departmentId' => $sub->id])}}">{{$sub->name_en}}</a>
                                @endforeach
                            </div>
                        @endforeach

                        @foreach($subDepartmentWithoutParent as $sub)
                            <div class="col-md-3 col-xs-4 category">
                                <h3>others</h3>
                                <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{route('getStoreByDepartment',['departmentId' => $sub->id])}}">{{$sub->name_en}}</a>
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
            @if(!session('login') && !\Cookie::get('remembered'))
                @if($active == "login")
                    <li class="active"><img src="{{url('/')}}/icons/login-24px.svg" class="filter-white"><a href="{{route('login')}}">Login</a></li>
                @else
                    <li class=""><img src="{{url('/')}}/icons/login-24px.svg" class="filter-white"><a href="{{route('login')}}" id="log-li">Login</a></li>
                @endif
                @if($active == "register")
                    <li class="active"><img src="{{url('/')}}/icons/how_to_reg-24px.svg" class="filter-white"><a href="{{route('reg')}}">Register</a></li>
                @else
                    <li class="" id="reg-li"><img src="{{url('/')}}/icons/how_to_reg-24px.svg" class="filter-white"><a href="{{route('reg')}}">Register</a></li>
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
            <span id="sumQuantity">{{$sumQuantity}}</span>
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

<!-- 521005274417-kgl4rmdof83j5cr4ukkbc0n1fj5149rp.apps.googleusercontent.com -->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="{{config('services.google.client_id')}}">
    <!--<script async defer src="https://apis.google.com/js/api.js" onload="this.onload=function(){};HandleGoogleApiLibrary()" onreadystatechange="if (this.readyState === 'complete') this.onload()"></script>
    <script type="text/javascript">
    // Called when Google Javascript API Javascript is loaded
    function HandleGoogleApiLibrary() {
        // Load "client" & "auth2" libraries
        gapi.load('client:auth2',  {
            callback: function() {
                // Initialize client & auth libraries
                gapi.client.init({
                    apiKey: 'AIzaSyAHXEEc-Ht8CyD3OTqEXzng-w5EKIJpITQ',
                    clientId: '521005274417-kgl4rmdof83j5cr4ukkbc0n1fj5149rp.apps.googleusercontent.com',
                    scope: 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me'
                }).then(
                    function(success) {
                        alert('success');
                        // Libraries are initialized successfully
                        // You can now make API calls
                    }, 
                    function(error) {
                        alert('error');
                        // Error occurred
                        // console.log(error) to find the reason
                    }
                );
            },
            onerror: function() {
                // Failed to load libraries
            }
        });
    }
    </script>-->

<!-- <script src="https://apis.google.com/js/client:platform.js?onload=renderButton" async defer></script>
<meta name="google-signin-client_id" content="521005274417-kgl4rmdof83j5cr4ukkbc0n1fj5149rp.apps.googleusercontent.com"> -->
    <!-- End Navbar -->
@if(!session('login')  && !\Cookie::get('remembered'))
    <!-- Login Modal -->
        <div class="modal fade search-modal" id="login-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                    <div class="modal-body">
                    <!-- Start Log In -->
                        <div class="log-in-container">
                            <div class="log-in-form-container">
                                <!-- Start Loading -->
                                  <div class="loading-overlay" id="loading" style="display:none;">
                                    <div class="spinner">
                                      <div class="dot1"></div>
                                      <div class="dot2"></div>
                                    </div>
                                  </div>
                                  <!-- End Loading -->
                                <div class="form-img">
                                    <img src="{{url('/')}}/icons/how_to_reg-24px.svg" class="filter-orange">
                                </div>
                                <div class="alert alert-danger" id="errors_log" style="display: none;">
                                  <!-- <strong>Danger!</strong> Indicates a dangerous or potentially negative action. -->
                                </div>
                                
                                <h4 class="form-heading">Log In</h4>
                                <form class="log-in-form" >
                                    <meta name="_token" id="_token" content="{{ csrf_token() }}">
                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-orange"></span>
                                      </div>
                                      <input type="email" name="email" id="log_email" class="form-control" placeholder="Email" >
                                    </div>

                                    <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                                      </div>
                                      <input type="password" name="password" id="log_pass" class="form-control" placeholder="Password" >
                                    </div>

                                    <span class="input-container"><input type="checkbox" name="remember_me" id="remember_me" value="1"></span>
                                    <label class="check-label">Remember me</label>

                                    <input type="button" name="modal-log" id="modal-log" class="form-btn" value="Log In">
                                    <hr>
                                    <p class="form-explain">Or Log In With</p>
                                    <div class="social-login">
                                        <button type="button" name="facebook" id="facebook-log" class="alter-btn hvr-bounce-to-bottom hvr-grow"><div><img src="{{url('/')}}/icons/pngegg%20(3).png" class="filter-lightgray"></div>Facebook</button>
                                        <button type="button" name="google" id="google-log" class="alter-btn hvr-bounce-to-bottom hvr-grow"><div><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-lightgray"></div>Gmail</button>
                                    </div>
                                </form>
                                <hr>
                                <p class="form-explain">If You Don't Have Account On Bazar Al-Seeb</p>
                                <a href="#" class="register-now hvr-icon-forward" data-dismiss="modal" data-toggle="modal" data-target="#register-modal">Register Now <img src="icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow hvr-icon"></a>
                            </div>
                        </div>
                        <div id="g-signin2" style="display:none;"></div>
                        <!-- End Log In -->
                    </div>
              </div>
            </div>
        </div>

        <!-- Register Modal -->
            <div class="modal fade search-modal" id="register-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                        <div class="modal-body">
                            <!-- Start Register -->
                            <div class="register-container">
                                <div class="register-form-container">
                                    
                                    <div class="form-img">
                                        <img src="{{url('/')}}/icons/person_add_alt_1-24px.svg" class="filter-orange">
                                    </div>
                                    
                                    <h4 class="form-heading">Register</h4>
                                    <form class="register-form" >
                                        <meta name="_token" content="{{ csrf_token() }}">
                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><img src="{{url('/')}}/icons/person-24px.svg" class="filter-orange"></span>
                                          </div>
                                          <input type="text" id="reg_fname" name="fname" class="form-control" placeholder="First Name" required>
                                        </div>

                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><img src="{{url('/')}}/icons/people_alt-24px.svg" class="filter-orange"></span>
                                          </div>
                                          <input type="text" id="reg_lname" name="lname" class="form-control" placeholder="Last Name" required>
                                        </div>

                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-orange"></span>
                                          </div>
                                          <input type="email" id="reg_email" name="email" class="form-control" placeholder="Email" required>
                                        </div>

                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                                          </div>
                                          <input type="password" id="reg_password" name="password" class="form-control" placeholder="Password" required>
                                        </div>
                                        <div class="input-group mb-3">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                                          </div>
                                          <input type="password" id="reg_confirmpassword" name="confirmpassword" class="form-control" placeholder="Password Confirmation" required>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                                            </div>
                                            <input type="phone" id="reg_phone" name="phone" class="form-control" placeholder="phone" value="{{old('phone')}}" required>
                                        </div>
                                        <input type="button" id="modal-reg" name="modal-reg" class="form-btn" value="Register">
                                    </form>
                                    <div class="alert alert-danger" id="errors_reg" style="display: none;">
                                      <!-- <strong>Danger!</strong> Indicates a dangerous or potentially negative action. -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Register -->
                        </div>
                  </div>
                </div>
            </div>
@endif
    @yield("content")