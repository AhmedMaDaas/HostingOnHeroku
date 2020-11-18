@extends('mall_manager.index')

@section('content')

    <div class="table">
      <h3 class="box-title" style="margin-left: 10px">{{ trans('admin.products_control') }}</h3>
      {{ Form::open(['id' => 'data_form', 'url' => url('/mall-manager/products/destroy/all'), 'method' => 'delete']) }}
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
@endpush
@endsection