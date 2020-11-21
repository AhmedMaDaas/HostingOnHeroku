@foreach($notifications as $notification)
	<li>
      <a href="{{ url('/') }}/shipping/shipping-orders">
        <i class="mdi mdi-account-plus"></i> {{ trans('admin.' . $notification->notification) }}
        <span style="max-width: 80px;" class=" font-size-12 d-inline-block float-right"><i class="mdi mdi-clock-outline"></i> {{ $notification->created_at->diffForHumans() }} </span>
      </a>
    </li>
@endforeach