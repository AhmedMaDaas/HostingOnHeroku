@extends('admin.index')

@section('content')

@push('js')
<script type="text/javascript">
$(document).ready(function(){
  @if(old('country_id'))
  $.ajax({
    url: '{{ url("admin/states/create") }}',
    dataType:'html',
    data:{country_id:"{{ old('country_id') }}", select:"{{ old('country_id') }}"},
    success: function(data){
      $('.city').html(data);
    }
  });
  @endif
  $('#country_id').change(function(){
    var country = $('#country_id option:selected').val();
    if(country > 0){
      $.ajax({
        url:'{{ route("states.create") }}',
        type:'get',
        dataType:'html',
        data:{country_id:country, select:''},
        success: function(data){
          $('.city').html('<div class="form-group col-md-offset-1" style="width:800px"><label>{{ trans("admin.city_id") }}</label>' + data + '</div>');
        }
      });
    }
    else{
      $('.city').html('');
    }
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

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header card-header-primary">
              <h4 class="card-title">{{ trans('admin.edit_state') }}</h4>
            </div>
            <div class="card-body">
              {{ Form::open(['url' => url('/admin/states/' . $state->id), 'files' => true, 'method' => 'put']) }}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
                      {{ Form::text('name_ar', $state->name_ar, ['class'=>'form-control']) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
                      {{ Form::text('name_en', $state->name_en, ['class'=>'form-control']) }}
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                    <p>{{ trans('admin.country') }}</p>
                      {{ Form::select('country_id', App\Country::pluck('name_' . lang(), 'id'), $state->country_id, ['class'=>'form-control', 'id' => 'country_id']) }}
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12">
                
                    <div class="form-group">
                        <span class="city">
                            <label class="bmd-label-floating">{{ trans('admin.city_id') }}</label>
                            {{ Form::select('city_id', App\City::where('country_id', $state->country_id)->pluck('name_' . lang(), 'id'), $state->city_id, ['class'=>'form-control']) }}
                        </span>
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

@endsection