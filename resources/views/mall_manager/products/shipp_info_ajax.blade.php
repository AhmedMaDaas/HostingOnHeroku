<div class="row">
  <div class="col-md-12">
    <div class="form-group">
       <p>{{ trans('admin.weights') }}</p>
      	{{ Form::select('weight_id', $weights, $productId > 0 ? \App\Product::where('id', $productId)->value('weight_id') : null, ['class' => 'form-control', 'placeholder' => 'Weight Type']) }}
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="form-group">
	    <p>{{ trans('admin.weight') }}</p>
	    {{ Form::text('product_weight', $productId > 0 ? \App\Product::where('id', $productId)->value('product_weight') : '', ['class' => 'form-control', 'placeholder' => 'Weight']) }}
    </div>
  </div>
</div>