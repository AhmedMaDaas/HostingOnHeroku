@extends("user_layouts.subPages.templateOfAll")
@section('supPage')

@if(count($malls))
<!-- Start Stores Section -->
  <section class="stores-page-section">
      <input type="hidden" class="skip" value="{{count($malls)}}">
      <div class="section-heading">
          <img src="{{url('/')}}/icons/store_mall_directory-24px.svg" class="section-icon filter-gray">
          <h3 class="section-header">Stores</h3>
      </div>
      <div class="stores-div">
          <div class="container">
              <div class="sub-container">
                    <!-- Swiper -->
                <div class="swiper-container">
                  <div class="swiper-wrapper">
                    @foreach($departmentsParents as $mainId => $parent)
                            @foreach($parent->child as $sub)
                                <a href="{{route('getStoreByDepartment',['departmentId' => $sub->id])}}"><div class="swiper-slide"><img src="{{url('/storage/'.$sub->icon)}}" class="filter-orange"><span>{{$sub->name_en}}</span></div></a>
                            @endforeach
                        @endforeach

                        @foreach($subDepartmentWithoutParent as $sub)
                            <a href="{{route('getStoreByDepartment',['departmentId' => $sub->id])}}"><div class="swiper-slide"><img src="{{url('/storage/'.$sub->icon)}}" class="filter-orange"><span>{{$sub->name_en}}</span></div></a>
                        @endforeach
                    <!-- <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/shopping_cart-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/check_circle-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_dining-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/local_fire_department-24px.svg" class="filter-orange"><span>Restaurant</span></div>
                    <div class="swiper-slide"><img src="{{url('/')}}/icons/star_border-24px.svg" class="filter-orange"><span>Restaurant</span></div> -->
                  </div>
                  <div class="swiper-button-next"></div>
                  <div class="swiper-button-prev"></div>
                </div>
                  <div class="stores">
                      <div class="row justify-content-center">
                          @foreach($malls as $mall)
                              @include('user_layouts.separatedStore',['mall'=>$mall,'departmentId'=>'all'])
                              <!-- <div class="col-md-6 col-sm-4 col-xs-6 store">
                                  <img src="{{url('/storage/'.$mall->icon)}}">
                                  <div class="store-footer">
                                      <a href="{{route('storebrand.get',['mallId'=>$mall->id,'departmentId'=>'all'])}}">{{$mall->name_en}}</a>
                                  </div>
                              </div> -->
                          @endforeach

                          <div class="replace_here"></div>
                          
                      </div>
                      
                  </div>
                  <div class="showall" id="showall"><span class="show-all">Show More</span><img src="{{url('/')}}/icons/keyboard_arrow_right-24px.svg" class="filter-fairouzi right-arrow"></div>
              </div>
          </div>
      </div>
  </section>
  <!-- End Stores Section -->

@endif

@endsection