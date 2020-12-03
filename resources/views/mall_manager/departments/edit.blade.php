@extends('mall_manager.index')

@section('content')

@push('js')
<script type="text/javascript">
  $(document).ready(function(){
    $('#jstree').jstree({
      "core" : {
        'data' : {!! load_dep($department->parent, $department->id) !!},
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
            <h4 class="card-title">{{ trans('admin.edit_department') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/mall-manager/departments/' . $department->id), 'method'=>'put', 'files' => true]) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
                    {{ Form::text('name_ar', $department->name_ar, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
                    {{ Form::text('name_en', $department->name_en, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.photo') }}</label>
                    {{ Form::file('photo', ['class'=>'form-control']) }}
                    @if(isset($department->photo) && Storage::has($department->photo))
                      <img src="{{ Storage::url('storage/' . $department->photo) }}" style="width:50px;height:50px">
                    @endif
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <input type="hidden" class="parent_id" name="parent" value="{{ Request::old('parent') }}">
                    <div id="jstree"></div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.description') }}</label>
                    {{ Form::textarea('description', $department->description, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.key_words') }}</label>
                    {{ Form::textarea('keywords', $department->keywords, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.icon') }}</label>
                    {{ Form::file('icon', ['class'=>'form-control']) }}
                    @if(isset($department->icon) && Storage::has($department->icon))
                      <img src="{{ Storage::url('storage/' . $department->icon) }}" style="width:50px;height:50px">
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

@endsection