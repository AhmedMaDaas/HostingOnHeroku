<div class="main-panel">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top " id="navigation-example">
    <div class="container-fluid">
    
      <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation" data-target="#navigation-example">
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <form class="navbar-form">
          <div class="input-group no-border">
            <input type="text" value="" class="form-control" placeholder="{{ trans('admin.search') }}...">
            <button type="submit" class="btn btn-default btn-round btn-just-icon">
              <i class="material-icons">search</i>
              <div class="ripple-container"></div>
            </button>
          </div>
        </form>
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link {{ $notifications->getCountNew() > 0 ? 'new-notification' : '' }}" href="#" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="material-icons">notifications</i>
              @if($notifications->getCountNew() > 0)
              <span class="notification">{{ $notifications->getCountNew() }}</span>
              @endif
              <p class="d-lg-none d-md-block">
                Some Actions
              </p>
            </a>
            <div id="notifications-menu" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              @foreach($notifications->getNotifications() as $notification)
                <a class="dropdown-item" href="{{ url('/admin') }}/shipping/shipping-orders">{{ trans('admin.' . $notification->notification) }}
                  <span style="max-width: 80px;" class=" font-size-13 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }} </span>
                </a>
              @endforeach
              @if($notifications->getCountAll() == 0)
                <a class="dropdown-item no-notifications" href="javscript:void(0)">{{ trans('admin.you_dont_have_notifications') }}</a>
              @endif
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" href="javscript:void(0)" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ trans('admin.language') }}
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{ url('admin/language/en') }}">English</a>
              <a class="dropdown-item" href="{{ url('admin/language/ar') }}">عربي</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/logout') }}">
              {{ trans('admin.logout') }}
            </a>
          </li>
        </ul>

      </div>
    </div>
  </nav>
  <!-- End Navbar -->