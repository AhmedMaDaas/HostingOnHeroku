@extends('shipping.index')

@section('content')

  <div class="content">
    @include('shipping.plugins.statistics')
    
    @include('shipping.plugins.orders_table_overview')
  </div>

@push('js')
  <script type="text/javascript">
    function deleteOrder(id, a){
      $.ajax({
        url: "{{ url('shipping/delete-order') }}",
        type: 'post',
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

    function returnOrder(id, a){
      $.ajax({
        url: "{{ url('shipping/return-order') }}",
        type: 'post',
        data: {
          id: id,
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          var order = a.parent('li').parent('ul').parent('div').parent('td').parent('tr');
          var span = order.find('span.badge');
          var spanClass = span.attr('class').split(/  +/g)[1];
          span.removeClass(spanClass);
          span.addClass('badge-warning');
          span.text('PENDING');
          a.parent('li').remove();
        }
      });
    }

    $(document).ready(function(){

      $(document).on('click', '.delete-order', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        deleteOrder(id, $(this));
      });

      $(document).on('click', '.return-order', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        returnOrder(id, $(this));
      });

    });
  </script>

  <script>

    var lastNotId = 0;

    function setLastId(id){
      lastNotId = id;
    }

    function setNewNotifications(data){
      $('.notifications-menu button').removeClass('no-after');
      $('.notifications-menu ul div.others').after(data.html);
      var count = $('.notifications-menu ul').children().length - 2;
      $('.notifications-menu ul').find('span.count').text(count);
      setLastId(data.id);
    }

    function getNewNotification(){
      $.ajax({
        url: "{{ url('/') }}/shipping/new-notifications",
        type: 'post',
        data: {
          id: '{{ auth()->guard("web")->user()->id }}',
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
        url: "{{ url('/') }}/shipping/shipping-overview",
        type: 'get',
        data: {},
        success: function(data){
          $('div.content .orders-table').replaceWith(data);
        }
      });
    }

    function makeNotificationsOld(){
      $.ajax({
        url: "{{ url('/') }}/shipping/make-notifications-old",
        type: 'post',
        data: {
          id: '{{ auth()->guard("web")->user()->id }}',
          _token: '{{ csrf_token() }}'
        },
        success: function(data){
          console.log(data);
        }
      });
    }

    $(document).ready(function(){

      $('.notifications-button').on('click', function(){
        if(!$(this).hasClass('no-after')){
          $(this).addClass('no-after');
          makeNotificationsOld();
        }
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