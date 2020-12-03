@extends("user_layouts.subPages.templateOfAll")
@section('supPage')

<!-- Start with Sale Section -->

@if(count($productsWithSale))
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
                            @include('user_layouts.separatedProduct',['product'=>$product])
                        @endforeach

                        <div class="replace_here"></div>
                    </div>
                </div>
                <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
            </div>
        </div>
    </section>

@endif

<!--  End With Sale Product  -->

@endsection