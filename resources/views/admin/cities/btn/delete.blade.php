<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$id}}"><i class="fa fa-trash"></i></button>

<!-- Modal -->
<div id="myModal{{$id}}" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ trans('admin.delete') }}!</h4>
      </div>
      <div class="modal-body">
        <p>{{ trans('admin.confirm_delete') . ${'name_' . lang()} }} ?</p>
      </div>
      <div class="modal-footer">
        {{ Form::open(['url' => url('admin/cities/' . $id), 'method' => 'delete']) }}
        <button type="button" class="btn btn-info" data-dismiss="modal">{{ trans('admin.close') }}</button>
        {{ Form::submit(trans('admin.yes'), ['class' => 'btn btn-danger']) }}
        {{ Form::close() }}
      </div>
    </div>

  </div>
</div>