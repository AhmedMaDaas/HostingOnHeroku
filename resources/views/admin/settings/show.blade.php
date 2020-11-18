@extends('admin.index')

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.settings') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/admin/settings'), 'files' => true]) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.site_name_in_arabic') }}</label>
                    {{ Form::text('sitename_ar', $settings->sitename_ar, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                      
                    <label class="bmd-label-floating">{{ trans('admin.site_name_in_english') }}</label>
                    {{ Form::text('sitename_en', $settings->sitename_en, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.email') }}</label>
                    {{ Form::email('email', $settings->email, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

               <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                   
                    <label class="bmd-label-floating">{{ trans('admin.site_logo') }}</label>
                    {{ Form::file('logo', ['class'=>'form-control']) }}
                    @if(!empty($settings->logo))
                      <img src="{{ Storage::url('storage/' . $settings->logo) }}" style="width:50px;height:50px"/>
                    @endif
                  
                </div>
               </div>
              </div>
                    
                <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                      
                    <label class="bmd-label-floating">{{ trans('admin.site_icon') }}</label>
                    {{ Form::file('icon', ['class'=>'form-control']) }}
                    @if(!empty($settings->icon))
                      <img src="{{ Storage::url('storage/' . $settings->icon) }}" style="width:50px;height:50px"/>
                    @endif
                          
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    
                  <div class="form-group">
                   <label class="bmd-label-floating">{{ trans('admin.site_desctiption') }}</label>
                   {{ Form::textarea('description', $settings->description, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                   <label class="bmd-label-floating">{{ trans('admin.site_key_words') }}</label>
                   {{ Form::textarea('keywords', $settings->keywords, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                 <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                      
                    <p>{{ trans('admin.site_main_language') }}</p>
                    {{ Form::select('main_lang', ['ar' => 'Arabic', 'en' => 'english'], $settings->main_lang, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                      
                    <p>{{ trans('admin.site_status') }}</p>
                    {{ Form::select('status', ['open' => trans('admin.open'), 'close' => trans('admin.closed')], $settings->status, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                   <label class="bmd-label-floating">{{ trans('admin.site_message_maintenance') }}</label>
                   {{ Form::textarea('message_maintenance', $settings->message_maintenance, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
             
      
                   
              <button type="submit" class="btn btn-primary pull-right">{{ trans('admin.save') }}</button>
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