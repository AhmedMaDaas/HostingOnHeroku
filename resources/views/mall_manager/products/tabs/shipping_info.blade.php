@push('js')
  <script type="text/javascript">

    function addInput(lable, input){
      $('.quantities-inputs div.row').append('<div class="col-md-4"><div class="form-group">' + lable + input + '</div></div>');
    }

    function setInputsFields(selectors, type, quantities){
      for (var i = selectors.length - 1; i >= 0; i--) {
        var id = selectors[i].id;
        var text = selectors[i].text;
        var value = null == quantities || null == quantities[i] ? '' : quantities[i];
        var lable = '<label class="bmd-label-floating">' + text + ' {{ trans("admin.quantity") }}</label>';
        var input = '<input class="form-control" type="number" name="' + type + '[' + id + ']' + '" value="' + value + '" />';
        addInput(lable, input);
      };
    }

    function refresh(){
      $('.quantities-inputs').html('');
      $('.quantities-inputs').append('<div class="row"></div>');
    }

    function getSelected(sizesQuantities, colorsQuantities){
      refresh();
      var sizes = $('.sizes_select2').select2('data');
      var colors = $('.colors_select2').select2('data');
      setInputsFields(sizes, 'sizes_quantities', sizesQuantities);
      setInputsFields(colors, 'colors_quantities', colorsQuantities);
    }

    $(document).ready(function(){
      @if(isset($product))

        var sizesQuantities = [];
        @foreach($product->sizes as $sizeProduct)
          sizesQuantities.push('{{ $sizeProduct->quantity }}');
        @endforeach

        var colorsQuantities = [];
        @foreach($product->colors as $colorProduct)
          colorsQuantities.push('{{ $colorProduct->quantity }}');
        @endforeach

        getSelected(sizesQuantities, colorsQuantities);

      @endif

      $(document).on('change', '.sizes_select2', function(){
        'undefined' === typeof sizesQuantities && 'undefined' === typeof colorsQuantities ? getSelected() : getSelected(sizesQuantities, colorsQuantities);
      });

      $(document).on('change', '.colors_select2', function(){
        'undefined' === typeof sizesQuantities && 'undefined' === typeof colorsQuantities ? getSelected() : getSelected(sizesQuantities, colorsQuantities);
      });

    });

  </script>
@endpush

<div id="shipping_info" class="tab-pane fade">
	<h3>{{ trans('admin.shipping_info') }}</h3>

	<div class="shipp_info">
		<center><h4>{{ trans('admin.pleaze_select_department') }}</h4></center>
	</div>


    <div class="row sizes hidden">
      <div class="col-md-12">
        <div class="form-group">
            <label class="bmd-label-floating">{{ trans('admin.sizes') }}</label>
            <select class="form-control sizes_select2 sizes_select" name="sizes[]" multiple style="width:100%">
                    
            </select>
        </div>
      </div>
    </div>

    <div class="row color hidden">
      <div class="col-md-12">
        <div class="form-group">
            <label class="bmd-label-floating">{{ trans('admin.color') }}</label>
            <select class="form-control colors_select2" name="colors[]" multiple style="width:100%">

            </select>
        </div>
      </div>
    </div>

    <div class="row trademark hidden">
      <div class="col-md-12">
        <div class="form-group">
            <p>{{ trans('admin.trade_mark') }}</p>
            {{ Form::select('trade_id', App\TradeMark::pluck('name_' . lang(), 'id'), isset($product) ? $product->trade_id : old('trade_id'), ['class'=>'form-control']) }}
        </div>
      </div>
    </div>

    <div class="row manufacturer hidden">
      <div class="col-md-12">
        <div class="form-group">
                <p>{{ trans('admin.manufacturer') }}</p>
                {{ Form::select('manu_id', App\ManuFacturer::pluck('name_' . lang(), 'id'), isset($product) ? $product->trade_id : old('manu_id'), ['class'=>'form-control']) }}
        </div>
      </div>
    </div>

    <div class="row malls hidden">
      <div class="col-md-12">
        <div class="form-group">
            <label class="bmd-label-floating">{{ trans('admin.mall') }}</label>
            <select class="form-control malls_select2" name="malls[]" multiple style="width:100%">

            </select>
        </div>
      </div>
    </div>

</div>