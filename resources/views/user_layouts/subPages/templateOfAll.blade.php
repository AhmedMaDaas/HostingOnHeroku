@extends("user_layouts.navbar")
@section("headr")
    <!DOCTYPE html>
    <html>
    <head>
        @include('user_layouts.separatedCss',['title'=>'All Products'])
        <!-- <meta charset="utf-8">
        <title>Products With Sale</title>
        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/hover-min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css"> -->
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
                            <img src="{{url('/storage/'.$ad->photo)}}" class="d-block w-100" alt="...">
                        </a>
                    </div>
                @endforeach
          </div>
    </div>
    <!-- End Ads Carousel -->
    
    @yield("supPage")

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
                        @include('user_layouts.separatedProduct',['product'=>$product])
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
    <script src="{{url('/')}}/js/serviceLoginAjax.js"></script>
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
                    }else {
                        alert(response.message);
                    };     
                    
                  },
                  error: function (response) {
                    alert("error ");
                    //location.href="";
                  },
             });
        });

        // function showMoreStoresByDefinedDep(){
        //     $.ajax({
        //           url: ""+showWhat,
        //           type: 'POST',
        //           data: {
        //             '_token' :"{{csrf_token()}}",
        //             'skip' : skip,
        //           }, 
        //           dataType: 'json',
        //           success: function (response) {
        //             if(response.operation == 'success'){
        //                 $('.replace_here').replaceWith(response.view);
        //                 $('.skip').val(response.skip);
        //             }else {
        //                 alert(response.message);
        //             };     
                    
        //           },
        //           error: function (response) {
        //             alert("error ");
        //             //location.href="";
        //           },
        //      });
        // }
    
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
                    }else {
                        alert(response.message);
                    };     
                    
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