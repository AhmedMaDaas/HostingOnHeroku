@extends('mall_manager.index')

@section('content')

<?php
  $lng = !empty($manufacturer->lng) ? $manufacturer->lng : 33.49924510522839;
  $lat = !empty($manufacturer->lat) ? $manufacturer->lat : 36.288330078125;
?>

@push('js')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?sensor=false&libraries=places&key='></script>
<script type="text/javascript" src='{{ url("/") }}/design/adminlte/dist/js/locationpicker.jquery.js'></script>
<script>
    $('#us1').locationpicker({
        location: {
            latitude: {{ $lat }},
            longitude: {{ $lng }}
        },
        radius: 300,
        markerIcon: '{{ url("/") }}/design/adminlte/dist/img/iconfinder_map-marker_299087.png',
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
            <h4 class="card-title">{{ trans('admin.edit_manu') }}</h4>
            <p class="card-category">Complete profile</p>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('/mall-manager/manufacturers/' . $manufacturer->id), 'files' => true, 'method' => 'put']) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_arabic') }}</label>
                    {{ Form::text('name_ar', $manufacturer->name_ar, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.name_in_english') }}</label>
                    {{ Form::text('name_en', $manufacturer->name_en, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>

              <input type="hidden" name="lat" id ="lat" value="">
              <input type="hidden" name="lng" id ="lng" value="">

             <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.email') }}</label>
                    {{ Form::email('email', $manufacturer->email, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.contact_name') }}</label>
                    {{ Form::text('contact_name', $manufacturer->contact_name, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.mobile') }}</label>
                    {{ Form::text('mobile', $manufacturer->mobile, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                    <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.address') }}</label>
                    {{ Form::text('address', $manufacturer->address, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                      <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.facebook') }}</label>
                    {{ Form::text('facebook', $manufacturer->facebook, ['class'=>'form-control']) }}
                  </div>
                </div>
              </div>
                      <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.twitter') }}</label>
                    {{ Form::text('twitter', $manufacturer->twitter, ['class'=>'form-control']) }}
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
        
              <button type="submit" class="btn btn-primary pull-right">{{ trans('admin.update') }}</button>
              <div class="clearfix"></div>
            {{ Form::close() }}
          </div>
        </div>
      </div>
  
    </div>
  </div>
  
@endsection