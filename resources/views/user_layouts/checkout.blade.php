@extends("user_layouts.navbar")
@section("headr")
  <!DOCTYPE html>
  <html>
  <head>
      @include('user_layouts.separatedCss',['title'=>'CheckOut'])
      <!-- <meta charset="utf-8">
      <title>CheckOut</title>

      <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
      <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
      <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
      <link rel="stylesheet" href="{{url('/')}}/css/hover-min.css">
      <link rel="stylesheet" href="{{url('/')}}/css/seeb.css"> -->
      <link rel="stylesheet" href="{{url('/')}}/css/checkout.css">
             
  </head>
  <body>
@endsection

@section('content')

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </ul>

</div>
@endif

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

<div class="succesed alert-success">
    
</div>

<div class="failed alert-danger">
    
</div>
 
<div class="container-fluid checkout">
    <div class="buy">
          <div class="select">
              <p class="selectall"><img src="{{url('/')}}/images/double-tick-indicator.png">Select All</p>
              <span>|</span>
              <p class="delet"><img src="{{url('/')}}/images/trash-bin.png">Delete</p>
          </div>
          <?php $var = 0?>
          @if(!empty($bill))
          @foreach($bill->products as $index=>$billProduct)
            @if($var != $billProduct->mall->id)
              @if($var != 0)
                   </ul>
               </section>
              @endif
              <section class="store">
              <div class="check">
                <input type="checkbox" class="checkbox">
                <label>{{$billProduct->mall->name_en}}</label>
              </div>
                  
              <ul class="list-unstyled list">
            @endif
            <?php
            
               
            if(!empty($billProduct->color))$colorId = $billProduct->color->id;
            else $colorId = 0;
            if(!empty($billProduct->size))$sizeId = $billProduct->size->id;
            else $sizeId = 0;
             ?>
               <li class="media">
                  <input type="checkbox" name="products" value="{{$billProduct->product->id.'/'.$colorId.'/'.$sizeId}}">
                  <img class="mr-3" src="{{Storage::url('/storage/'.$billProduct->product->photo)}}" alt="Generic placeholder image">
                    <div class="media-body">
                        <h6 class="mt-0 mb-1 description"><a href="{{route('product.get',['productId'=>$billProduct->product->id])}}">{{$billProduct->product->name_en}}</a></h6>
                        @if(!empty($billProduct->color))
                        <div class="t"> <p>Color:</p> {{$billProduct->color->name_en}} </div>
                        @endif
                        @if(!empty($billProduct->size))
                        <div class="t"><p>Size:</p><span>{{$billProduct->size->name_en}}</span></div>
                        @endif
                        <div class="t"><p>quantity:</p><span>{{$billProduct->quantity}}</span></div>
                        <input type="hidden" id="product-id" class="product-id" value="{{$billProduct->product->id}}">
                        <meta name="_token" content="{{ csrf_token() }}">
                        <div class="rating">
                          <input type="hidden" class="index-star" value="0">
                          @if(count($billProduct->product->evaluationUsers))
                              @foreach($billProduct->product->evaluationUsers as $evaluation)
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
                     </div>
                     <div class="totalprice">
                        @if(!empty($billProduct->product->price_offer) && (time()-(60*60*24)) <= strtotime($billProduct->product->offer_end_at))
                        <p class="price">{{$billProduct->product->price_offer}} omr</p>
                        <p class="old-price"><del>{{$billProduct->product->price}} omr</del></p>
                        <p class="discount">{{(int)((($billProduct->product->price-$billProduct->product->price_offer)/$billProduct->product->price)*100)}}%</p>
                        @else
                        <p class="price">{{$billProduct->product->price}} omr</p>
                        @endif
                     </div>
                </li>
                  
              @if($index==count($bill->products)-1)
               </ul>
             </section>
             @endif
             <?php $var = $billProduct->mall->id ?>
           @endforeach
           @endif
    </div>
  <div class="invoice">
    <form class="totalinvoice" method="post">
        {{csrf_field()}}
        <!-- <div class="totalinvoice"> -->
             <h4><img src="{{url('/')}}/images/invoice%20(2).png">Invoice Detail</h4>
        		 <span class="line"></span>
        	 	 <!-- <div class="totalcost">
            	   <span><img src="{{url('/')}}/images/dollar%20(3).png">Total Cost Before Sale</span>
            		 <span><del>100.00 omr</del></span>
        	   </div> -->

        	   <!-- <div class="totaldiscount">
            	   <span><img src="{{url('/')}}/images/dollar%20(3).png">Total Discount</span>
            		 <span>50.00 omr</span>
        	   </div> -->
        		
          	 <div class="total">
          	 	 <span><img src="{{url('/')}}/images/dollar%20(1).png">Total</span>
          		 <span>{{$total_coast}} omr</span>
          		 <p><span>({{$sumQuantity}})</span>item</p>
          	 </div>

          	 <!-- <div class="code">
            	   <input type="text" placeholder="code">
            		 <button class="apply">Apply</button>
          	 </div> -->

        	 	 <span class="line"></span>
             <h5 class="location">Your Location</h5>
             <input type="text" name="country" placeholder="Country">
             <input type="text" name="city" placeholder="city">
             <input type="text" name="street" placeholder="street">
             <div class="bb">
               <button class="confirm" name="cash" tybe="submit">Cash pay</button>  
               <button class="confirmcash" name="paybal" tybe="submit">paybal pay</button>
             </div>
        	   <!-- <button class="confirm" name="confirm" tybe="submit">Confirm</button> -->
          
       
        <!-- </div> -->
    </form>

        <div class="productmaylike">
          <p class="likee"><img src="{{url('/')}}/images/ok-like-hand-sign.png">Product May Like</p>
      	   <span class="lline"></span>
           @foreach($relatedProducts as $product)
            @include('user_layouts.separatedProduct',['product'=>$product])
             <!-- <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 product">
                  <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                  <meta name="_token" content="{{ csrf_token() }}">
                  <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love">
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
</div>
    
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
<script src="{{url('/')}}/js/serviceLoginAjax.js"></script>
<script src="{{url('/')}}/js/checkout.js"></script>
<script>
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

<script >
$(".delet").click(function(){

    var button = 'delete';
    var products = [];
    $.each($("input[name='products']:checked"), function(){
        products.push($(this).val());
    });

     $.ajax({
        url: "{{route('check.post')}}",
        type: 'POST',
        data: {
          '_token' :"{{csrf_token()}}",
          'products': products,
          'button': button,
        }, 
        dataType: 'json',
        success: function (response) {
          if(response.operation == 'succesed'){
            $('.'+response.operation).text('deleted succesfully');
            location.href="{{route('check.get')}}";
          }else{
            $('.'+response.operation).text('deleted failed');
          }
            
        },
        error: function (response) {
          alert("error ");
          //location.href="{{route('check.get')}}";
          //location.href="";
        },
      });

});

</script>

</body>
</html>
@endsection