@extends("user_layouts.subPages.templateOfAll")
@section('supPage')

@if(count($productsBestSellingByDep))
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
                                @include('user_layouts.separatedProduct',['product'=>$product->product])
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
@endif

@endsection