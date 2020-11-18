<header class="main-header " id="header">
  <nav class="navbar navbar-static-top navbar-expand-lg">
    <!-- Sidebar toggle button -->
    <button id="sidebar-toggler" class="sidebar-toggle">
    </button>
    <!-- search form -->
    <div class="search-form d-none d-lg-inline-block">
      <div class="input-group">
        <button type="button" name="search" id="search-btn" class="btn btn-flat">
          <i class="mdi mdi-magnify"></i>
        </button>
        <input type="text" name="query" id="search-input" class="form-control" placeholder="{{ trans('admin.search') }}"
          autofocus autocomplete="off" />
      </div>
      <div id="search-results-container">
        <ul id="search-results"></ul>
      </div>
    </div>

    <div class="navbar-right ">
      <ul class="nav navbar-nav">
        <!-- Github Link Button -->
        <div class="btn-group mb-1 lang">
            <button type="button" class="btn btn-primary">{{ trans('admin.language') }}</button>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
            
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{ url('shipping/language/en') }}">{{ trans('admin.english') }}</a>
              <a class="dropdown-item" href="{{ url('shipping/language/ar') }}">{{ trans('admin.arabic') }}</a>
            </div>
        </div>

        <li class="dropdown notifications-menu">
          <button class="notifications-button dropdown-toggle {{ $notifications->getCountNew() < 1 ? 'no-after' : ''}}" data-toggle="dropdown">
            <i class="mdi mdi-bell-outline"></i>
          </button>
          <ul id="notifications-menu" class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-header">{{ trans('admin.you_have') }} <span class="count"> {{ $notifications->getCountAll()}} </span> {{ trans('admin.notifications') }}</li>
            <div class="others"></div>
            @foreach($notifications->getNotifications() as $notification)
              <li>
                <a href="{{ url('/') }}/shipping/shipping-orders">
                  <i class="mdi mdi-account-plus"></i> {{ trans('admin.' . $notification->notification) }}
                  <span style="max-width: 80px;" class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }} </span>
                </a>
              </li>
            @endforeach
          </ul>
        </li>
        <!-- User Account -->
        <li class="dropdown user-menu">
          <button href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
            <img src="{{ url('shipping_design') }}/assets/img/user/user.png" class="user-image" alt="User Image" />
            <span class="d-none d-lg-inline-block">{{ auth()->guard('web')->user()->name }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-right">
            <!-- User image -->
            <li class="dropdown-header">
              <img src="{{ url('shipping_design') }}/assets/img/user/user.png" class="img-circle" alt="User Image" />
              <div class="d-inline-block">
                {{ auth()->guard('web')->user()->name }} <small class="pt-1">{{ auth()->guard('web')->user()->email }}</small>
              </div>
            </li>

            <li class="dropdown-footer">
              <a href="{{ url('shipping/logout') }}"> <i class="mdi mdi-logout"></i> {{ trans('admin.logout') }} </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>