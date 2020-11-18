<aside class="left-sidebar bg-sidebar">
  <div id="sidebar" class="sidebar sidebar-with-footer">
    <!-- Aplication Brand -->
    <div class="app-brand">
      <a href="{{ url('shipping/home') }}">
        <img src="{{ url('shipping_design') }}/assets/img/Bazar%20AL%20Seeb%20final%20logo.png"/>
      </a>
    </div>
    <!-- begin sidebar scrollbar -->
    <div class="sidebar-scrollbar">

      <!-- sidebar menu -->
      <ul class="nav sidebar-inner" id="sidebar-menu">
         <li  class="has-sub" >
            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#dashboard"
              aria-expanded="false" aria-controls="dashboard">
              <i class="jam jam-shopping-bag-f"></i>
              <span class="nav-text">{{ trans('admin.shipping') }}</span> <b class="caret"></b>
            </a>
            <ul  class="collapse"  id="dashboard"
              data-parent="#sidebar-menu">
              <div class="sub-menu">
                <li >
                  <a class="sidenav-item-link" href="{{url('shipping/shipping-overview')}}">
                    <span class="nav-text">{{ trans('admin.shipping_overview') }}</span>
                    
                  </a>
                </li>
                <li >
                  <a class="sidenav-item-link" href="{{url('shipping/shipping-orders')}}">
                    <span class="nav-text">{{ trans('admin.shipping_orders') }}</span>
                  </a>
                </li>
              </div>
            </ul>
          </li>
      </ul>
    </div>

    <hr class="separator" />

    <div class="sidebar-footer">
      <div class="sidebar-footer-content">
        <h6 class="text-uppercase">
          {{ trans('admin.shipping_orders_today') }} <span class="float-right">40%</span>
        </h6>
        <div class="progress progress-xs">
          <div
            class="progress-bar active"
            style="width: 40%;"
            role="progressbar"
          ></div>
        </div>
        <h6 class="text-uppercase">
           {{ trans('admin.shipping_orders_yesterday') }} <span class="float-right">65%</span>
        </h6>
        <div class="progress progress-xs">
          <div
            class="progress-bar progress-bar-warning"
            style="width: 65%;"
            role="progressbar"
          ></div>
        </div>
      </div>
    </div>
  </div>
</aside>