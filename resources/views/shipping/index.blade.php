@include('shipping.layouts.header')

	<div class="mobile-sticky-body-overlay"></div>

	<div class="wrapper">
		@include('shipping.layouts.menu')
		<div class="page-wrapper">
			@include('shipping.layouts.navbar')
			<div class="content-wrapper">
				@include('shipping.layouts.message')
		  		@yield('content')
			</div>
		</div>
	</div>


@include('shipping.layouts.footer')