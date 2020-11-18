@extends('mall_manager.index')

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.edit_color') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/mall-manager/colors/' . $color->id), 'method' => 'put']) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
                    {{ Form::text('name_ar', $color->name_ar, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
                    {{ Form::text('name_en', $color->name_en, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                 <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                     <p>{{ trans('admin.color') }}</p>
                     <canvas id="picker"></canvas>
                     <br>
                     <input id="color" name="color" value="{{ $color->color }}">
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

@push('js')
  <script src="{{ url('/') }}/admin_design/assets/js/html5kellycolorpicker.min.js"></script>

  <script>
    new KellyColorPicker({place : 'picker', input : 'color', size : 200});
  </script>
@endpush

@endsection