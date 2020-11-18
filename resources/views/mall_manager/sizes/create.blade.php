@extends('mall_manager.index')

@section('content')

@push('js')
<script type="text/javascript">
  $(document).ready(function(){
    $('#jstree').jstree({
      "core" : {
        'data' : {!! load_dep(old('department_id')) !!},
        "themes" : {
          "variant" : "large"
        }
      },
      "checkbox" : {
        "keep_selected_style" : false
      },
      "plugins" : [ "wholerow" ]
    });

    $('#jstree').on("changed.jstree", function (e, data) {
      $('.parent_id').val(data.selected);
    });
    
  });
</script>
@endpush

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.new_size') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/mall-manager/sizes')]) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
                    {{ Form::text('name_ar', old('name_ar'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
                    {{ Form::text('name_en', old('name_en'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group clearfix">
                    <input type="hidden" class="parent_id" name="department_id" value="{{ Request::old('department_id') }}">
                    <div id="jstree"></div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <p>{{ trans('admin.is_public') }}</p>
                    {{ Form::select('is_public', ['yes' => trans('admin.yes'), 'no' => trans('admin.no')], old('is_public'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
      
                   
              <button type="submit" class="btn btn-primary pull-right">{{ trans('admin.create') }}</button>
              <div class="clearfix"></div>
            {{ Form::close() }}
          </div>
        </div>
      </div>
  
    </div>
  </div>

@endsection