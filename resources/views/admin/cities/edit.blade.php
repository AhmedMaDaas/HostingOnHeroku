@extends('admin.index')

@section('content')

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ trans('admin.edit_city') }}</h4>
            </div>
            <div class="card-body">
              {{ Form::open(['url' => url('/admin/cities/' . $city->id), 'files' => true, 'method' => 'put']) }}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
                      {{ Form::text('name_ar', $city->name_ar, ['class'=>'form-control']) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
                      {{ Form::text('name_en', $city->name_en, ['class'=>'form-control']) }}
                    </div>
                  </div>
                </div>
                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <p>{{ trans('admin.country') }}</p>
                      {{ Form::select('country_id', App\country::pluck('name_' . lang(), 'id'), $city->country_id, ['class'=>'form-control', 'id' => 'country_id']) }}
                    </div>
                  </div>
                </div>


                <button type="submit" class="btn btn-primary pull-right">{{ trans('admin.update') }}</button>
                <div class="clearfix"></div>
              {{ Form::close() }}
            </div>
          </div>
        </div>

      </div>
    </div>


@push('js')
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