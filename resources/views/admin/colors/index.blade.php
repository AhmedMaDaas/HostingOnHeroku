@extends('admin.index')

@section('content')

    <div class="table">
      <h3 class="box-title" style="margin-left: 10px">{{ trans('admin.colors_control') }}</h3>
      {{ Form::open(['id' => 'data_form', 'url' => url('/admin/colors/destroy/all'), 'method' => 'delete']) }}
      {{ $dataTable->table([
        'class' => 'dataTable table table-bordered table-hover'
      ]) }}
      {{ Form::close() }}
    </div>

    <!-- Modal -->
    <div id="multipleDelete" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{ trans('admin.delete') }}!</h4>
          </div>
          <div class="alert alert-danger">
            <h1 class="empty_record" hidden>{{ trans('admin.select_records_to_delete') }}</h1>
            <h1 class="not_empty" hidden>{{ trans('admin.confirm_delete') }} <span class="records-count"></span> {{ trans('admin.records') }}?</h1>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('admin.close') }}</button>
            <input type="submit" class="btn btn-danger not_empty_button delete_all" value="{{ trans('admin.yes') }}">
          </div>
        </div>

      </div>
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