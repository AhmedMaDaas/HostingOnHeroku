<div class="col-md-2 col-sm-4 col-xs-6 product">
    <a href="{{route('product.get',['productId'=>$product->id])}}" class="fill-link"></a>
    <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
    <meta name="_token" content="{{ csrf_token() }}">
    @if(count($product->users))
        @foreach($product->users as $user)
            @if(!empty($user))
            <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-orange love" style="opacity: 100%;">
            @else
            <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love" data-toggle="modal" data-target="#login-modal">
            @endif
        @endforeach
    @else
        <img src="{{url('/')}}/icons/favorite-24px.svg" class="filter-fairouzi love" data-toggle="modal" data-target="#login-modal">
    @endif
    <img src="{{url('/storage/'.$product->photo)}}">
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
        <span class="old-price">&nbsp;</span>
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
                <span class="rating-amount">(30)</span>
            @else
                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow">
                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
                <span class="rating-amount">(30)</span>
            @endif
            <!-- <input type="hidden" class="index-star" value="0">
            <img src="{{url('/')}}/icons/star-24px.svg" class="filter-yellow">
            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
            <img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-yellow"> 
            <span class="rating-amount">(30)</span> -->
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
        <img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-fairouzi shopping-card" data-toggle="modal" data-target="#login-modal"s>
    </div>
</div>