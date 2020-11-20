@extends("user_layouts.navbar")
@section("headr")
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Log In</title>
        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/hover-min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css">
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
    <!-- Start Log In -->
    <div class="log-in-container">
        <div class="log-in-form-container">
            <div class="form-img">
                <img src="{{url('/')}}/icons/how_to_reg-24px.svg" class="filter-orange">
            </div>
            <h4 class="form-heading">Log In</h4>
            <form class="log-in-form" method="post">
                {{csrf_field()}}
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-orange"></span>
                  </div>
                  <input type="email" name="email" class="form-control" placeholder="Email" value="{{old('fname')}}">
                </div>
                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                  </div>
                  <input type="password" name="password" class="form-control" placeholder="Password">
                </div>
                <input type="submit" name="log" class="form-btn" value="Log In">
                <hr>
                <p class="form-explain">Or Log In With</p>
                <div class="social-login">
                    <button type="submit" name="facebook" class="alter-btn hvr-bounce-to-bottom hvr-grow"><div><img src="{{url('/')}}/icons/pngegg%20(3).png" class="filter-lightgray"></div>Facebook</button>
                    <button type="submit" name="google" class="alter-btn hvr-bounce-to-bottom hvr-grow"><div><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-lightgray"></div>Gmail</button>
                </div>
            </form>
            <hr>
            <p class="form-explain">If You Don't Have Account On Bazar Al-Seeb</p>
            <a href="{{route('reg')}}" class="register-now hvr-icon-forward">Register Now <img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow hvr-icon"></a>
        </div>
    </div>
    <!-- End Log In -->
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
</body>
</html>
@endsection