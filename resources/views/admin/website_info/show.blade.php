@extends('admin.index')

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.website_info') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/admin/website-info/' . $info->id), 'files' => true]) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.main_photo') }}</label>
                    {{ Form::file('main_photo', ['class'=>'form-control']) }}
                    @if(!empty($info->main_photo))
                      <img src="{{ Storage::url('storage/' . $info->main_photo) }}" style="width:50px;height:50px"/>
                    @endif
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.photo_title') }}</label>
                    {{ Form::text('photo_title', $info->photo_title, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.photo_desc') }}</label>
                    {{ Form::text('photo_desc', $info->photo_desc, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              @include('admin.website_info.attr_info_ajax', ['height' => ''])

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.web_desc') }}</label>
                    {{ Form::textarea('web_desc', $info->web_desc, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.desc_photo') }}</label>
                    {{ Form::file('desc_photo', ['class'=>'form-control']) }}
                    @if(!empty($info->desc_photo))
                      <img src="{{ Storage::url('storage/' . $info->desc_photo) }}" style="width:50px;height:50px"/>
                    @endif
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.email_page') }}</label>
                    {{ Form::text('email', $info->email, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.facebook') }}</label>
                    {{ Form::text('facebook', $info->facebook, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.twitter') }}</label>
                    {{ Form::text('twitter', $info->twitter, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.instagram') }}</label>
                    {{ Form::text('instagram', $info->instagram, ['class'=>'form-control']) }}
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

<script type="text/javascript">

  function updateData(data){
    $('#attractive-info').replaceWith(data);
    $('.add').addClass('hidden');
  }

  function showErrors(xhr){
    $('.errors').removeClass('hidden');
    var error_li = '';
    $.each(xhr.responseJSON.errors, function(k, v){
      error_li += '<li>' + v + '</li>';
    });
    $('.errors ul').html(error_li);
  }
  
  function addAttrInfo(){
    var formData = new FormData();
    formData.append('info_id', '{{ $info->id }}');
    formData.append('title', $('#attractive-info input[name="title"]').val());
    formData.append('photo', $('#attractive-info input[name="photo"]')[0].files[0]);
    formData.append('_token', '{{ csrf_token() }}');
    $.ajax({
      url: "{{ url('/admin') }}/add-attr-info",
      type: 'post',
      enctype: 'multipart/form-data',
      contentType: false,
      processData: false,
      data: formData,
      success: function(data){
        updateData(data);
      },
      error: function(xhr){
        $('.add').addClass('hidden');
        showErrors(xhr);
      }
    });
  }

  function deleteAttrInfo(id){
    var formData = new FormData();
    $.ajax({
      url: "{{ url('/admin') }}/delete-attr-info",
      type: 'post',
      data: {
        attr_id : id,
        _token : '{{ csrf_token() }}'
      },
      success: function(data){
        
      }
    });
  }

  $(document).ready(function(){

    $(document).on('click', '.add-attr-info', function(e){
      e.preventDefault();
      $('.add').removeClass('hidden');
      $('.errors').addClass('hidden');
      addAttrInfo();
    });

    $(document).on('click', '.delete-attr-info', function(e){
      $(this).parent('div').remove();
      deleteAttrInfo($(this).attr('id'));
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