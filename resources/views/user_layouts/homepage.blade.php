@extends("user_layouts.navbar")
@section("headr")
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Home</title>
        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css">
        <script src="{{url('/')}}/js/jquery-3.3.1.min.js"></script>
        
    </head>
    <body>
@endsection
<!-- start navbar here-->

@section("content")    
    
    <!-- End Navbar -->
    <!-- Start Ads Carousel -->
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
                        <a href="{{route('storebrand.get',['mallId'=>$ad->mall->id,'departmentId'=>'all'])}}">
                            <img src="{{Storage::url('/storage/'.$ad->photo)}}" class="d-block w-100" alt="...">
                        </a>
                    </div>
                @endforeach
                <!-- <div class="carousel-item active">
                    <a href="#">
                        <img src="images/65695255-black-friday-sales-fashion-shopping-woman-black-silhouette-with-shopping-bags-black-friday-sale-adve.jpg" class="d-block w-100" alt="...">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="#">
                        <img src="images/sales.jpg" class="d-block w-100" alt="...">
                    </a>
                </div>
                <div class="carousel-item">
                    <a href="#">
                        <img src="images/pexels-artem-beliaikin-2292953.jpg" class="d-block w-100" alt="...">
                    </a>
                </div> -->
                </div>
    </div>
    <!-- End Ads Carousel -->
    
    <!-- Start Products Sale Section -->
    <section class="products-sale" id="products-sale">
        <div class="section-heading">
            <img src="icons/local_fire_department-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Products With Sale</h3>
        </div>
        <div class="main-container">
            @if(count($productsWithSale)>0)
            <div class="time-container">
                <span class="label">Ending In</span>
                <span class="time" id="ending_days" style="visibility:hidden">48</span><span id="end_d" style="visibility:hidden">D</span>
                <span class="time" id="ending_hours">48</span>H
                <span class="time" id="ending_minutes">33</span>M
                <span class="time" id="ending_seconds">10</span>S
            </div>
            <input type="hidden" class="current_date" value="{{$currentDate}}">
            <input type="hidden" class="end_date" value="{{$endDate}}">
            <div class="products">
                <div class="row justify-content-center">
                    @foreach($productsWithSale as $product)
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                            <meta name="_token" content="{{ csrf_token() }}">
                            @if(count($product->users))
                                @foreach($product->users as $user)
                                    @if(!empty($user))
                                    <img src="icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                    @else
                                    <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                                    @endif
                                @endforeach
                            @else
                                <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            @endif
                            <img src="{{Storage::url('/storage/'.$product->photo)}}">
                            <div class="product-details">
                                <a href="{{route('product.get',['productId'=>$product->id])}}" class="product-name">
                                    {{$product->name_en}}
                                </a>
                                <span class="price">{{$product->price_offer}} omr</span>
                                <span class="old-price"><del>{{$product->price}} omr</del></span>
                                <span class="discount">{{(int)((($product->price-$product->price_offer)/$product->price)*100)}}%</span>
                                <div class="rating">
                                    <input type="hidden" class="index-star" value="0">
                                    @if(count($product->evaluationUsers))
                                        @foreach($product->evaluationUsers as $evaluation)
                                            @for($i=1;$i<=$evaluation->evaluation;$i++)
                                                <img src="icons/star-24px.svg" class="filter-yellow">
                                            @endfor
                                            @for($i=$evaluation->evaluation;$i<5;$i++)
                                                <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                            @endfor
                                        @endforeach
                                    @else
                                        <img src="icons/star_border-24px.svg" class="filter-yellow">
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <span class="rating-amount">(30)</span>
                                    @endif
                                    <!-- <input type="hidden" class="index-star" value="0">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span> -->
                                </div>
                                <div class="stock-div">
                                    <span class="label">Stock</span>
                                    <div class="progress">
                                        @if($product->stock >= 100)
                                        <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        @else
                                        <div class="progress-bar" role="progressbar" style="width: {{($product->stock/100)*100}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        @endif
                                    </div>
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="showall"><a href="{{route('getShowAll',['productsType' => 'products-with-sale'])}}" class="show-all">Show All</a><img src="icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
        </div>
        @else
            </br>
            <div class="alert alert-info">
              <strong>Info!</strong> not found poduct with sale ending tomorrow.
            </div>
        @endif
    </section>
    <!-- End Products Sale Section -->
    <!-- Start Collection Section -->
    <section class="collection">
        <div class="section-heading">
            <img src="icons/view_list-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Collection</h3>
        </div>
            <div class="container-custom">
            <div class="row justify-content-center">
                @foreach($products as $product)
                    <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                        <div class="prod-img">
                            <img src="{{Storage::url('/storage/'.$product->photo)}}">
                        </div>
                        <div class="prod-hor-details">
                            @foreach($product->malls as  $key => $mall)
                                @if($key == 0)
                                    <a href="{{route('storebrand.get',['mallId'=>$mall->mall->id , 'departmentId'=>'all'])}}" class="store-name">{{$mall->mall->name_en}}</a>
                                @endif
                            @endforeach
                            @if(!empty($product->price_offer) && (time()-(60*60*24)) <= strtotime($product->offer_end_at))
                                <span class="prod-price">{{$product->price_offer}} omr</span>
                            @else
                                <span class="prod-price">{{$product->price}} omr</span>
                            @endif
                        </div>
                    </div>
                @endforeach
               
                <!-- <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/blog_2.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/gallery_1.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/4.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/banner_3.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/4.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/banner_3.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/20.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 prod-hor-card">
                    <div class="prod-img">
                        <img src="images/22.jpg">
                    </div>
                    <div class="prod-hor-details">
                        <a href="#" class="store-name">MM</a>
                        <span class="prod-price">120.00 omr</span>
                    </div>
                </div> -->
            </div>
          </div>

    </section>
    <!-- End Collection Section -->
    <!-- Start Stores Section -->
    <section class="stores-section" id="stores-section">
        <div class="section-heading">
            <img src="icons/store_mall_directory-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Stores</h3>
        </div>
        <div class="stores-div">
            <div class="container">
                <div class="sub-container">
                      <!-- Swiper -->
                  <div class="swiper-container">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide"><img src="icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/shopping_cart-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/shopping_cart-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                  </div>
                    <div class="stores">
                        <div class="row justify-content-center">
                            @foreach($malls as $mall)
                                <div class="col-md-6 col-sm-4 col-xs-6 store">
                                    <img src="{{Storage::url('/storage/'.$mall->icon)}}">
                                    <div class="store-footer">
                                        <a href="{{route('storebrand.get',['mallId'=> $mall->id , 'departmentId'=> 'all'])}}">{{$mall->name_en}}</a>
                                    </div>
                                </div>
                            @endforeach
                            <!-- <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/zara.jpg">
                                <div class="store-footer">
                                    <a href="#">ZARA</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/zara.jpg">
                                <div class="store-footer">
                                    <a href="#">ZARA</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/talis_full_logo.png">
                                <div class="store-footer">
                                    <a href="#">talis</a>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-4 col-xs-6 store">
                                <img src="images/masotti.png">
                                <div class="store-footer">
                                    <a href="#">Masotti</a>
                                </div>
                            </div> -->
                        </div>
                        
                    </div>
                    <div class="showall"><a href="{{route('getShowAll',['productsType' => 'stores'])}}" class="show-all">Show All</a><img src="icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Stores Section -->
    <!-- Start Best Selling Section -->
    <section class="best-selling">
        <div class="section-heading">
            <img src="icons/monetization_on-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Best Selling Products</h3>
        </div>
        <div class="main-container">
            <div class="products">
                <div class="row justify-content-center">
                    @foreach($bestSellerProducts as $product)
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <input type="hidden" id="product-id" class="product-id" value="{{$product->product->id}}">
                            <meta name="_token" content="{{ csrf_token() }}">
                            @if(count($product->product->users))
                                @foreach($product->product->users as $user)
                                    @if(!empty($user))
                                    <img src="icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                    @else
                                    <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                                    @endif
                                @endforeach
                            @else
                                <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            @endif
                            <img src="{{Storage::url('/storage/'.$product->product->photo)}}">
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
                                                <img src="icons/star-24px.svg" class="filter-yellow">
                                            @endfor
                                            @for($i=$evaluation->evaluation;$i<5;$i++)
                                                <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                            @endfor
                                        @endforeach
                                    @else
                                        <img src="icons/star_border-24px.svg" class="filter-yellow">
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <span class="rating-amount">(30)</span>
                                    @endif
                                    <!-- <input type="hidden" class="index-star" value="0">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span> -->
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                    @endforeach
                    <!-- <div class="col-md-2 col-sm-4 col-xs-6 product">
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
                        <img src="images/black-framed-eyeglasses-on-white-jacket-and-blue-denim-934070.jpg">
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
                        <img src="images/pexels-markus-spiske-191158.jpg">
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
                        <img src="images/gallery_1.jpg">
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
                        <img src="images/4.jpg">
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
                    </div> -->
                    
                </div>
            </div>
            <div class="showall"><a href="{{route('getShowAll',['productsType' => 'products-best-selling'])}}" class="show-all">Show All</a><img src="icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
        </div>
    </section>
    <!-- End Best Selling Section -->

    <!-- Start Just For You Section -->
    <section class="for-you" id="for-you">
        <div class="section-heading">
            <img src="icons/favorite-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Just For You</h3>
        </div>
        <div class="main-container">
            <div class="products">
                <div class="row justify-content-center">
                    @foreach($justForYouProduct as $product)
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                            <meta name="_token" content="{{ csrf_token() }}">
                            @if(count($product->users))
                                @foreach($product->users as $user)
                                    @if(!empty($user))
                                    <img src="icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                    @else
                                    <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                                    @endif
                                @endforeach
                            @else
                                <img src="icons/favorite-24px.svg" class="filter-fairouzi love">
                            @endif
                            <img src="{{Storage::url('/storage/'.$product->photo)}}">
                            <div class="product-details">
                                <a href="{{route('product.get',['productId'=>$product->id])}}" class="product-name">
                                    {{$product->name_en}}
                                </a>
                                @if(!empty($product->price_offer) && (time()-(60*60*24)) <= strtotime($product->offer_end_at))
                                <span class="price">{{$product->price_offer}} omr</span>
                                <span class="old-price"><del>{{$product->price}} omr</del></span>
                                <span class="discount">{{(int)((($product->price-$product->price_offer)/$product->price)*100)}}%</span>
                                @else
                                <span class="price">{{$product->price}} omr</span>
                                @endif
                                <div class="rating">
                                    <input type="hidden" class="index-star" value="0">
                                    @if(count($product->evaluationUsers))
                                        @foreach($product->evaluationUsers as $evaluation)
                                            @for($i=1;$i<=$evaluation->evaluation;$i++)
                                                <img src="icons/star-24px.svg" class="filter-yellow">
                                            @endfor
                                            @for($i=$evaluation->evaluation;$i<5;$i++)
                                                <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                            @endfor
                                        @endforeach
                                    @else
                                        <img src="icons/star_border-24px.svg" class="filter-yellow">
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                        <span class="rating-amount">(30)</span>
                                    @endif
                                    <!-- <input type="hidden" class="index-star" value="0">
                                    <img src="icons/star-24px.svg" class="filter-yellow">
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span> -->
                                </div>
                                <img src="icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                    @endforeach
                    <!-- <div class="col-md-2 col-sm-4 col-xs-6 product">
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
                        <img src="images/black-framed-eyeglasses-on-white-jacket-and-blue-denim-934070.jpg">
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
                        <img src="images/pexels-markus-spiske-191158.jpg">
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
                        <img src="images/gallery_1.jpg">
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
                        <img src="images/4.jpg">
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
                    </div> -->
                    
                </div>
            </div>
            <div class="showall"><a href="{{route('getShowAll',['productsType' => 'products-just-for-you'])}}" class="show-all">Show All</a><img src="icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
        </div>
    </section>
    <!-- End Just For You Section -->
    <!-- Start Side Bars -->
    <div class="links-bar">
        <a href="#card-nav" data-container="body" data-toggle="popover" data-placement="right" data-content="Shopping card" data-trigger="hover"><img src="icons/shopping_cart-24px.svg" class="filter-fairouzi"></a>
        <a href="#products-sale" data-container="body" data-toggle="popover" data-placement="right" data-content="Products with sale" data-trigger="hover"><img src="icons/money_off-24px.svg" class="filter-fairouzi"></a>
        <a href="#stores-section" data-container="body" data-toggle="popover" data-placement="right" data-content="Stores" data-trigger="hover"><img src="icons/apartment-24px.svg" class="filter-fairouzi"></a>
        <a href="#for-you" data-container="body" data-toggle="popover" data-placement="right" data-content="Just for you" data-trigger="hover"><img src="icons/favorite-24px.svg" class="filter-fairouzi"></a>
    </div>
    <div class="social-bar">
        <a href="" data-container="body" data-toggle="popover" data-placement="right" data-content="Facebook" data-trigger="hover"><img src="icons/pngegg%20(3).png" class="filter-fairouzi"></a>
        <a href="" data-container="body" data-toggle="popover" data-placement="right" data-content="Twitter" data-trigger="hover"><img src="icons/pngegg.png" class="filter-fairouzi"></a>
        <a href="" data-container="body" data-toggle="popover" data-placement="right" data-content="Instagram" data-trigger="hover"><img src="icons/pngegg%20(1).png" class="filter-fairouzi"></a>
        <a href="" data-container="body" data-toggle="popover" data-placement="right" data-content="Mail" data-trigger="hover"><img src="icons/pngegg%20(2).png" class="filter-fairouzi"></a>
    </div>
    <!-- End Side Bars -->
    <!-- Start Mail Us -->
    <div class="collections-bottom">

        <div class="collections-bottom-grids">
            <div class="collections-bottom-grid">
                <h3>What you want <span>you'll find it!</span></h3>
            </div>
        </div>
	</div>
    <!-- End Mail Us -->
    <!-- Start Footer -->
    <div class="footer">
        <div class="container">
            <div class="footer-grids row">
                <div class="col-md-9 col-xs-6 footer-grid">
                    <h3>Bazar Al-Seeb</h3>
                    <p>Grand Shopping Mall In<span class="locate-footer"><img src="{{url('/')}}/images/oman-flag-3d-icon-16.png">Seeb, Oman</span></p>
                </div>
                <div class="col-md-3 col-xs-6 footer-grid">
                    <h3>Contact Info</h3>
                    <ul class="list-unstyled">
                        <li><img src="icons/location_on-24px.svg" class="filter-white">Seeb, Oman</li>
                        <li><img src="icons/pngegg%20(2).png" class="filter-white"><a href="mailto:info@example.com">bazaralseeb@gmail.com</a></li>
                        <li><img src="icons/call-24px.svg" class="filter-white">+968 9405 6359</li>
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
    <!-- End Footer -->

    <!--ajax in seeb.js-->
     <script >
        //$(document).ready(function () {
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
    <script src="{{url('/')}}/js/popper.js"></script>
    <script src="{{url('/')}}/js/bootstrap.min.js"></script>
    <script src="{{url('/')}}/js/bootstrap.bundle.min.js"></script>
    <script src="{{url('/')}}/package/js/swiper.min.js"></script>
    <script src="{{url('/')}}/js/jquery.nicescroll.min.js"></script>
    <script src="{{url('/')}}/js/seeb.js"></script>
    <script>
      
        var currentDate = $('.current_date').val();
        var endDate = $('.end_date').val();


        let [currentJustDate, currentJustTime] = currentDate.split(' ');
        let [currentDateYears, currentDateMonths , currentDatedays] = currentJustDate.split('-');
        let [currentH,currentM,currentS] = currentJustTime.split(':');
        var currentTime = new Date(currentDateYears,currentDateMonths-1,currentDatedays,currentH,currentM,currentS);

        let [endJustDate, endJustTime] = endDate.split(' ');
        let [endDateYears, endDateMonths , endDatedays] = endJustDate.split('-');
        let [endH,endM,endS] = endJustTime.split(':');
        var endTime = new Date(endDateYears,endDateMonths-1,endDatedays,endH,endM,endS);
        

      // // Update the count down every 1 second
      var x = setInterval(function() {
         var today = new Date();
         var todayYear = today.getUTCFullYear();
         var todayMonth = today.getUTCMonth();
         var todayDay = today.getUTCDate();
         var todayHours = today.getUTCHours();
         var todayMinutes = today.getUTCMinutes();
         var todaySeconds = today.getUTCSeconds();
         //+4 hours because we need the time in muscat and its UTC+4
         var todayUTC = new Date(todayYear,todayMonth,todayDay,todayHours+4,todayMinutes,todaySeconds);
        // todayUTC.setHours(todayUTC.getHours());
        // todayUTC.setMinutes(todayUTC.getMinutes());
        // todayUTC.setSeconds(todayUTC.getSeconds());
        

        // Find the distance between d and the count down date
        var distance = endTime.getTime() - todayUTC.getTime();

        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        var days = Math.floor(distance / (1000 * 3600 * 24));
        //alert(days+' '+hours+' '+minutes+' '+seconds);
        
        // Display the result in the element with id="demo"
        if(days>0){
            document.getElementById("ending_days").style.visibility = "visible";
            document.getElementById("end_d").style.visibility = "visible";
            document.getElementById("ending_days").innerHTML = days;
            //document.getElementById("ending_days").style.visibility = "hidden";

        }
            
        document.getElementById("ending_hours").innerHTML = hours;
        document.getElementById("ending_minutes").innerHTML = minutes;
        document.getElementById("ending_seconds").innerHTML = seconds;

        // If the count down is finished, write some text

        if (distance < 0) {
          clearInterval(x);
          // document.getElementById("value").innerHTML = "EXPIRED";
          // location.replace("/finish");
        }
      }, 1000);
    </script>
</body>
</html>
@endsection