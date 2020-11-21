@extends('admin.index')

@section('content')

  <div class="content">
    @include('admin.shippings.plugins.statistics')
    
    @include('admin.shippings.plugins.orders_table_overview')
  </div>

@push('js')
  <script type="text/javascript">
    function deleteOrder(id, a){
      $.ajax({
        url: "{{ url('admin/shipping/delete-order') }}",
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
        url: "{{ url('admin/shipping/return-order') }}",
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
        url: "{{ url('/') }}/admin/shipping/shipping-overview",
        type: 'get',
        data: {},
        success: function(data){
          $('div.content .orders-table').replaceWith(data);
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