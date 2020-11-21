@extends('mall_manager.index')

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
      <div class="modal-body">
        <p>{{ trans('admin.confirm_delete') }} <span class="name"></span> ?</p>
      </div>
      <div class="modal-footer">
        {{ Form::open(['url' => '', 'method' => 'delete', 'id' => 'form_delete_department']) }}
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('admin.close') }}</button>
        {{ Form::submit(trans('admin.yes'), ['class' => 'btn btn-danger']) }}
        {{ Form::close() }}
      </div>
    </div>

  </div>
</div>

@push('js')
<script type="text/javascript">
  var depId = 0;
  var allowedDepartments = getAllowedDepartments();

  function getAllowedDepartments(){
    var departments = [];
    @foreach($allowedDepartments as $key => $department)
    departments[{{$key}}] = {{$department->id}};
    @endforeach
    return departments;
  }

  function prependControl(){
    var exist = false;
    if($('.control').children().length === 0){
      $('.control').prepend('<a href="" class="btn btn-info edit"><i class="fa fa-edit"></i> {{ trans("admin.edit") }}</a>' +
                            '<a href="" class="btn btn-danger delete" data-toggle="modal" data-target="#myModal"><i class="fa fa-trash"></i> {{ trans("admin.delete") }}</a>' +
                            '<hr/>');
      $('.control').show(0);
    }
    for (var i = 0; i < allowedDepartments.length; i++) {
      if(depId == allowedDepartments[i]){
        exist = true;
      }
    };
    if(!exist){
      $('.control').html('');
    }
  }

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
      depId = data.selected;
      $('.parent_id').val(data.selected);
      prependControl();
      $('.edit').attr('href', '{{ url("mall-manager/departments") }}/' +  data.selected + '/edit');
      $('.name').text(data.instance.get_node(data.selected).text);
      $('#form_delete_department').attr('action', '{{ url("mall-manager/departments") }}/' + data.selected);
    });

  });
</script>
@endpush

    <div class="box">
      <div class="container">
        <h3 class="box-title" style="text-align: center;color: #fff;">{{ trans('admin.departments_control') }}</h3>
        <div class="control" style="display:none">
        </div>

       <div id="jstree">
        {{ Form::hidden('parent', '', ['class' => 'parent_id']) }}
       </div>
      </div>
    </div>

@endsection