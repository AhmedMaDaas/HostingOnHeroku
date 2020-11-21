@push('js')

<script type="text/javascript">
  $(document).ready(function(){
    $('#jstree').jstree({
      "core" : {
        'data' : {!! load_dep(isset($product) && old('department_id') == null ? $product->department_id : old('department_id')) !!},
        "themes" : {
          "variant" : "large"
        }
      },
      "checkbox" : {
        "keep_selected_style" : true
      },
      "plugins" : [ "wholerow" ]//, "checkbox"
    });

    $('#jstree').on("changed.jstree", function (e, data) {
      var id = data.selected[0];
      var productId = '{{ isset($product) ? $product->id : 0 }}';
      $('.parent_id').val(id);
      $.ajax({
        url: '{{ url("mall-manager/product/load/shipp-info") }}',
        type: 'post',
        data: {
          _token: '{{ csrf_token() }}',
          id: id,
          productId: productId
        },
        success: function(data){
          $('.shipp_info').html(data.weights);
          $('.sizes_select2').select2({data: data.sizes.original.sizes});
          $('.sizes_select2').select2().trigger('change');
          $('.sizes').removeClass('hidden');
          $('.color').removeClass('hidden');
          $('.trademark').removeClass('hidden');
          $('.manufacturer').removeClass('hidden');
          $('.malls').removeClass('hidden');
        }
      });
    });

  });
</script>
@endpush

<div id="product_department" class="tab-pane fade">
	<h3>{{ trans('admin.department') }}</h3>
	  <div id="jstree">
      
     </div>
     {{ Form::hidden('department_id', '', ['class' => 'parent_id']) }}
</div>