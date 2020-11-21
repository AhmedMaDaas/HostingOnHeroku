@extends('admin.index')

@section('content')

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans('admin.delete') }}!</h4>
      </div>
      <div class="alert alert-danger">
        <h1 class="not_empty">{{ trans('admin.confirm_delete') }}?</h1>
      </div>
      <div class="modal-footer">
        {{ Form::open(['url' => '', 'method' => 'delete', 'id' => 'form_delete_department']) }}
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('admin.close') }}</button>
        <input type="submit" class="btn btn-danger not_empty_button delete_all" value="{{ trans('admin.yes') }}">
        {{ Form::close() }}
      </div>
    </div>

  </div>
</div>

@push('js')
<script type="text/javascript">
  $(document).ready(function(){
    $('#jstree').jstree({
      "core" : {
        'data' : {!! load_dep() !!},
        "themes" : {
          "variant" : "large"
        }
      },
      "checkbox" : {
        "keep_selected_style" : true
      },
      "plugins" : [ "wholerow" ]//, "checkbox"
    });

    $('#jstree').on("changed.jstree", function (e, data) {
      $('.parent_id').val(data.selected);
      $('.edit').attr('href', '{{ url("admin/departments") }}/' +  data.selected + '/edit');
      $('.control').show(0);
      var name = data.instance.get_node(data.selected).text.split('<');
      $('.name').text(name[0]);
      $('#form_delete_department').attr('action', '{{ url("admin/departments") }}/' + data.selected);
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

    <div class="box">
      <div class="container">
        <h3 class="box-title" style="text-align: center;">{{ trans('admin.departments_control') }}</h3>
        <div class="control" style="display:none">
          <a href="" class="btn btn-info edit"><i class="fa fa-edit"></i> {{ trans('admin.edit') }}</a>
          <a href="" class="btn btn-danger delete" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i> {{ trans('admin.delete') }}</a>

          <hr/>
        </div>

       <div id="jstree">
        {{ Form::hidden('parent', '', ['class' => 'parent_id']) }}
       </div>
      </div>
    </div>

@endsection