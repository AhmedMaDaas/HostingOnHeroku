<section id="attractive-info">
  <label class="bmd-label-floating">{{ trans('admin.attractive_info') }}</label>
  <div class="row">
  @foreach($info->attrInfo as $attr)
    <div class="col-md-6 col-sm-4 col-xs-6 store">
      <i id="{{ $attr->id }}" class="fa fa-close delete-attr-info"></i>
      <img src="{{ url('storage/' . $attr->photo) }}">
    </div>
  @endforeach
  </div>

  <div class="errors alert alert-danger hidden">
    <ul>

    </ul>
  </div>

  <div class="row" style="padding-left:10.0px;">
    <div class="col-md-4">
      <div class="form-group">
        <label class="bmd-label-floating">{{ trans('admin.photo') }}</label>
        {{ Form::file('photo') }}
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group" style="{{isset($height) ? 'height:100%' : ''}}">
        <label class="bmd-label-floating">{{ trans('admin.title') }}</label>
        <input type="text" name="title" class="form-control" style="{{isset($height) ? 'height:100%' : ''}}" />
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group">
        <a href="#" class="btn btn-info add-attr-info">{{ trans('admin.add') }} <i class="fa fa-spin fa-spinner add hidden"></i></a>
      </div>
    </div>
  </div>

</section>