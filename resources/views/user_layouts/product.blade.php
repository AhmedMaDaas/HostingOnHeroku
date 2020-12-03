@extends("user_layouts.navbar")
@section("headr")
    <!DOCTYPE html>
    <html>
    <head>
        @include('user_layouts.separatedCss',['title'=>'Product'])
        <!-- <meta charset="utf-8">
        <title>Product</title>

        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}css/hover-min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css"> -->
        <link rel="stylesheet" href="{{url('/')}}/css/flexslider.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="{{url('/')}}/css/product.css">
               
    </head>
    <body>
@endsection

@section("content") 
    @if(Session::has('success'))
      <div class="alert alert-success">
        {{ Session::get('success') }}
       </div>
    @endif

    @if(Session::has('failed'))
      <div class="alert alert-danger">
        {{ Session::get('failed') }}
       </div>
    @endif
<form method="post">
    {{csrf_field()}}
 <section class="productsingle">
     <div class="productpic">
	<div class="single">
		<div class="container">
			<div class="col-md-12 single-right">
				<div class="col-md-12 single-right-left">
					<div class="flexslider">
						<ul class="slides">

							<li data-thumb="{{Storage::url('/storage/'.$product->photo)}}">
								<div class="thumb-image"> <img src="{{Storage::url('/storage/'.$product->photo)}}" data-imagezoom="true" class="img-responsive"> </div>
							</li>
                            @if(count($product->files))
                                @foreach($product->files as $file)
                                <li data-thumb="{{Storage::url('/storage/'.$file->fullFile)}}">
                                     <div class="thumb-image"> <img src="{{Storage::url('/storage/'.$file->fullFile)}}" data-imagezoom="true" class="img-responsive"> </div>
                                </li>
                                @endforeach
                            @endif
							<!-- <li data-thumb="{{url('/')}}/images/si1.jpg">
								 <div class="thumb-image"> <img src="{{url('/')}}/images/si1.jpg" data-imagezoom="true" class="img-responsive"> </div>
							</li>
							<li data-thumb="{{url('/')}}/images/si2.jpg">
							   <div class="thumb-image"> <img src="{{url('/')}}/images/si2.jpg" data-imagezoom="true" class="img-responsive"> </div>
							</li> --> 
						</ul>
					</div>
				</div>
				
			</div>
		</div>
    </div>

        <div class="options">
            <h3 class="productname">{{$product->name_en}}</h3>
            <p class="description">{{$product->content}}</p>
            <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
            <meta name="_token" content="{{ csrf_token() }}">

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
                     <!-- <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                     <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                     <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                     <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                     <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                     <span class="rating-amount">(30)</span> -->
                     <span>Ratings</span>
            </div>

            @if(!empty($product->price_offer) && (time()-(60*60*24)) <= strtotime($product->offer_end_at))
            <span class="price">{{$product->price_offer}} omr</span>
            <span class="old-price"><del>{{$product->price}} omr</del></span>
            @else
            <span class="price">{{$product->price}} omr</span>
            @endif
            
            <!-- <span class="price">120.00 omr</span>
            <span class="old-price"><del>150.00 omr</del></span> -->

            
            @if(count($product->colors))
                <p class="colorfamily">Color Family</p>
                <div class="colorpic">
                    @foreach($product->colors as $color)
                        <button class="{{$color->color->name_en}}"><input type="radio" name="color" value="{{$color->color->id}}" hidden></button>
                    @endforeach
                    <!-- <button class="red"><input type="radio" name="h" hidden></button>
                    <button class="blue"><input type="radio" name="h" hidden></button>
                    <button class="green"><input type="radio" name="h" hidden></button>
                    <button class="gray"><input type="radio" name="h" hidden></button> -->
                </div>
            @else
                <span class="old-price">&nbsp;</span>
                <span class="old-price">&nbsp;</span>
            @endif
            
            <!-- <div class="colorpic">
                
                <img src="{{url('/')}}/images/si.jpg">
                <img src="{{url('/')}}/images/si1.jpg">
                <img src="{{url('/')}}/images/si2.jpg">
            </div> -->
            @if(count($product->sizes))
                <div class="sizeoption">
                    <p class="size">Size</p>
                    @foreach($product->sizes as $size)
                        <button value="1"><input type="radio" name="size" value="{{$size->size->id}}" hidden>{{$size->size->name_en}}</button>
                     @endforeach
                    <!-- <button value="2"><input type="radio" name="h" hidden>M</button>
                    <button value="3"><input type="radio" name="h" hidden>L</button>
                    <button value="4"><input type="radio" name="h" hidden>XL</button> -->
                </div>
            @else
                <span class="old-price">&nbsp;</span>
                <span class="old-price">&nbsp;</span>
            @endif
            
            <div class="quantityoption">
                <p class="quantity" >Quantity</p>
                <button value="1" type="button" class="value-minus">-</button>
                <input type="hidden" name="quantity" class="quantityvalInput" value="1">
                <span class="quantityval">1</span>
                <button value="2" type="button" class="value-plus">+</button>
            </div>
            <div class="button">
                <button class="buynow" name="buy">Buy Now</button>
                <button class="addtocart" name="add_product">Add To Cart</button>
            </div>

         </div>
        </div>
     
     <div class="sold">
        <h4>Sold By</h4>
         <div class="soldinfo">
         <img src="{{Storage::url('/storage/'.$mallProduct->mall->icon)}}">
             <div class="inffo">
                <input type="hidden" value="{{$mallProduct->mall->id}}" name="mall"> 
                  <p class="storename">{{$mallProduct->mall->name_en}}</p>
                  <span>{{$countFollowers}} Followers</span>
                  <a href="{{route('storebrand.get',['mallId'=>$mallProduct->mall->id,'departmentId'=>'all'])}}"><input type="button" onclick="{{route('storebrand.get',['mallId'=>$mallProduct->mall->id,'departmentId'=>'all'])}}';" value="Go to Store" /></a>
             </div>
         </div>
     </div>
</section>
</form>
     <h3 class="sec"><img src="{{url('/')}}/images/show-apps-button.png">Related Products</h3>
    <section class="products-sale" id="products-sale">
        <div class="main-container">
           
            <div class="products">
                <div class="row justify-content-center">

                    @foreach($relatedProducts as $product)
                        @include('user_layouts.separatedProduct',['product'=>$product])
                        <!-- <div class="col-md-2 col-sm-4 col-xs-6 product">
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
                            </div> -->
                    @endforeach
                    
                </div>
            </div>
            <div class="showall"><a href="{{route('storebrand.get',['mallId'=>$mallProduct->mall->id,'departmentId'=>'all'])}}" class="show-all">Show All</a><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
        </div>
    </section>
    <h3 class="sec2"><img src="{{url('/')}}/images/show-apps-button.png">Product Information</h3>
    
    <section class="productinfo">
        @if(count($table) > 0)
            <table class="tableinfo">
                @foreach($table as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td contenteditable='true' rowspan="{{ $cell['rowspan'] }}" colspan="{{ $cell['colspan'] }}">{{ $cell['text'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
           </table>
        @endif
        <form class="Question" method="post">
            {{csrf_field()}}
            <label><img src="{{url('/')}}/images/question-mark-on-a-circular-black-background%20(1).png">Question?</label>
            <input type="text" name="question" placeholder="Type your question">

            <button class="add" name="add_question">Add</button>

            <span></span>
            <label><img src="{{url('/')}}/images/question-mark-on-a-circular-black-background%20(1).png">Question For People & Comment</label>
            <textarea name="commint"></textarea>

             <button class="add" name="add_commint">Add</button>
        
        </form>
        
    

    </section>
    
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
    <script src="{{url('/')}}/js/seeb.js"></script
    <script src="{{url('/')}}/js/serviceLoginAjax.js"></script>

    <script defer src="{{url('/')}}/js/jquery.flexslider.js"></script>
	<script src="{{url('/')}}/js/zoomsl.js"></script>
	<script src="{{url('/')}}/js/zoomsl.min.js"></script>
    <script src="{{url('/')}}/js/product.js"></script>
    <script>
        $('.value-plus').on('click', function(){
            var quantity = $('.quantityval').text();
            quantity = parseInt(quantity, 10)+1;
            $('.quantityval').text(quantity);
            $('.quantityvalInput').val(quantity);
            
        });

        $('.value-minus').on('click', function(){
            var quantity = $('.quantityval').text();
            quantity = parseInt(quantity, 10)-1;
            if(quantity < 1) quantity = 1;
            $('.quantityval').text(quantity);
            $('.quantityvalInput').val(quantity);
            

        });
    </script>
	

</body>
</html>
@endsection