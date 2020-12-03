
@push('js')
<script type="text/javascript">

	$(document).ready(function(){
		$('.status').on('change', function(){
			var option = $('.status option:selected').val();
			if(option == 'refused'){
				$('.reason').removeClass('hidden');
			}else{
				$('.reason').addClass('hidden');
			}
		});
	});
</script>
@endpush

<div id="product_settings" class="tab-pane fade">
  <h3>{{ trans('admin.product_settings') }}</h3>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label class="bmd-label-floating">{{ trans('admin.price') }}</label>
        {{ Form::text('price', isset($product) && old('price') == null ? $product->price : old('price'), ['class'=>'form-control']) }}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label class="bmd-label-floating">{{ trans('admin.quantity') }}</label>
        {{ Form::text('stock', isset($product) && old('stock') == null ? $product->stock : old('stock'), ['class'=>'form-control']) }}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <label class="bmd-label-floating">{{ trans('admin.start_at') }}</label>
        {{ Form::text('start_at', isset($product) && old('start_at') == null ? $product->start_at : old('start_at'), ['class'=>'form-control datepicker']) }}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
       <label class="bmd-label-floating">{{ trans('admin.end_at') }}</label>
       {{ Form::text('end_at', isset($product) && old('end_at') == null ? $product->end_at : old('end_at'), ['class'=>'form-control datepicker']) }}
      </div>
    </div>
   </div>
   <div class="row">
    <div class="col-md-12">
      <div class="form-group">
       <label class="bmd-label-floating">{{ trans('admin.price_offer') }}</label>
       {{ Form::text('price_offer', isset($product) && old('price_offer') == null ? $product->price_offer : old('price_offer'), ['class'=>'form-control']) }}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
       <label class="bmd-label-floating">{{ trans('admin.start_offer_at') }}</label>
       {{ Form::text('offer_start_at', isset($product) && old('offer_start_at') == null ? $product->offer_start_at : old('offer_start_at'), ['class'=>'form-control datepicker']) }}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
       <label class="bmd-label-floating">{{ trans('admin.end_offer_at') }}</label>
       {{ Form::text('offer_end_at', isset($product) && old('offer_end_at') == null ? $product->offer_end_at : old('offer_end_at'), ['class'=>'form-control datepicker']) }}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <p>{{ trans('admin.status') }}</p>
        {{ Form::select('status', ['pending' => trans('admin.pending'), 'active' => trans('admin.active'), 'refused' => trans('admin.refused')], isset($product) ? $product->status : 'active', ['class'=>'form-control status']) }}
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="form-group {{ (isset($product) && ($product->status == 'refused')) ? '' : 'hidden' }} reason">
        <label class="bmd-label-floating">{{ trans('admin.refused_reason') }}</label>
        {{ Form::textarea('reason', isset($product) && old('reason') == null ? $product->reason :  old('reason'), ['class'=>'form-control']) }}
    </div>
    </div>
  </div>
</div>