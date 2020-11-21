@extends('admin.index')

@section('content')

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ trans('admin.edit_user') }}</h4>
            </div>
            <div class="card-body">
              {{ Form::open(['url' => url('admin/users/' . $user->id), 'method' => 'put', 'files' => true]) }}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.name') }}</label>
                      {{ Form::text('name', $user->name, ['class' => 'form-control']) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.email') }}</label>
                      {{ Form::email('email', $user->email, ['class' => 'form-control']) }}
                    </div>
                  </div>
                </div>
                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.password') }}</label>
                      {{ Form::password('password', ['class' => 'form-control']) }}
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.mobile') }}</label>
                      {{ Form::text('phone', $user->phone, ['class' => 'form-control']) }}
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                       <div class="form-group">
                      <!--<label class="bmd-label-floating">User Level </label>-->
                             <p>{{ trans('admin.level') }}</p>
                      {{ Form::select('level', ['user' => trans('admin.user'), 'mall' => trans('admin.mall_manager'), 'company' => trans('admin.company')], $user->level, ['class' => 'form-control', 'placeholder' => trans('admin.level')]) }}
                    </div>

                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.photo') }}</label>
                      {{ Form::file('photo', ['class' => 'form-control']) }}
                      @if(isset($user->photo) && Storage::has($user->photo))
                        <img src="{{ Storage::url('storage/' . $user->photo) }}" style="height: 100px; width: 100px">
                      @endif
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