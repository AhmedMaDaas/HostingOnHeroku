@extends('mall_manager.index')

@section('content')

    <div class="table">
      <h3 class="box-title" style="margin-left: 10px">{{ trans('admin.sales') }}</h3>
      
      {{ $dataTable->table([
        'class' => 'dataTable table table-bordered table-hover'
      ]) }}
    </div>

@push('js')
{{ $dataTable->scripts() }}
@endpush
@endsection