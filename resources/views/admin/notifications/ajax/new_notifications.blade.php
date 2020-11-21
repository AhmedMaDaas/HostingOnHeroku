@foreach($notifications as $notification)
	<a class="dropdown-item" href="{{ url('/admin') }}/shipping/shipping-orders">{{ trans('admin.' . $notification->notification) }}
      <span style="max-width: 80px;" class=" font-size-13 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }} </span>
    </a>
@endforeach