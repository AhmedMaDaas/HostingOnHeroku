    <div class="sidebar" data-color="purple" data-background-color="black" data-image="{{ url('/admin_design') }}/assets/img/sidebar-2.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><img src="{{ url('/admin_design') }}/assets/img/Bazar%20AL%20Seeb%20final%20logo.png" class="simple-text logo-normal">
          
        </div>
     <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item active"> 
            <i class="material-icons">dashboard</i>
            <p class="nav-link">{{ trans('admin.main_navigation') }}</p>
            <ul style="{{ count(request()->segments()) == 2 ? activeList('home') . activeList('sales') : '' }}">
              <li class="{{ activeLink(url('mall-manager/home')) }}"> <a href="{{ url('mall-manager/home') }}">{{ trans('admin.dashboard') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/sales')) }}"> <a href="{{ url('/mall-manager') }}/sales">{{ trans('admin.sales') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">card_giftcard</i>
            <p class="nav-link">{{ trans('admin.adds_control') }}</p>
              <ul style="{{ activeList('adds') }}">
                  <li class="{{ activeLink(url('/mall-manager/adds')) }}"> <a href="{{ url('/mall-manager') }}/adds">{{ trans('admin.adds') }}</a></li>
                  <li class="{{ activeLink(url('/mall-manager/adds/create')) }}"> <a href="{{ url('/mall-manager') }}/adds/create">{{ trans('admin.create_add') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
              <i class="material-icons">shopping_bag</i>
              <p class="nav-link">{{ trans('admin.shipping_control') }}</p>
              <ul style="{{ activeList('shipping') }}">
                <li class="{{ activeLink(url('/mall-manager/shipping/shipping-overview')) }}"> <a href="{{ url('/mall-manager') }}/shipping/shipping-overview">{{ trans('admin.shipping_overview') }}</a></li>
                <li class="{{ activeLink(url('/mall-manager/shipping/shipping-orders')) }}"> <a href="{{ url('/mall-manager') }}/shipping/shipping-orders">{{ trans('admin.shipping_orders') }}</a></li>
              </ul>
          </li>

          </li>
            <li class="nav-item">
            <i class="material-icons">view_headline</i>
            <p class="nav-link">{{ trans('admin.departments_control') }}</p>
            <ul style="{{ activeList('departments') }}">
              <li class="{{ activeLink(url('/mall-manager/departments')) }}"> <a href="{{ url('/mall-manager') }}/departments">{{ trans('admin.departments') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/departments/create')) }}"> <a href="{{ url('/mall-manager') }}/departments/create">{{ trans('admin.create_department') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
       
            <i class="material-icons">invert_colors</i>
            <p class="nav-link">{{ trans('admin.colors_control') }}</p>
            <ul style="{{ activeList('colors') }}">
              <li class="{{ activeLink(url('/mall-manager/colors')) }}"> <a href="{{ url('/mall-manager') }}/colors">{{ trans('admin.colors') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/colors/create')) }}"> <a href="{{ url('/mall-manager') }}/colors/create">{{ trans('admin.create_color') }}</a></li>
            </ul>
        
          </li>

          <li class="nav-item">
          
            <i class="material-icons">crop_free</i>
            <p class="nav-link">{{ trans('admin.sizes_control') }}</p>
            <ul style="{{ activeList('sizes') }}">
              <li class="{{ activeLink(url('/mall-manager/sizes')) }}"> <a href="{{ url('/mall-manager') }}/sizes">{{ trans('admin.sizes') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/sizes/create')) }}"> <a href="{{ url('/mall-manager') }}/sizes/create">{{ trans('admin.create_size') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">exposure_plus_1</i>
            <p class="nav-link">{{ trans('admin.weights_control') }}</p>
            <ul style="{{ activeList('weights') }}">
              <li class="{{ activeLink(url('/mall-manager/weights')) }}"> <a href="{{ url('/mall-manager') }}/weights">{{ trans('admin.weights') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/weights/create')) }}"> <a href="{{ url('/mall-manager') }}/weights/create">{{ trans('admin.create_weight') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">all_inclusive</i>
            <p class="nav-link" >{{ trans('admin.trade_mark_control') }}</p>
            <ul style="{{ activeList('tradeMarks') }}">
              <li class="{{ activeLink(url('/mall-manager/tradeMarks')) }}"> <a href="{{ url('/mall-manager') }}/tradeMarks">{{ trans('admin.trade_marks') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/tradeMarks/create')) }}"> <a href="{{ url('/mall-manager') }}/tradeMarks/create">{{ trans('admin.create_trade_mark') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">meeting_room</i>
            <p class="nav-link">{{ trans('admin.manufacturers_control') }}</p>
            <ul style="{{ activeList('manufacturers') }}">
              <li class="{{ activeLink(url('/mall-manager/manufacturers')) }}"> <a href="{{ url('/mall-manager') }}/manufacturers">{{ trans('admin.manufacturers') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/manufacturers/create')) }}"> <a href="{{ url('/mall-manager') }}/manufacturers/create">{{ trans('admin.create_manufacturer') }}</a></li>
            </ul>
          </li>

          @if(hasMalls())
          <li class="nav-item">
     
            <i class="material-icons">panorama_vertical</i>
            <p class="nav-link">{{ trans('admin.products_control') }}</p>
            <ul style="{{ activeList('products') }}">
              <li class="{{ activeLink(url('/mall-manager/products')) }}"> <a href="{{ url('/mall-manager') }}/products">{{ trans('admin.products') }}</a></li>
              <li class="{{ activeLink(url('/mall-manager/products/create')) }}"> <a href="{{ url('/mall-manager') }}/products/create">{{ trans('admin.create_product') }}</a></li>
            </ul>       
          </li>
          @endif
         
          <!-- <li class="nav-item active-pro ">
                <a class="nav-link" href="./upgrade.html">
                    <i class="material-icons">unarchive</i>
                    <p>Upgrade to PRO</p>
                </a>
            </li> -->
        </ul>
      </div>
    </div>