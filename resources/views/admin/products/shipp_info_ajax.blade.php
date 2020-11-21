<div class="row">
  <div class="col-md-12">
    <div class="form-group">
       <p>{{ trans('admin.weights') }}</p>
      	{{ Form::select('weight_id', $weights, $productId > 0 && old('weight_id') == null ? \App\Product::where('id', $productId)->value('weight_id') : old('weight_id'), ['class' => 'form-control', 'placeholder' => trans('admin.weight_type')]) }}
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
	    <p>{{ trans('admin.weight') }}</p>
	    {{ Form::text('product_weight', $productId > 0 && old('product_weight') == null ? \App\Product::where('id', $productId)->value('product_weight') : old('product_weight'), ['class' => 'form-control', 'placeholder' => trans('admin.weight')]) }}
    </div>
  </div>
</div>