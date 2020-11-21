@extends('mall_manager.index')

@section('content')

<?php
  $lng = !empty(old('lng')) ? old('lng') : 33.49924510522839;
  $lat = !empty(old('lat')) ? old('lat') : 36.288330078125;
?>

@push('js')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyCPWpivYowEUa2sDkifBW5wCvshOpiwMKY'></script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> -->


<script type="text/javascript" src='{{ url("/") }}/admin/dist/js/locationpicker.jquery.js'></script>

<script>
    $('#us1').locationpicker({
        location: {
            latitude: {{ $lat }},
            longitude: {{ $lng }}
        },
        radius: 300,
        markerIcon: '{{ url("/") }}/admin/dist/img/iconfinder_map-marker_299087.png',
        inputBinding: {
          latitudeInput: $('#lat'),
          longitudeInput: $('#lng'),
          //radiusInput: $('#us2-radius'),
          locationNameInput: $('#address')
        }
    });
</script>
@endpush

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.new_manu') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/mall-manager/manufacturers'), 'files' => true]) }}
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

              <input type="hidden" name="lat" id ="lat" value="">
              <input type="hidden" name="lng" id ="lng" value="">

             <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.email') }}</label>
                    {{ Form::email('email', old('email'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.contact_name') }}</label>
                    {{ Form::text('contact_name', old('contact_name'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.mobile') }}</label>
                    {{ Form::text('mobile', old('mobile'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.address') }}</label>
                    {{ Form::text('address', old('address'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                      <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.facebook') }}</label>
                    {{ Form::text('facebook', old('facebook'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                      <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.twitter') }}</label>
                    {{ Form::text('twitter', old('twitter'), ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                      <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.icon') }}</label>
                    {{ Form::file('icon', ['class'=>'form-control']) }}
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