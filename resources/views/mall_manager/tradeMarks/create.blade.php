@extends('mall_manager.index')

@section('content')

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.new_trade_mark') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/mall-manager/tradeMarks'), 'files' => true]) }}
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
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.logo') }}</label>
                    {{ Form::file('logo', ['class'=>'form-control']) }}
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