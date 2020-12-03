 <div class="modal-body">
 <!--Stores -->
        @if($empty)
                <div class="alert alert-warning">
                  <strong>:(</strong> there is no any result matched try agine.
                </div>
        @else
            @foreach($searchResult as $key=>$element)
                @if($key == 'products' && count($element))
                <!-- Products -->
                    <div class="search-header-container">
                        <span class="search-header">Products</span>
                    </div>
                    <div class="products">
                        <div class="row justify-content-center">
                            @foreach($element as $product)
                                @include('user_layouts.separatedProduct',['product'=>$product])
                                <!-- <div class="col-md-2 col-sm-4 col-xs-6 product">
                                    <input type="hidden" id="product-id" class="product-id" value="{{$product->id}}">
                                    <meta name="_token" content="{{ csrf_token() }}">
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
                                    </div>
                                </div> -->
                            @endforeach
                            
                        </div>
                    </div>
                  
                <hr>
                @endif
                @if($key == 'departments' && count($element))
                    <div class="search-header-container">
                        <span class="search-header">Categories</span>
                    </div>
                    <div class="cats">
                            <div class="row justify-content-center">
                                @foreach($element as $department)
                                    <div class="col-md-6 col-sm-4 col-xs-6 cat">
                                        <img src="{{Storage::url('/storage/'.$department->photo)}}" class="filter-gray">
                                        <div class="store-footer">
                                            <a href="{{route('getShowAll',['productsType' => 'products-best-selling'])}}">{{$department->name_en}}</a>
                                        </div>
                                    </div>
                                @endforeach
                                
                            </div>
                            
                        </div> 
                      <hr>
                @endif
                @if($key == 'malls' && count($element))
                    <div class="search-header-container">
                        <span class="search-header">Stores</span>
                    </div>
                    <div class="stores">
                                <div class="row justify-content-center">
                                    @foreach($element as $mall)
                                        @include('user_layouts.separatedStore',['mall'=>$mall,'departmentId'=>'all'])
                                        <!-- <div class="col-md-6 col-sm-4 col-xs-6 store">
                                            <img src="{{Storage::url('/storage/'.$mall->icon)}}">
                                            <div class="store-footer">
                                                <a href="{{route('storebrand.get',['mallId'=>$mall->id,'departmentId'=>'all'])}}">{{$mall->name_en}}</a>
                                            </div>
                                        </div> -->
                                    @endforeach
                                    
                                </div>
                                
                        </div>
                @endif
                
                
            @endforeach
        @endif
</div>
