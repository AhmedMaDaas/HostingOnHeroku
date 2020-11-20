@extends("user_layouts.navbar")
@section("headr")
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Products With Sale</title>
        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css">
    </head>
    <body>
@endsection
    <!-- Start Navbar -->

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
    
    
@if(count($productsWithSale))
    <!-- Start with Sale Section -->
    <section class="products-with-sale-section">
        <input type="hidden" class="skip" value="{{count($productsWithSale)}}">
        <div class="section-heading">
            <img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Products With Sale</h3>
        </div>
        <div class="best-container">
            <!-- <div class="time-container">
                <span class="label">Ending In</span>
                <span class="time">48</span>
                <span class="time">33</span>
                <span class="time">10</span>
            </div> -->
            <div class="best-sub-cont container">
                <div class="best-of-cat">
                    <div class="row justify-content-center best-sel-prod">
                        @foreach($productsWithSale as $product)
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                            <meta name="_token" content="{{ csrf_token() }}">
                            @if(count($product->users))
                                @foreach($product->users as $user)
                                    @if(!empty($user))
                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                    @else
                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                                    @endif
                                @endforeach
                            @else
                                <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
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
                                    <!-- <input type="hidden" class="index-star" value="0">
                                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
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
                                <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                    @endforeach

                    <div class="replace_here"></div>
                        
                    </div>
                </div>
                <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
            </div>
        </div>
    </section>
@elseif(count($malls))
<!-- Start Stores Section -->
    <section class="stores-page-section">
        <input type="hidden" class="skip" value="{{count($malls)}}">
        <div class="section-heading">
            <img src="{{url('/')}}/icons/store_mall_directory-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Stores</h3>
        </div>
        <div class="stores-div">
            <div class="container">
                <div class="sub-container">
                      <!-- Swiper -->
                  <div class="swiper-container">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                      <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                  </div>
                    <div class="stores">
                        <div class="row justify-content-center">
                            @foreach($malls as $mall)
                                <div class="col-md-6 col-sm-4 col-xs-6 store">
                                    <img src="{{Storage::url('/storage/'.$mall->photo)}}">
                                    <div class="store-footer">
                                        <a href="{{route('storebrand.get',['mallId'=>$mall->id,'departmentId'=>'all'])}}">{{$mall->name_en}}</a>
                                    </div>
                                </div>
                            @endforeach

                            <div class="replace_here"></div>
                            
                        </div>
                        
                    </div>
                    <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Stores Section -->
@elseif(count($productsBestSellingByDep))
<!-- Start Best Selling Section -->
    <section class="best-selling-section">
        
        <div class="section-heading">
            <img src="{{url('/')}}/icons/monetization_on-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Best Selling Products</h3>
        </div>
        <div class="best-container">
            <div class="best-sub-cont container">
                @foreach($productsBestSellingByDep as $dep=> $products)
                    @if(count($products))
                    <div class="best-of-cat"  id="best-of-cat">
                        <input type="hidden" class="category-name" value="{{$dep}}">
                        <input type="hidden" class="skip" value="{{count($products)}}">
                        <div class="best-of-cat-header-container">
                            <span class="best-of-cat-header" id="{{$dep}}">{{$dep}}</span>
                        </div>
                        <div class="row justify-content-center best-sel-prod">
                            @foreach($products as $product)
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
                                            <!-- <input type="hidden" class="index-star" value="0">
                                            <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <span class="rating-amount">(30)</span> -->
                                        </div>
                                        <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                                    </div>
                                </div>
                            @endforeach

                            <div class="replace_here"></div>

                        </div>
                        <div class="showall" ><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
                        <hr>
                    </div>
                    @endif
                @endforeach
                
            </div>
        </div>
    </section>
    <!-- End Best Selling Section -->
    <!-- Start Side Bars -->
    <div class="cats-side-bar">
        @foreach($productsBestSellingByDep as $dep=> $products)
            @if(count($products))
                <a href="#{{$dep}}" data-container="body" data-toggle="popover" data-placement="left" data-content="{{$dep}}" data-trigger="hover"><img src="{{url('/')}}/icons/checkroom-24px.svg" class="filter-gray"></a>
                <!-- <a href="#furnitures" data-container="body" data-toggle="popover" data-placement="left" data-content="Furnitures" data-trigger="hover"><img src="{{url('/')}}/icons/weekend-24px.svg" class="filter-gray"></a>
                <a href="#wear" data-container="body" data-toggle="popover" data-placement="left" data-content="Wear" data-trigger="hover"><img src="{{url('/')}}/icons/checkroom-24px.svg" class="filter-gray"></a>
                <a href="#furnitures" data-container="body" data-toggle="popover" data-placement="left" data-content="Furnitures" data-trigger="hover"><img src="{{url('/')}}/icons/weekend-24px.svg" class="filter-gray"></a> -->
            @endif
        @endforeach
    </div>
    <!-- End Side Bars -->
@elseif(count($productsJustForYou))
<!-- Start Just For You Section -->
    <section class="just-for-you-section">
        <input type="hidden" class="skip" value="{{count($productsJustForYou)}}">
        <div class="section-heading">
            <img src="{{url('/')}}/icons/favorite-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Just For You</h3>
        </div>
        <div class="best-container">
            <div class="best-sub-cont container">
                <div class="best-of-cat">
                    <div class="row justify-content-center best-sel-prod">
                        @foreach($productsJustForYou as $product)
                            <div class="col-md-2 col-sm-4 col-xs-6 product">
                                <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                                <meta name="_token" content="{{ csrf_token() }}">
                                @if(count($product->users))
                                    @foreach($product->users as $user)
                                        @if(!empty($user))
                                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                        @else
                                        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                                        @endif
                                    @endforeach
                                @else
                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
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
                                        <!-- <input type="hidden" class="index-star" value="0">
                                        <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                        <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                        <span class="rating-amount">(30)</span> -->
                                    </div>
                                    <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                                </div>
                            </div>
                        @endforeach

                        <div class="replace_here"></div>
                        
                    </div>
                    <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Just For You Section -->
@endif
@if(count($productsBestSellingByDep) || count($productsWithSale) || count($malls) )
    <section class="for-you" id="for-you">
        <div class="section-heading">
            <img src="{{url('/')}}/icons/favorite-24px.svg" class="section-icon filter-gray">
            <h3 class="section-header">Just For You</h3>
        </div>
        <div class="main-container">
            <div class="products">
                <div class="row justify-content-center">
                    @foreach($productsJustForYou as $product)
                        <div class="col-md-2 col-sm-4 col-xs-6 product">
                            <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                            <meta name="_token" content="{{ csrf_token() }}">
                            @if(count($product->users))
                                @foreach($product->users as $user)
                                    @if(!empty($user))
                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
                                    @else
                                    <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
                                    @endif
                                @endforeach
                            @else
                                <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
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
                                    <!-- <input type="hidden" class="index-star" value="0">
                                    <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                    <span class="rating-amount">(30)</span> -->
                                </div>
                                <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                            </div>
                        </div>
                    @endforeach
                    
                    
                </div>
            </div>
            <div class="showall"><a href="{{route('getShowAll',['productsType' => 'products-just-for-you'])}}" class="show-all">Show All</a><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
        </div>
    </section>
@endif
    <!-- End Just For You Section -->
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
     <!-- start show all -->
     <script>

         // click show all and access input hidden for category
         $(document).on('click',".best-selling-section .best-container .best-sub-cont .best-of-cat .show-all",function(e){
            var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var showWhat = array[array.length-1];
            //console.log($(this).parent().parent().find(".category-name").val());
            var thisVar = $(this);
            var departmentName = $(this).parent().parent().find(".category-name").val();
            var skip = $(this).parent().parent().find(".skip").val();

            $.ajax({
                  url: ""+showWhat,
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'skip' : skip,
                    'departmentName' : departmentName,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    if(response.operation == 'success'){
                        thisVar.parent().parent().find('.replace_here').replaceWith(response.view);
                        thisVar.parent().parent().find('.skip').val(response.skip);
                    }else {};     
                    
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
             });
        });
    
        $(document).on('click',"#showall",function(e){
            var currentLocation = window.location.pathname+'';
            array = currentLocation.split('/');
            var showWhat = array[array.length-1];

            var skip = $('.skip').val();

            $.ajax({
                  url: ""+showWhat,
                  type: 'POST',
                  data: {
                    '_token' :"{{csrf_token()}}",
                    'skip' : skip,
                  }, 
                  dataType: 'json',
                  success: function (response) {
                    if(response.operation == 'success'){
                        $('.replace_here').replaceWith(response.view);
                        $('.skip').val(response.skip);
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