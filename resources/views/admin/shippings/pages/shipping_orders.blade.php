@extends('admin.index')

@section('content')

<div class="content">       
  <div class="row">
	  <div class="col-12"> 
      <div class="card card-table-border-none" id="recent-orders">
        <div class="card-header justify-content-between">
          <h2>{{ trans('admin.shipping_orders') }}</h2>
          <div class="fast-manage">
              <span class="jam jam-refresh"></span>
              <span class="jam jam-arrow-up"></span>
              <span class="jam jam-arrow-down"></span>
          </div>
        </div>
        <div class="card-body pt-0 pb-5">
          <table class="table card-table table-responsive table-responsive-xl orders-table" style="width:100%">
            <thead>
              <tr>
                <th class="d-none d-lg-table-cell">{{ trans('admin.id') }}</th>
                <th class="d-none d-lg-table-cell">{{ trans('admin.customer_name') }}</th>
                <th class="d-none d-lg-table-cell">{{ trans('admin.products') }}</th>
                <th class="d-none d-lg-table-cell">{{ trans('admin.address') }}</th>
                <th class="d-none d-lg-table-cell">{{ trans('admin.date') }}</th>
                <th class="d-none d-lg-table-cell">{{ trans('admin.total_coast') }}</th>
                <th class="d-none d-lg-table-cell">{{ trans('admin.shipping_coast') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($bills as $bill)
                <tr>
                  <td ><a class="text-dark order-id" id="order{{$bill->id}}" href="">{{ $bill->id }}</a></td>
                  <td >
                    <a class="text-dark customer-name" href="" id="customer{{$bill->id}}">{{ $bill->user->name }}</a>
                  </td>
                  <td class="d-none d-md-table-cell">{{ $bill->products->count() }}</td>
                  <td class="d-none d-md-table-cell">{{ $bill->address }}</td>
                  <td class="d-none d-md-table-cell">{{ $bill->created_at }}</td>
                  <td class="d-none d-md-table-cell">${{ $bill->total_coast }}</td>
                  <td class="d-none d-md-table-cell">${{ $bill->shipping_coast }}</td>
                  <td class="text-right">
                    <div class="dropdown show d-inline-block widget-dropdown">
                      <a class="dropdown-toggle icon-burger-mini" href="" role="button" id="dropdown-recent-order1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"><span class="jam jam-more-vertical"></span></a>
                      <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-recent-order1">
                        <li class="dropdown-item">
                          <a href="#" class="edit-order" id="edit-cost-modal{{$bill->id}}"><span class="jam jam-pencil-f"></span>{{ trans('admin.edit') }}</a>
                        </li>
                        <li class="dropdown-item">
                          <a class="accept-order" id="{{$bill->id}}" href="#"><span class="jam jam-check"></span>{{ trans('admin.accept') }}</a>
                        </li>
                        <li class="dropdown-item">
                          <a class="reject-order" id="{{$bill->id}}" href="#"><span class="jam jam-close"></span>{{ trans('admin.reject') }}</a>
                        </li>
                      </ul>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    @foreach($bills as $bill)
      <div class="modal edit-cost-modal" tabindex="-1" role="dialog" id="edit-cost-modal{{$bill->id}}">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content text-center">
            <div class="modal-header">
              <h5 class="modal-title">{{ trans('admin.shipping_coast') }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="mdi mdi-currency-usd"></i>
                    </span>
                  </div>
                  <input type="number" class="form-control" placeholder="" aria-label="">
                </div>
                <button class="btn btn-primary" type="submit">{{ trans('admin.save') }}</button>
              </form>     
            </div>
          </div>
        </div>
      </div>

      <div class="modal customers-modal" tabindex="-1" role="dialog" id="customer{{$bill->id}}">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content text-center">
            <div class="modal-header">
              <h5 class="modal-title">{{ $bill->user->name }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="bg-white border rounded">
                <div class="no-gutters">
                  <div class="profile-content-left pt-5 pb-3 px-3 px-xl-5">
                    <div class="card text-center widget-profile px-0 border-0">
                      @if(isset($bill->user->photo) && Storage::has($bill->user->photo))
                      <div class="card-img mx-auto rounded-circle-mid">
                        <img class="customer-img-small" src="{{ Storage::url('storage/' . $bill->user->photo) }}" alt="user image">
                      </div>
                      @endif
                      <div class="card-body">
                        <h4 class="py-2 text-dark">{{ $bill->user->name }}</h4>
                        <p>{{ $bill->user->email }}</p>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between ">
                      <div class="text-center pb-4">
                        <h6 class="text-dark pb-2">{{ $bill->user->bills->count() }}</h6>
                        <p>{{ trans('admin.total_orders') }}</p>
                      </div>
                      <div class="text-center pb-4">
                        <h6 class="text-dark pb-2">{{ $bill->user->billsPerMonth->count() }}</h6>
                        <p>{{ trans('admin.orders_this_month') }}</p>
                      </div>
                      <div class="text-center pb-4">
                        <h6 class="text-dark pb-2">{{ $bill->user->statusBills('pending')->count() }}</h6>
                        <p>{{ trans('admin.pending_orders') }}</p>
                      </div>
                      <div class="text-center pb-4">
                        <h6 class="text-dark pb-2">{{ $bill->user->statusBills('completed')->count() }}</h6>
                        <p>{{ trans('admin.completed_orders') }}</p>
                      </div>
                    </div>
                    <hr class="w-100">
                    <div class="contact-info pt-4">
                      <h5 class="text-dark mb-1">{{ trans('admin.contact_info') }}</h5>
                      <p class="text-dark font-weight-medium pt-4 mb-2">{{ trans('admin.email') }}</p>
                      <p>{{ $bill->user->email }}</p>
                      <p class="text-dark font-weight-medium pt-4 mb-2">{{ trans('admin.mobile') }}</p>
                      <p>{{ $bill->user->phone }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal order-fast-modal" tabindex="-1" role="dialog" id="order{{$bill->id}}">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content text-center">
            <div class="modal-header">
              <h5 class="modal-title">{{ $bill->id }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="invoice-wrapper rounded border bg-white py-5 px-3 px-md-4 px-lg-5">
                <div class="d-flex justify-content-between">
                  <h2 class="text-dark font-weight-medium">{{ trans('admin.order') }} {{ $bill->id }}</h2>
                  <div class="btn-group">
                    <button class="btn btn-sm btn-secondary">
                      <i class="mdi mdi-content-save"></i> {{ trans('admin.save') }}</button>
                    <button class="btn btn-sm btn-secondary">
                      <i class="mdi mdi-printer"></i> {{ trans('admin.print') }}</button>
                  </div>
                </div>
                <div class="row pt-5">
                  <div class="col-xl-6 col-lg-4">
                    <p class="text-dark mb-2">{{ trans('admin.to') }}</p>
                    <address>
                        {{ $bill->user->name }}
                    </address>
                  </div>
                  <div class="col-xl-6 col-lg-4">
                    <p class="text-dark mb-2">{{ trans('admin.from') }}</p>
                    <address>
                      {{ getBillMalls($bill->products) }}
                    </address>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                     <h6>{{ trans('admin.address') }}:</h6>  {{ $bill->address }}
                  </div>
                  <div class="col-md-3">
                     <h6>{{ trans('admin.date') }}:</h6> {{ $bill->created_at }}
                  </div>
                  <div class="col-md-3">
                     <h6>{{ trans('admin.payment') }}:</h6>  {{ trans('admin.' . $bill->payment) }}
                  </div>
                  <div class="col-md-3">
                     <h6>{{ trans('admin.status') }}:</h6> {{ trans('admin.' . $bill->status) }}
                  </div>
                </div>
                <table class="table mt-3 table-striped table-responsive table-responsive-large" style="width:100%">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>{{ trans('admin.item') }}</th>
                      <th>{{ trans('admin.mall') }}</th>
                      <th>{{ trans('admin.quantity') }}</th>
                      <th>{{ trans('admin.unit_coast') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bill->products as $productInBill)
                      <tr>
                        <td>{{ $productInBill->product->id }}</td>
                        <td>{{ $productInBill->product->{'name_' . lang()} }}</td>
                        <td>{{ $productInBill->mall->{'name_' . lang()} }}</td>
                        <td>{{ $productInBill->quantity }}</td>
                        <td>${{ $productInBill->product_coast }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="row justify-content-end">
                  <div class="col-lg-5 col-xl-4 col-xl-3 ml-sm-auto">
                    <ul class="list-unstyled mt-4">
                      <li class="mid pb-3 text-dark">{{ trans('admin.subtotal') }}
                        <span class="d-inline-block float-right text-default">${{ getSubtotal($bill->products) }}</span>
                      </li>
                      <li class="pb-3 text-dark">{{ trans('admin.discount') }}
                        <span class="d-inline-block float-right">0%</span>
                      </li>
                      <li class="pb-3 text-dark">{{ trans('admin.total') }}
                        <span class="d-inline-block float-right">${{ getSubtotal($bill->products) }}</span>
                      </li>
                    </ul>
                    <a href="#" data-dismiss="modal" id="{{ $bill->id }}" class="accept-order-btn btn mt-2 btn-lg btn-success">{{ trans('admin.accept') }}</a>
                    <a href="#" data-dismiss="modal" id="{{ $bill->id }}" class="reject-order-btn btn mt-2 btn-lg btn-danger">{{ trans('admin.reject') }}</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    
    @if($pagesNumber > 1)
      <div class="card card-default radius-pag">
        <div class="card-body">
          <nav aria-label="Page navigation example">
            <ul class="pagination pagination-flat pagination-flat-rounded">
              <li class="page-item">
                <a class="page-link" href="{{ $page > 1 ? url('admin/shipping/shipping-overview') . '?page=' . ($page - 1) : '#' }}" aria-label="Previous">
                  <span aria-hidden="true" class="mdi mdi-chevron-left"></span>
                  <span class="sr-only">{{ trans('admin.previous') }}</span>
                </a>
              </li>
              @for($i = 1; $i <= $pagesNumber; $i++)
              <li class="page-item {{ $page == $i ? 'active' : '' }}">
                <a class="page-link" href="{{ url('admin/shipping/shipping-overview') }}?page={{ $i }}">{{ $i }}</a>
              </li>
              @endfor
              <li class="page-item">
                <a class="page-link" href="{{ $page == $pagesNumber ? '#' : url('admin/shipping/shipping-overview') . '?page=' . ($page + 1) }}" aria-label="Next">
                  <span aria-hidden="true" class="mdi mdi-chevron-right"></span>
                  <span class="sr-only">{{ trans('admin.next') }}</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
      @endif
    </div>
  </div>

@push('js')
  <script type="text/javascript">
    function orderAction(id, url, type, a){
      $.ajax({
        url: url,
        type: type,
        data: {
          id: id,
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          var order = a.parent('li').parent('ul').parent('div').parent('td').parent('tr');
          order.remove();
        }
      });
    }

    $(document).ready(function(){
      $(document).on('click', '.accept-order', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var url = "{{ url('admin/shipping/accept-order') }}";
        var type = 'post';
        orderAction(id, url, type, $(this));
      });

      $(document).on('click', '.reject-order', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var url = "{{ url('admin/shipping/reject-order') }}";
        var type = 'post';
        orderAction(id, url, type, $(this));
      });

      $(document).on('click', '.accept-order-btn ', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var url = "{{ url('admin/shipping/accept-order') }}";
        var type = 'post';
        orderAction(id, url, type, $('a.accept-order#' + id));
      });

      $(document).on('click', '.reject-order-btn ', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        var url = "{{ url('admin/shipping/reject-order') }}";
        var type = 'post';
        orderAction(id, url, type, $('a#' + id + '.reject-order'));
      });

    });
  </script>

  <script>

    var lastNotId = 0;

    function setLastId(id){
      lastNotId = id;
    }

    function setNewNotifications(data){
      $('#notifications').addClass('new-notification');
      $('#notifications').next('div').prepend(data.html);
      setLastId(data.id);
      addNotificationsNumber(data.count);
    }

    function getNewNotification(){
      $.ajax({
        url: "{{ url('/') }}/admin/new-notifications",
        type: 'post',
        data:{
          id: '{{ auth()->guard("admin")->user()->id }}',
          not_id: lastNotId,
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          var audio = new Audio('{{url("/sounds")}}/piece-of-cake.mp3');
          audio.play();
          setNewNotifications(data);
        }
      });
    }

    function getNewOrder(){
      $.ajax({
        url: "{{ url('/') }}/admin/shipping/shipping-orders",
        type: 'get',
        data: {},
        success: function(data){
          $('div.content').replaceWith(data);
        }
      });
    }

    function makeNotificationsOld(){
      $.ajax({
        url: "{{ url('/') }}/admin/make-notifications-old",
        type: 'post',
        data: {
          id: '{{ auth()->guard("admin")->user()->id }}',
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          console.log(data);
        }
      });
    }

    function removeNotificationsNumber(){
      $('#notifications span').remove();
      $('#notifications').removeClass('new-notification');
    }

    function addNotificationsNumber(num){
      if($('.no-notifications').length > 0){
        $('.no-notifications').remove();
      }

      if(!$('#notifications span').length > 0){
        $('#notifications').append('<span class="notification"></span>');
      }
      else{
        oldNum = parseInt($('#notifications span').text());
        num += oldNum;
      }
      $('#notifications span').text(num);
    }

    $(document).ready(function(){

      $('#notifications').on('click', function(e){
        if($(this).hasClass('new-notification')){
          removeNotificationsNumber();
          makeNotificationsOld();
        }
        e.preventDefault();
      });

      Pusher.logToConsole = true;

      var pusher = new Pusher('9b09910381b9d11e94dd', {
        cluster: 'eu',
        forcaTLS: true
      });

      var channel = pusher.subscribe('orders-channel');
      channel.bind('new-order', function(data) {
        getNewNotification();
        getNewOrder();
      });

    });

  </script>
@endpush

@endsection