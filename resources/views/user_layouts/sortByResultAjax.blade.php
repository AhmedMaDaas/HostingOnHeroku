@if(count($productsByDep))
<?php $temp = 0;?>
	@foreach($productsByDep as $dep => $mallsProducts)
    @if(count($mallsProducts))
    <?php $temp++;?>
        <section class="products-sale" id="products-sale">
            <div class="main-container">
                <input type="hidden" class="category-name" value="{{$dep}}">
                <input type="hidden" class="skip" value="{{count($mallsProducts)}}">
                <h3 class="sec">{{$dep}}</h3>
                <div class="products">
                    <div class="row justify-content-center">
                        @foreach($mallsProducts as $dep => $product)
                            @if(count($mallsProducts))
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
                                    <img src="{{url('/storage/'.$product->product->photo)}}">
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
                                            <!-- <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                                            <span class="rating-amount">(30)</span> -->
                                        </div>
                                        <div class="stock-div">
                                            <span class="label">Stock</span>
                                            <div class="progress">
                                                @if($product->product->stock >= 100)
                                                <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                @else
                                                <div class="progress-bar" role="progressbar" style="width: {{($product->product->stock/100)*100}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                @endif
                                            </div>
                                        </div>
                                        <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card">
                                    </div>
                                </div>
                                @endif
                        @endforeach

                        <div class="replace_here"></div>
                        
                        
                    </div>
                </div>
                <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
            </div>
        </section>
        @endif
    @endforeach
    @if($temp == 0)
            <section class="products-sale" id="products-sale">
                <div class="main-container">
                    <div class="alert alert-warning">
                      <strong>:)</strong> no result matched please enter another data.
                    </div>
                </div>
            </section>
        @endif

@endif