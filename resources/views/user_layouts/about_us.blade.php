@extends("user_layouts.navbar")
@section("headr")
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>About Us</title>
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
    <!-- Start About Heading Section -->
    <section class="about-heading-section" style="{{ Storage::has($websiteInfo->main_photo) ? 'background-image: url(' . Storage::url('/storage/' . $websiteInfo->main_photo) .');' : '' }}">
        <div class="container-cover">
            <div class="about-container-content">
                <h1>BAZAR ALSEEB</h1>
                <p>Grand Shopping Mall In Seeb,Oman</p>
            </div>
        </div>
    </section>
    <!-- End About Heading Section -->
    <!-- Start General Demo Carousel -->
    <!-- Swiper -->
    <section class="demo-slider-section">
        <div class="swiper-container general-demo-swiper">
        <div class="swiper-wrapper">

            @foreach($websiteInfo->attrInfo as $attr)
                <div class="swiper-slide" style="background-image:url({{ Storage::url('/storage/' . $attr->photo) }})"><div class="text-container"><p>{{ $attr->title }}</p></div></div>
            @endforeach
            
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
      </div>
    </section>
    <!-- End General Demo Carousel -->
    <!-- Start Info Section -->
    <section class="info-section">
        <div class="info-text">
            <h4><img src="{{ url('/') }}/icons/info-24px.svg" class="filter-gray">About Bazar Al-Seeb</h4>
            <p class="lead">
                {{ $websiteInfo->web_desc }}
            </p>
            <div class="contact-us">
                <a href="{{ isset($websiteInfo->instagram) ? $websiteInfo->instagram : '#' }}"><img src="{{ url('/') }}/icons/pngegg%20(1).png" class="filter-gray"></a>
                <a href="{{ isset($websiteInfo->email) ? $websiteInfo->email : '#' }}"><img src="{{ url('/') }}/icons/pngegg%20(2).png" class="filter-gray"></a>
                <a href="{{ isset($websiteInfo->facebook) ? $websiteInfo->facebook : '#' }}"><img src="{{ url('/') }}/icons/pngegg%20(3).png" class="filter-gray"></a>
                <a href="{{ isset($websiteInfo->twitter) ? $websiteInfo->twitter : '#' }}"><img src="{{ url('/') }}/icons/pngegg.png" class="filter-gray"></a>
            </div>
        </div>
        <div class="info-img">
            @if(isset($websiteInfo->desc_photo))
            <img src="{{ Storage::url('/storage/' . $websiteInfo->desc_photo) }}">
            @endif
        </div>
    </section>
    <!-- End Info Section -->
    <!-- Start Footer -->
    <div class="footer">
        <div class="container">
            <div class="footer-grids row">
                <div class="col-md-9 col-xs-6 footer-grid">
                    <h3>Bazar Al-Seeb</h3>
                    <p>Grand Shopping Mall In<span class="locate-footer"><img src="{{ url('/') }}/images/oman-flag-3d-icon-16.png">Seeb, Oman</span></p>
                </div>
                <div class="col-md-3 col-xs-6 footer-grid">
                    <h3>Contact Info</h3>
                    <ul class="list-unstyled">
                        <li><img src="{{ url('/') }}/icons/location_on-24px.svg" class="filter-white">Seeb, Oman</li>
                        <li><img src="{{ url('/') }}/icons/pngegg%20(2).png" class="filter-white"><a href="mailto:info@example.com">bazaralseeb@gmail.com</a></li>
                        <li><img src="{{ url('/') }}/icons/call-24px.svg" class="filter-white">+968 9405 6359</li>
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
    <script src="{{ url('/') }}/js/jquery-3.3.1.min.js"></script>
    <script src="{{ url('/') }}/js/popper.js"></script>
    <script src="{{ url('/') }}/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('/') }}/package/js/swiper.min.js"></script>
    <script src="{{ url('/') }}/js/jquery.nicescroll.min.js"></script>
    <script src="{{ url('/') }}/js/seeb.js"></script>
</body>
</html>
@endsection