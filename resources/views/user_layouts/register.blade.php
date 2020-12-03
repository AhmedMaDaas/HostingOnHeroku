@extends("user_layouts.navbar")
@section("headr")
    <!DOCTYPE html>
    <html>
    <head>
        @include('user_layouts.separatedCss',['title'=>'Register'])
        <!-- <meta charset="utf-8">
        <title>Register</title>
        <link rel="stylesheet" href="{{url('/')}}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/normalize.css">
        <link rel="stylesheet" href="{{url('/')}}/package/css/swiper.min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/hover-min.css">
        <link rel="stylesheet" href="{{url('/')}}/css/seeb.css"> -->
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
    <div class="register-container">
        <div class="register-form-container">
            <div class="form-img">
                <img src="{{url('/')}}/icons/person_add_alt_1-24px.svg" class="filter-orange">
            </div>
            <h4 class="form-heading">Register</h4>
            <form class="register-form"  method="post">
                {{csrf_field()}}
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/person-24px.svg" class="filter-orange"></span>
                  </div>
                  <input type="text" name="fname" class="form-control" placeholder="First Name" value="{{old('fname')}}" required>
                </div>
                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/people_alt-24px.svg" class="filter-orange"></span>
                  </div>
                  <input type="text" name="lname" class="form-control" placeholder="Last Name" value="{{old('lname')}}" required>
                </div>
                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/pngegg%20(2).png" class="filter-orange"></span>
                  </div>
                  <input type="email" name="email" class="form-control" placeholder="Email" value="{{old('email')}}" required>
                </div>
                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                  </div>
                  <input type="password" name="password" class="form-control" placeholder="Password" value="{{old('password')}}" required>
                </div>
                
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                  </div>
                  <input type="password" name="confirmpassword" class="form-control" placeholder="Password Confirmation" value="{{old('confirmpassword')}}" required>
                </div>

                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><img src="{{url('/')}}/icons/lock-24px.svg" class="filter-orange"></span>
                  </div>
                  <input type="phone" name="phone" class="form-control" placeholder="phone" value="{{old('phone')}}" required>
                </div>
                <input type="submit" class="form-btn" value="Register">
            </form>
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