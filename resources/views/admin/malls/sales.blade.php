@extends('admin.index')

@section('content')

    <div class="table">
      <h3 class="box-title" style="margin-left: 10px">{{ trans('admin.sales') }}</h3>
      
      {{ $dataTable->table([
        'class' => 'dataTable table table-bordered table-hover'
      ]) }}
    </div>

@push('js')
{{ $dataTable->scripts() }}

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
    });

  });

</script>
@endpush
@endsection