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
            <ul style="{{ count(request()->segments()) == 2 ? activeList('home') . activeList('settings') . activeList('website-info') : '' }}">
              <li class="{{ activeLink(url('admin/home')) }}"> <a href="{{ url('admin/home') }}">{{ trans('admin.dashboard') }}</a></li>
              <li class="{{ activeLink(url('admin/settings')) }}"> <a href="{{ url('admin/settings') }}">{{ trans('admin.settings') }}</a></li>
              <li class="{{ activeLink(url('admin/website-info')) }}"> <a href="{{ url('admin/website-info') }}">{{ trans('admin.website_info') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">card_giftcard</i>
            <p class="nav-link">{{ trans('admin.adds_control') }}</p>
              <ul style="{{ activeList('adds') }}">
                  <li class="{{ activeLink(url('/admin/adds')) }}"> <a href="{{ url('/admin') }}/adds">{{ trans('admin.adds') }}</a></li>
                  <li class="{{ activeLink(url('/admin/adds/create')) }}"> <a href="{{ url('/admin') }}/adds/create">{{ trans('admin.create_add') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">admin_panel_settings</i>
            <p class="nav-link">{{ trans('admin.admins_control') }}</p>
              <ul style="{{ activeList('admins') }}">
                  <li class="{{ activeLink(url('/admin/admins')) }}"> <a href="{{ url('/admin') }}/admins">{{ trans('admin.admins_accounts') }}</a></li>
                  <li class="{{ activeLink(url('/admin/admins/create')) }}"> <a href="{{ url('/admin') }}/admins/create">{{ trans('admin.create_account') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">person</i>
            <p class="nav-link">{{ trans('admin.users_control') }}</p>
            <ul style="{{ activeList('users') }}">
              <li class="{{ activeLink(url('/admin/users')) }}"> <a href="{{ url('/admin') }}/users">{{ trans('admin.users_accounts') }}</a></li>
              <li class="{{ activeLink(url('/admin/users/create')) }}"> <a href="{{ url('/admin') }}/users/create">{{ trans('admin.create_account') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">place</i>
            <p class="nav-link">{{ trans('admin.countries_control') }}</p>
            <ul style="{{ activeList('countries') }}">
              <li class="{{ activeLink(url('/admin/countries')) }}"> <a href="{{ url('/admin') }}/countries">{{ trans('admin.countries') }}</a></li>
              <li class="{{ activeLink(url('/admin/countries/create')) }}"> <a href="{{ url('/admin') }}/countries/create">{{ trans('admin.create_country') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">location_city</i>
            <p class="nav-link">{{ trans('admin.cities_control') }}</p>
            <ul style="{{ activeList('cities') }}">
              <li class="{{ activeLink(url('/admin/cities')) }}"> <a href="{{ url('/admin') }}/cities">{{ trans('admin.cities') }}</a></li>
              <li class="{{ activeLink(url('/admin/cities/create')) }}"> <a href="{{ url('/admin') }}/cities/create">{{ trans('admin.create_city') }}</a></li>
            </ul>
          </li>

          </li>
            <li class="nav-item">
            <i class="material-icons">business</i>
            <p class="nav-link">{{ trans('admin.states_control') }}</p>
            <ul style="{{ activeList('states') }}">
              <li class="{{ activeLink(url('/admin/states')) }}"> <a href="{{ url('/admin') }}/states">{{ trans('admin.states') }}</a></li>
              <li class="{{ activeLink(url('/admin/states/create')) }}"> <a href="{{ url('/admin') }}/states/create">{{ trans('admin.create_state') }}</a></li>
            </ul>
          </li>

          </li>
            <li class="nav-item">
            <i class="material-icons">view_headline</i>
            <p class="nav-link">{{ trans('admin.departments_control') }}</p>
            <ul style="{{ activeList('departments') }}">
              <li class="{{ activeLink(url('/admin/departments')) }}"> <a href="{{ url('/admin') }}/departments">{{ trans('admin.departments') }}</a></li>
              <li class="{{ activeLink(url('/admin/departments/create')) }}"> <a href="{{ url('/admin') }}/departments/create">{{ trans('admin.create_department') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">all_inclusive</i>
            <p class="nav-link" >{{ trans('admin.trade_mark_control') }}</p>
            <ul style="{{ activeList('tradeMarks') }}">
              <li class="{{ activeLink(url('/admin/tradeMarks')) }}"> <a href="{{ url('/admin') }}/tradeMarks">{{ trans('admin.trade_marks') }}</a></li>
              <li class="{{ activeLink(url('/admin/tradeMarks/create')) }}"> <a href="{{ url('/admin') }}/tradeMarks/create">{{ trans('admin.create_trade_mark') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">meeting_room</i>
            <p class="nav-link">{{ trans('admin.manufacturers_control') }}</p>
            <ul style="{{ activeList('manufacturers') }}">
              <li class="{{ activeLink(url('/admin/manufacturers')) }}"> <a href="{{ url('/admin') }}/manufacturers">{{ trans('admin.manufacturers') }}</a></li>
              <li class="{{ activeLink(url('/admin/manufacturers/create')) }}"> <a href="{{ url('/admin') }}/manufacturers/create">{{ trans('admin.create_manufacturer') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
              <i class="material-icons">shopping_bag</i>
              <p class="nav-link">{{ trans('admin.shipping_control') }}</p>
              <ul style="{{ activeList('shippings') }}{{ activeList('shipping') }}">
                <li class="{{ activeLink(url('/admin/shippings')) }}"> <a href="{{ url('/admin') }}/shippings">{{ trans('admin.shpping_companies') }}</a></li>
                <li class="{{ activeLink(url('/admin/shippings/create')) }}"> <a href="{{ url('/admin') }}/shippings/create">{{ trans('admin.create_shipping_company') }}</a></li>
                <li class="{{ activeLink(url('/admin/shipping/shipping-overview')) }}"> <a href="{{ url('/admin') }}/shipping/shipping-overview">{{ trans('admin.shipping_overview') }}</a></li>
                <li class="{{ activeLink(url('/admin/shipping/shipping-orders')) }}"> <a href="{{ url('/admin') }}/shipping/shipping-orders">{{ trans('admin.shipping_orders') }}</a></li>
              </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">local_mall</i>
            <p class="nav-link">{{ trans('admin.malls_control') }}</p>
            <ul style="{{ activeList('malls') }}">
              <li class="{{ activeLink(url('/admin/malls')) }}"> <a href="{{ url('/admin') }}/malls">{{ trans('admin.malls') }}</a></li>
              <li class="{{ activeLink(url('/admin/malls/create')) }}"> <a href="{{ url('/admin') }}/malls/create">{{ trans('admin.create_mall') }}</a></li>
              <li class="{{ activeLink(url('/admin/malls/sales')) }}"> <a href="{{ url('/admin') }}/malls/sales">{{ trans('admin.sales') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
       
            <i class="material-icons">invert_colors</i>
            <p class="nav-link">{{ trans('admin.colors_control') }}</p>
            <ul style="{{ activeList('colors') }}">
              <li class="{{ activeLink(url('/admin/colors')) }}"> <a href="{{ url('/admin') }}/colors">{{ trans('admin.colors') }}</a></li>
              <li class="{{ activeLink(url('/admin/colors/create')) }}"> <a href="{{ url('/admin') }}/colors/create">{{ trans('admin.create_color') }}</a></li>
            </ul>
        
          </li>

          <li class="nav-item">
          
            <i class="material-icons">crop_free</i>
            <p class="nav-link">{{ trans('admin.sizes_control') }}</p>
            <ul style="{{ activeList('sizes') }}">
              <li class="{{ activeLink(url('/admin/sizes')) }}"> <a href="{{ url('/admin') }}/sizes">{{ trans('admin.sizes') }}</a></li>
              <li class="{{ activeLink(url('/admin/sizes/create')) }}"> <a href="{{ url('/admin') }}/sizes/create">{{ trans('admin.create_size') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
            <i class="material-icons">exposure_plus_1</i>
            <p class="nav-link">{{ trans('admin.weights_control') }}</p>
            <ul style="{{ activeList('weights') }}">
              <li class="{{ activeLink(url('/admin/weights')) }}"> <a href="{{ url('/admin') }}/weights">{{ trans('admin.weights') }}</a></li>
              <li class="{{ activeLink(url('/admin/weights/create')) }}"> <a href="{{ url('/admin') }}/weights/create">{{ trans('admin.create_weight') }}</a></li>
            </ul>
          </li>

          <li class="nav-item">
     
            <i class="material-icons">panorama_vertical</i>
            <p class="nav-link">{{ trans('admin.products_control') }}</p>
            <ul style="{{ activeList('products') }}">
              <li class="{{ activeLink(url('/admin/products')) }}"> <a href="{{ url('/admin') }}/products">{{ trans('admin.products') }}</a></li>
              <li class="{{ activeLink(url('/admin/products/create')) }}"> <a href="{{ url('/admin') }}/products/create">{{ trans('admin.create_product') }}</a></li>
            </ul>       
          </li>
        
         
          <!-- <li class="nav-item active-pro ">
                <a class="nav-link" href="./upgrade.html">
                    <i class="material-icons">unarchive</i>
                    <p>Upgrade to PRO</p>
                </a>
            </li> -->
        </ul>
      </div>
    </div>