@extends("user_layouts.navbar")
@section("headr")
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>store brand</title>
        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css">
        <link rel="stylesheet" href="{{url('/')}}/css/store2.css">
        <link rel="stylesheet" href="{{url('/')}}/css/storebrand.css">
    </head>
    <body>
@endsection

@section('content')
    
<!-- carousal -->
<section class="tesst">
    <div class="brand">
        <img src="{{url('/storage/'.$mall->icon)}}">
        <h4 class="brandname">{{$mall->name_en}}</h4>
        <input class="numfollowInput" type="hidden" value="{{$countFollowers}}">
        <span class="numfollow">{{$countFollowers}} followers</span>
        @if(count($mall->users))
            @foreach($mall->users as $user)
                @if(!empty($user))
                <button class="follow">UnFollow</button>
                @else
                <button class="follow">Follow</button>
                @endif
            @endforeach
        @else
            <button class="follow">Follow</button>
        @endif
    </div>
  

    <div id="carouselExampleIndicators" class="carousel slide home-ads" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($ads as $i => $ad)
                @if($i==0)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}" class="active"></li>
                @else
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{$i}}"></li>
                @endif
                <!--<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>-->
            @endforeach
        </ol>
        <div class="carousel-inner">
           @foreach($ads as $i => $ad)
                @if($i==0)
                <div class="carousel-item active">
                @else
                <div class="carousel-item">
                @endif
                    <a href="#">
                        <img src="{{url('/storage/'.$ad->photo)}}" class="d-block w-100" alt="...">
                    </a>
                </div>
            @endforeach
        </div>
    </div> 
         
</section>
    <!-- End carousal -->

<!-- start category -->
<section class="category">
    <input type="hidden" class="categoryInput" value="{{$categoryId}}">
    <div class="chose">
        <div class="cate">
          <p>Category</p>
            <ul>
                @foreach($departments as $department)
                    <li><a href="{{route('storebrand.get',['mallId'=> $mall->id , 'departmentId' => $department->id])}}">{{$department->name_en}}</a></li>
                @endforeach
            </ul>
        </div>

        <div class="home"><a href="{{route('home.get')}}">Home</a></div>
        <div class="allproduct"><a href="{{route('storebrand.get',['mallId'=> $mall->id , 'departmentId' => 'all'])}}">All Product</a></div>
    </div>
    <div class="input-group">
        <input type="text" class="form-control" aria-label="Text input with dropdown button" placeholder="Type to search...">
        <button class="btn search-btn" id="searchId" data-toggle="modal" data-target="#search-cat-modal"><img src="{{url('/')}}/icons/search-24px.svg" class="filter-fairouzi"/></button>
    </div>

    <!-- Search Result -->
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade search-modal" id="search-cat-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-store" id="modal-store">
              <!-- All Search -->
              <!-- if products -->
              <!-- Heading -->
                <!-- <div class="search-header-container">
                    <span class="search-header">Products</span>
                </div>
                <div class="products">
                    <div class="row justify-content-center">
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <input type="hidden" id="product-id" class="product-id" value="2">
                            <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            <img src="images/banner_1.jpg">
                            <div class="product-details">
                                <a href="#" class="product-name">
                                    New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                                </a>
                                <span class="price">120.00 omr</span>
                                <span class="old-price"><del>150.00 omr</del></span>
                                <span class="discount">50%</span>
                                <div class="rating">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span>
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                         <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            <img src="images/product_6.jpg">
                            <div class="product-details">
                                <a href="#" class="product-name">
                                    New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                                </a>
                                <span class="price">120.00 omr</span>
                                <span class="old-price"><del>150.00 omr</del></span>
                                <span class="discount">50%</span>
                                <div class="rating">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span>
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            <img src="images/banner_1.jpg">
                            <div class="product-details">
                                <a href="#" class="product-name">
                                    New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                                </a>
                                <span class="price">120.00 omr</span>
                                <span class="old-price"><del>150.00 omr</del></span>
                                <span class="discount">50%</span>
                                <div class="rating">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span>
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            <img src="images/banner_1.jpg">
                            <div class="product-details">
                                <a href="#" class="product-name">
                                    New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                                </a>
                                <span class="price">120.00 omr</span>
                                <span class="old-price"><del>150.00 omr</del></span>
                                <span class="discount">50%</span>
                                <div class="rating">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span>
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            <img src="images/banner_1.jpg">
                            <div class="product-details">
                                <a href="#" class="product-name">
                                    New product from our amazing store pla pla pla pla pla pla pla pla pla pla
                                </a>
                                <span class="price">120.00 omr</span>
                                <span class="old-price"><del>150.00 omr</del></span>
                                <span class="discount">50%</span>
                                <div class="rating">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span>
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                    </div>
                </div> -->

          </div>
        </div>
      </div>
    </div>

    <div class="sort">
            <div class="price">
            <span>Price</span>
                <input type="text" class="fromPrice" placeholder="from">
                <input type="hidden" class="fromPriceInput" value="0">
                <span>-</span>
                <input type="text" class="toPrice" placeholder="to">
                <input type="hidden" class="toPriceInput" value="0">
            <button class="filterByPrice"><img src="{{url('/')}}/images/play-button.png"></button>
            </div>
        <div class="sorting">
             <span>SortBy</span>
          
            <ul>
                <input type="hidden" class="sortByInput" value="noThing">
                <li class="sortBy noThing"><a >no thing</a></li>
                <li class="sortBy sale"><a >sale</a></li>
                <li class="sortBy created_at-desc"><a >created_at desc</a><li>
                <li class="sortBy created_at-asc"><a >created_at asc</a></li>
                <li class="sortBy price-desc"><a >price desc</a></li>
                <li class="sortBy price-asc"><a >price asc</a></li>
                
            </ul>
        </div>
    </div>
</section>
    
<!-- end category>
    <!-- Start Products Sale Section -->
<div class="info">
    <!-- <div class="row"> -->
        <div class="brands">
             <!-- <div class="brands1" id="brands1">
                <h4>Brand</h4>
                <input type="checkbox" value="">
                <label>Masouti</label>
                <br>
                <input type="checkbox" value="">
                <label>Zara</label>
                <br>
                <input type="checkbox" value="">
                <label>Talis</label>
            </div> -->
            <div class="brands1" id="brands1">
                <h4>Rating</h4>
                <input type="checkbox" name="stars" value="1">
                <div class="rating">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                </div>
                <br>
                <input type="checkbox" name="stars" value="2">
                <div class="rating">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                </div>
                <br>
                <input type="checkbox" name="stars" value="3">
                <div class="rating">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                </div>
                <br>
                <input type="checkbox" name="stars" value="4">
                <div class="rating">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                </div>
                <br>
                <input type="checkbox" name="stars" value="5">
                <div class="rating">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow"> 
                </div>
                <br>
        
            </div>

            <div class="brands1" id="brands1">
                <h4>Color</h4>
                @foreach($colors as $color)
                    <input type="checkbox" name="colors" value="{{$color->id}}">
                    <label>{{$color->name_en}}</label>
                    <br>
                @endforeach
                <!-- <input type="checkbox" name="colors" value="red">
                <label>Red</label>
                <br>
                <input type="checkbox" name="colors" value="">
                <label>Black</label>
                <br>
                <input type="checkbox" name="colors" value="">
                <label>White</label> -->
            </div>
            
            <div class="brands1" id="brands1">
                <h4>Size</h4>
                @foreach($sizes as $size)
                    <input type="checkbox" name="sizes" value="{{$size->id}}">
                    <label>{{$size->name_en}}</label>
                    <br>
                @endforeach
                <!-- <input type="checkbox" name="sizes" value="3">
                <label>XS</label>
                <br>
                <input type="checkbox" name="sizes" value="1">
                <label>S</label>
                <br>
                <input type="checkbox" name="sizes" value="6">
                <label>M</label>
                    <br>
                <input type="checkbox" name="sizes" value="4">
                <label>L</label>
                    <br>
                <input type="checkbox" name="sizes" value="">
                <label>XL</label> -->
            </div>
        </div>
        <div class="info1">
            @foreach($productsByDep as $dep => $mallsProducts)
                <section class="products-sale" id="products-sale">
                    <div class="main-container">
                        <input type="hidden" class="category-name" value="{{$dep}}">
                        <input type="hidden" class="skip" value="{{count($mallsProducts)}}">
                        <h3 class="sec">{{$dep}}</h3>
                        <div class="products">
                            <div class="row justify-content-center">
                                @foreach($mallsProducts as $dep => $product)
                                    @if(count($mallsProducts))
                                    <div class="col-md-2 col-sm-4 col-xs-6 product">
                                            <input type="hidden" id="product-id" class="product-id" value="{{$product->product->id}}">
                                            <meta name="_token" content="{{ csrf_token() }}">
                                            @if(count($product->product->users))
                                                @foreach($product->product->users as $user)
                                                    @if(!empty($user))
                                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                                    @else
                                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                                                    @endif
                                                @endforeach
                                            @else
                                                <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                                            @endif
                                            <img src="{{url('/storage/'.$product->product->photo)}}">
                                            <div class="product-details">
                                                <a href="{{route('product.get',['productId'=>$product->product->id])}}" class="product-name">
                                                    {{$product->product->name_en}}
                                                </a>
                                                @if(!empty($product->product->price_offer) && (time()-(60*60*24)) <= strtotime($product->product->offer_end_at))
                                                <span class="price">{{$product->product->price_offer}} omr</span>
                                                <span class="old-price"><del>{{$product->product->price}} omr</del></span>
                                                <span class="discount">{{(int)((($product->product->price-$product->product->price_offer)/$product->product->price)*100)}}%</span>
                                                @else
                                                <span class="price">{{$product->product->price}} omr</span>
                                                @endif
                                                <div class="rating">
                                                    <input type="hidden" class="index-star" value="0">
                                                    @if(count($product->product->evaluationUsers))
                                                        @foreach($product->product->evaluationUsers as $evaluation)
                                                            @for($i=1;$i<=$evaluation->evaluation;$i++)
                                                                <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                                            @endfor
                                                            @for($i=$evaluation->evaluation;$i<5;$i++)
                                                                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                            @endfor
                                                        @endforeach
                                                    @else
                                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow">
                                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                        <span class="rating-amount">(30)</span>
                                                    @endif
                                                    <!-- <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                                    <span class="rating-amount">(30)</span> -->
                                                </div>
                                                <div class="stock-div">
                                                    <span class="label">Stock</span>
                                                    <div class="progress">
                                                        @if($product->product->stock >= 100)
                                                        <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        @else
                                                        <div class="progress-bar" role="progressbar" style="width: {{($product->product->stock/100)*100}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                                            </div>
                                        </div>
                                        @endif
                                @endforeach

                                <div class="replace_here"></div>
                                
                                
                            </div>
                        </div>
                        <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
                    </div>
                </section>
                @endforeach
            </div>
    <!-- </div> -->
</div>
       
      

    
         
         
    <!-- End Products Sale Section -->
    <!-- Start Footer -->
    <div class="footer navbar-inverse navbar-fixed-bottom">
        <div class="footer-container">
            <div class="container">
            <div class="footer-grids row">
                <div class="col-md-9 col-xs-6 footer-grid">
                    <h3>Bazar Al-Seeb</h3>
                    <p>Grand Shopping Mall In<span class="locate-footer"><img src="{{url('/')}}/images/oman-flag-3d-icon-16.png">Seeb, Oman</span></p>
                </div>
                <div class="col-md-3 col-xs-6 footer-grid">
                    <h3>Contact Info</h3>
                    <ul class="list-unstyled">
                        <li><img src="{{url('/')}}/icons/location_on-24px.svg" class="filter-white">Seeb, Oman</li>
                        <li><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-white"><a href="mailto:info@example.com">bazaralseeb@gmail.com</a></li>
                        <li><img src="{{url('/')}}/icons/call-24px.svg" class="filter-white">+968 9405 6359</li>
                    </ul>
                </div>
            </div>
            <div class="footer-logo">
                <h2><a href="index.html">BAZAR AL-SEEB</a></h2>
                <span>shop anywhere</span>
            </div>
            <div class="copy-righ">
                <p>&copy 2020 Bazar Al seeb. All rights reserved <a href="#">BAZAR AL-SEEB</a></p>
            </div>
        </div>
        </div>
        
    </div>
    <!-- End Footer -->
    <script src="{{url('/')}}/js/jquery-3.3.1.min.js"></script>
    <script src="{{url('/')}}/js/popper.js"></script>
    <script src="{{url('/')}}/js/bootstrap.min.js"></script>
    <script src="{{url('/')}}/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/package/js/swiper.min.js"></script>
    <script src="{{url('/')}}/js/jquery.nicescroll.min.js"></script>
    <script src="{{url('/')}}/js/seeb.js"></script>
      <script src="{{url('/')}}/js/store.js"></script>

      <!-- Start script for search -->
    <script >
            $(".input-group-append .search-btn").click(function(){
                var searchSelect = $(".select-search-val").val();
                var searchQuery = $(".form-control").val();
                var button = "search";

                $.ajax({
                  url: "{{route('home.post')}}",
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'searchSelect': searchSelect,
                    'searchQuery' : searchQuery,
                    'button': button,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    $('.modal-second').html(response.view);
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
                });
             });

     </script>
     <!-- end script search -->

     <script >
            $(document).on('click', '#searchId', function(){
                var currentLocation = window.location.pathname+'';
                array = currentLocation.split('/');
                var mallId = array[array.length-2];
                var searchQuery = $(this).parent().find(".form-control").val();
                var button = "searchByMall";

                $.ajax({
                  url: "/store/"+mallId,
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'searchQuery' : searchQuery,
                    'button': button,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    $('#modal-store').html(response.view);
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
                });
             });

     </script>

     <script>

         // $('.sort .fromPrice').blur(function(){
         //    var fromPrice = $(this).val();
         //    alert(fromPrice);
         // });

        $(document).on('click',".filterByPrice",function(e){
            var sortBy = $('.sortByInput').val();
            var fromPrice = $('.sort .fromPrice').val(); //$('.sort').find('.fromPrice').val();
            var toPrice = $('.sort .toPrice').val() ; //$('.toPrice').val();
            var button = 'sortBy';
            var categoryId = $('.categoryInput').val();
            var stars = [];
            var colors = [];
            var sizes = [];

            var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var mallId = array[array.length-2];
            
                $.each($("input[name='stars']:checked"), function(){
                    stars.push($(this).val());
                });

                $.each($("input[name='colors']:checked"), function(){
                    colors.push($(this).val());
                });

                $.each($("input[name='sizes']:checked"), function(){
                    sizes.push($(this).val());
                });

            $.ajax({
                  url: "/store-brand/"+mallId,
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'sortBy' : sortBy,
                    'button' : button,
                    'categoryId' : categoryId,
                    'fromPrice' : fromPrice,
                    'toPrice' : toPrice,
                    'stars' : stars,
                    'colors' : colors,
                    'sizes' : sizes,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    if(response.operation == 'success'){
                        $('.info1').html(response.view);
                    }else {};     
                    
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
             });
        });
     </script>

     <script>
         // click show all and access input hidden for category
        $(document).on('click',".products-sale .main-container  .showall",function(e){
            var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var mallId = array[array.length-2];

            var button = 'showMore';
            var thisVar = $(this);
            var departmentName = $(this).parent().find(".category-name").val();
            var skip = $(this).parent().find(".skip").val();

            var sortBy = $('.sortByInput').val();
            var fromPrice = $('.sort .fromPrice').val();
            var toPrice = $('.sort .toPrice').val() ;  
            var categoryId = $('.categoryInput').val();
            var stars = [];
            var colors = [];
            var sizes = [];

                $.each($("input[name='stars']:checked"), function(){
                    stars.push($(this).val());
                });

                $.each($("input[name='colors']:checked"), function(){
                    colors.push($(this).val());
                });

                $.each($("input[name='sizes']:checked"), function(){
                    sizes.push($(this).val());
                });

            $.ajax({
                  url: "/store-brand/"+mallId,
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'skip' : skip,
                    'departmentName' : departmentName,
                    'button' : button,
                    'sortBy' : sortBy,
                    'categoryId' : categoryId,
                    'fromPrice' : fromPrice,
                    'toPrice' : toPrice,
                    'stars' : stars,
                    'colors' : colors,
                    'sizes' : sizes,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    if(response.operation == 'success'){
                        thisVar.parent().parent().find('.replace_here').replaceWith(response.view);
                        thisVar.parent().find('.skip').val(response.skip);
                    }else {};     
                    
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
             });
        });
     </script>

     <script>
         // click show all and access input hidden for category
        $(".follow").click(function(){
            var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var mallId = array[array.length-2];
            

            var button = 'follow';

            $.ajax({
                  url: "/store/"+mallId,
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'button' : button,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    if(response.operation == 'success'){

                        var numfollow = $('.numfollowInput').val();

                        $('.follow').text(response.follow);
                        if(response.follow == 'UnFollow') numfollow = parseInt(numfollow, 10) + 1;
                        else numfollow = parseInt(numfollow, 10) - 1;
                        
                        
                        $('.numfollow').text(numfollow+ ' followers');
                        $('.numfollowInput').val(numfollow);
                        
                    }else location.href="{{route('login')}}";
                    
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
             });
        });
     </script>
<!-- sort by script -->
     <script>
         $('.sortBy').click(function(){
             var classes = $(this).attr('class');
             let [mainClass,sortBy] = classes.split(' ');
             var button = 'sortBy';
             $('.sortByInput').val(sortBy);

            var fromPrice = $('.sort .fromPrice').val();
            var toPrice = $('.sort .toPrice').val() ; 
            var categoryId = $('.categoryInput').val();
            var stars = [];
            var colors = [];
            var sizes = [];

             var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var mallId = array[array.length-2];

            var categoryId = $('.categoryInput').val();

             $.each($("input[name='stars']:checked"), function(){
                    stars.push($(this).val());
                });

                $.each($("input[name='colors']:checked"), function(){
                    colors.push($(this).val());
                });

                $.each($("input[name='sizes']:checked"), function(){
                    sizes.push($(this).val());
                });

            $.ajax({
                      url: "/store-brand/"+mallId,
                      type: 'POST',
                      data: {
                        '_token' :"{{csrf_token()}}",
                        'sortBy' : sortBy,
                        'button' : button,
                        'categoryId' : categoryId,
                        'sortBy' : sortBy,
                        'fromPrice' : fromPrice,
                        'toPrice' : toPrice,
                        'stars' : stars,
                        'colors' : colors,
                        'sizes' : sizes,
                      }, 
                      dataType: 'json',
                      success: function (response) {
                        if(response.operation == 'success'){
                            $('.info1').html(response.view);
                        }else {};     
                        
                      },
                      error: function (response) {
                        alert("error ");
                        //location.href="";
                      },
             });
        });

     </script>
<!-- end sort by script -->

     <script>
         $(".brands .brands1 input[type=checkbox]").click(function(){
            var sortBy = $('.sortByInput').val();
            var fromPrice = $('.sort .fromPrice').val();
            var toPrice = $('.sort .toPrice').val() ; 
            var button = 'checkbox';
            var categoryId = $('.categoryInput').val();
            var stars = [];
            var colors = [];
            var sizes = [];

            var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var mallId = array[array.length-2];
            
                $.each($("input[name='stars']:checked"), function(){
                    stars.push($(this).val());
                });

                $.each($("input[name='colors']:checked"), function(){
                    colors.push($(this).val());
                });

                $.each($("input[name='sizes']:checked"), function(){
                    sizes.push($(this).val());
                });

            $.ajax({
                      url: "/store-brand/"+mallId,
                      type: 'POST',
                      data: {
                        '_token' :"{{csrf_token()}}",
                        'sortBy' : sortBy,
                        'button' : button,
                        'categoryId' : categoryId,
                        'fromPrice' : fromPrice,
                        'toPrice' : toPrice,
                        'stars' : stars,
                        'colors' : colors,
                        'sizes' : sizes,
                      }, 
                      dataType: 'json',
                      success: function (response) {
                        if(response.operation == 'success'){
                            $('.info1').html(response.view);
                        }else {};     
                        
                      },
                      error: function (response) {
                        alert("error ");
                        //location.href="";
                      },
             });
            
        });
     </script>
</body>
</html>

@endsection