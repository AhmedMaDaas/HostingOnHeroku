@include('mall_manager.layouts.header')
@include('mall_manager.layouts.menu')
@include('mall_manager.layouts.navbar')

<div class="content">
  @include('mall_manager.layouts.message')
  @yield('content')
</div>


@include('mall_manager.layouts.footer')