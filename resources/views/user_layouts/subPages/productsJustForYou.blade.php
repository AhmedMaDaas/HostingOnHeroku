@extends("user_layouts.subPages.templateOfAll")
@section('supPage')

@if(count($productsJustForYou))
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
                            @include('user_layouts.separatedProduct',['product'=>$product])
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

@endsection