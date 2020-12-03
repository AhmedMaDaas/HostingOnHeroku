@extends('admin.index')

@section('content')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
	.select2-container--default .select2-selection--single .select2-selection__rendered {
	    background-color: aliceblue;
	}
</style>
@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script type="text/javascript">

	function addInputs(){
		$('.discount').html('<div class="row">' +
				    '<div class="col-md-12">' +
				      '<div class="form-group">' +
				       '<label class="bmd-label-floating">{{ trans("admin.discount_ratio") }} % </label>' +
				       '<input type="number" name="discount" value="{{ old("discount") }}" class="form-control" />' +
				      '</div>' +
				    '</div>' +
				'</div>' +

				'<div class="row">' +
				    '<div class="col-md-12">' +
				      '<div class="form-group">' +
				       '<label class="bmd-label-floating">{{ trans("admin.products_or_departments") }}</label>' +
				       '<select name="productsDepartments" class="products-departments form-control">' +
				       		'<option value="" style="display:none;">{{ trans("admin.select_one") }}</option>' +
				       		'<option value="departments" {{ old("productsDepartments") == "departments" ? "selected" : "" }}>{{ trans("admin.departments") }}</option>' +
				       		'<option value="products" {{ old("productsDepartments") == "products" ? "selected" : "" }}>{{ trans("admin.products") }}</option>' +
				       '</select>' +
				      '</div>' +
				    '</div>' +
				'</div>');
	}

	function refresh(){
		$('.products-departments-section').html('');
		$('.get-inputs').removeClass('hidden');
		if(!$('.error').hasClass('hidden')){
			$('.error').addClass('hidden');
		}
	}

	function loaded(data){
		$('.products-departments-section').html(data);
		$('.get-inputs').addClass('hidden');
	}

	function getDepartments(departments){
		$('#jstree').jstree({
	        "core" : {
	          'data' : JSON.parse(departments),
	          "themes" : {
	            "variant" : "large"
	          }
	        },
	        "checkbox" : {
	          "keep_selected_style" : true
	        },
	        "plugins" : [ "wholerow", "checkbox"]//, "checkbox"
	    });
	}

	function getData(choose, mallId){
		$.ajax({
			url: "{{ url('/') }}/admin/adds/get-data",
			type: 'post',
			data: {
				_token: '{{ csrf_token() }}',
				choose: choose,
				mallId: mallId
			},
			success: function(data){
				loaded(data.input);
				if(data.type === 'pro'){
					$('.products_select2').select2();
				}
				else if(data.type === 'dep'){
					getDepartments(data.departments);
				}
			}
		});
	}

	function loadData(choose, mallId){
		refresh();
		getData(choose, mallId);
	}

  $(document).ready(function() {

  	@if(old('discountRadio') == 'yes')
		addInputs();
  	@endif

  	if($('input[type="radio"]:checked').val() === 'yes'){
  		addInputs();
  	}

    var mallsData = [
      @foreach(App\Country::all() as $country)
        {
          "text": "{{ $country->{'name_' . lang()} }}",
          "children": [
            @foreach($country->malls as $mall)
              {
                "id": "{{ $mall->id }}",
                "text": "{{ $mall->{'name_' . lang()} }}",
                @if(old('mall_id') !== null)
                	"selected": "{{ old('mall_id') == $mall->id ? true : false}}",
                @endif
              },
            @endforeach
          ]
        },
      @endforeach
    ];

    $('.mall_select2').select2({data: mallsData, placeholder: '{{ trans("admin.select_mall") }}'});

    @if(old('productsDepartments') !== null && old('mall_id') !== null)
		var choose = $('.products-departments').find('option:selected').val();
    	var mallId = $('.mall_select2').select2('data')[0].id;
		loadData(choose, mallId);
  	@endif

    $('input[type="radio"]').on('change', function(){
    	if($('input[name="discountRadio"]:checked').val() == 'yes'){
    		addInputs();
    	}
    	else{
    		$('.discount').html('');
    		$('.products-departments-section').html('');
    	}
    });

    $(document).on('change', '.products-departments', function(){
    	var choose = $(this).find('option:selected').val();
    	var mallId = $('.mall_select2').select2('data')[0].id;
    	if(mallId != null && mallId != ''){
    		loadData(choose, mallId);
    	}
    	else{
    		$('.error').removeClass('hidden');
    	}
    });

    $(document).on('change', '.mall_select2', function(){
    	var choose = $('.products-departments').find('option:selected').val();
    	var mallId = $(this).select2('data')[0].id;
    	if(choose != null && choose != '' && mallId != null && mallId != ''){
    		loadData(choose, mallId);
    	}
    });

    $('#submit-form').on("click", function (e) {
      	var checked_ids = $('#jstree').jstree('get_checked');
      	$('.deps-ids').html('');
      	jQuery.each(checked_ids, function(key, value){
      		$('.deps-ids').append('<input type="hidden" class="departments_ids" name="departments_ids[]" value="' + value + '" />');
      	});
    });

  });

</script>

<script>

  var lastNotId = 0;

  function setLastId(id){
    lastNotId = id;
  }

  function setNewNotifications(data){
    $('#notifications').addClass('new-notification');
    $('#notifications').next('div').prepend(data.html);
    setLastId(data.id);
    addNotificationsNumber(data.count);
  }

  function getNewNotification(){
    $.ajax({
      url: "{{ url('/') }}/admin/new-notifications",
      type: 'post',
      data:{
        id: '{{ auth()->guard("admin")->user()->id }}',
        not_id: lastNotId,
        _token: '{{ csrf_token() }}'
      },
      success: function(data){
        var audio = new Audio('{{url("/sounds")}}/piece-of-cake.mp3');
        audio.play();
        setNewNotifications(data);
      }
    });
  }

  function makeNotificationsOld(){
    $.ajax({
      url: "{{ url('/') }}/admin/make-notifications-old",
      type: 'post',
      data: {
        id: '{{ auth()->guard("admin")->user()->id }}',
        _token: '{{ csrf_token() }}'
      },
      success: function(data){
        console.log(data);
      }
    });
  }

  function removeNotificationsNumber(){
    $('#notifications span').remove();
    $('#notifications').removeClass('new-notification');
  }

  function addNotificationsNumber(num){
    if($('.no-notifications').length > 0){
      $('.no-notifications').remove();
    }

    if(!$('#notifications span').length > 0){
      $('#notifications').append('<span class="notification"></span>');
    }
    else{
      oldNum = parseInt($('#notifications span').text());
      num += oldNum;
    }
    $('#notifications span').text(num);
  }

  $(document).ready(function(){

    $('#notifications').on('click', function(e){
      if($(this).hasClass('new-notification')){
        removeNotificationsNumber();
        makeNotificationsOld();
      }
      e.preventDefault();
    });

    Pusher.logToConsole = true;

    var pusher = new Pusher('9b09910381b9d11e94dd', {
      cluster: 'eu',
      forcaTLS: true
    });

    var channel = pusher.subscribe('orders-channel');
    channel.bind('new-order', function(data) {
      getNewNotification();
    });

  });

</script>
@endpush

<div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ trans('admin.new_add') }}</h4>
          </div>
          <div class="card-body">
            {{ Form::open(['url' => url('admin/adds'), 'files' => true]) }}
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.title_ar') }}</label>
                    {{ Form::text('title_ar', old('title_ar'), ['class' => 'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.title_en') }}</label>
                    {{ Form::text('title_en', old('title_en'), ['class' => 'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.photo') }}</label>
                    {{ Form::file('photo', ['class' => 'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="bmd-label-floating">{{ trans('admin.description') }}</label>
                    {{ Form::textarea('ad', old('ad'), ['class' => 'form-control']) }}
                  </div>
                </div>
              </div>

              <div class="row">
			    <div class="col-md-12">
			      <div class="form-group">
			       <label class="bmd-label-floating">{{ trans('admin.start_at') }}</label>
			       {{ Form::text('start_at', old('start_at'), ['class'=>'form-control datepicker']) }}
			      </div>
			    </div>
			  </div>

			  <div class="row">
			    <div class="col-md-12">
			      <div class="form-group">
			       <label class="bmd-label-floating">{{ trans('admin.end_at') }}</label>
			       {{ Form::text('end_at', old('end_at'), ['class'=>'form-control datepicker']) }}
			      </div>
			    </div>
			  </div>

              <div class="row">
			      <div class="col-md-12">
			        <div class="form-group">
			            <label class="bmd-label-floating">{{ trans('admin.mall') }}</label>
			            <select class="form-control mall_select2" name="mall_id" style="width:100%">
			            	<option></option>
			            </select>
			        </div>
			      </div>
			  </div>

			  <div class="row">
			    <div class="col-md-12">
			      <div class="form-group">
			       <label class="bmd-label-floating">{{ trans('admin.discount') }}?</label>
			       {{ trans('admin.yes') }}
			       {{ Form::radio('discountRadio', 'yes', ['class'=>'form-control']) }}
			       {{ trans('admin.no') }}
			       {{ Form::radio('discountRadio', 'no', ['class'=>'form-control']) }}
			      </div>
			    </div>
			  </div>

			  <div class="discount">


			  	
			  </div>

			  <div class="loading">
			  	<i class="fa fa-spin fa-spinner get-inputs hidden"></i>
			  </div>

			  <div class="error alert alert-danger hidden">
			  	{{ trans('admin.select_mall_error') }}
			  </div>

			  <div class="products-departments-section">

			  </div>

              <button type="submit" id="submit-form" class="btn btn-primary pull-right">{{ trans('admin.create') }}</button>
              <div class="clearfix"></div>
            {{ Form::close() }}
          </div>
        </div>
      </div>

    </div>
  </div>

@endsection