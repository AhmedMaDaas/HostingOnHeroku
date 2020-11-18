@if(count($errors) > 0)
<div class="alert alert-danger">
	<ul>
	@foreach($errors->all() as $error)
	<li> {{ $error }} </li>
	@endforeach
	</ul>
</div>
@endif

@if(session()->has('success'))
<div class="alert alert-success">
	{{ session('success') }}
</div>
@endif

@if(session()->has('error'))
<div class="alert alert-danger">
	{{ session('error') }}
</div>
@endif