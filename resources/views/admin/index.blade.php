@include('admin.layouts.header')
@include('admin.layouts.menu')
@include('admin.layouts.navbar')

<div class="content">
  @include('admin.layouts.message')
  @yield('content')
</div>


@include('admin.layouts.footer')