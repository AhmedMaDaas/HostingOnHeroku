<div class="row">
  <div class="col-md-12">
    <div class="form-group">
        <label class="bmd-label-floating">{{ trans('admin.products') }}</label>
        <select class="form-control products_select2" name="products[]" multiple style="width:100%">
           @foreach($mallProducts as $mallProduct)
            <option value="{{ $mallProduct->product->id }}">{{ $mallProduct->product->{'name_' . lang()} }}</option>
          @endforeach
        </select>
    </div>
  </div>
</div>