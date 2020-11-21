@extends('admin.index')

@section('content')

@push('css')
<link href="{{ url('/') }}/admin_design/assets/css/select2.min.css" rel="stylesheet" />
<link href="{{ url('/') }}/admin_design/assets/css/dropzone.min.css" rel="stylesheet" />

@if(lang() == 'ar')
<style type="text/css">
  .card-header-primary .nav-tabs .nav-item .nav-link{
    font-size: 20px;
  }
</style>
@endif

@endpush

@push('js')
<script src="{{ url('/') }}/admin_design/assets/js/plugins/dropzone.min.js"></script>
<script src="{{ url('/') }}/admin_design/assets/js/plugins/select2.min.js"></script>

<script type="text/javascript">
  function getCellsCount(cell, rowOrColSpan){
    if(cell.attr(rowOrColSpan) === undefined) return 1;
    return parseInt(cell.attr(rowOrColSpan));
  }
  
  function addOtherData(){
    var rows = $('table.editable-table tr');
    for (var i = 0; i < rows.length; i++) {
      var columns = rows.eq(i).find('td');
      for (var j = 0; j < columns.length; j++) {
        var cell = columns.eq(j);
        if(cell.has('button').length)continue;
        var value = [i, j, cell.text(), getCellsCount(cell, 'rowspan'), getCellsCount(cell, 'colspan')];
        var input = '<input type="hidden" name="other_data[]" value="' + value + '">';
        $('#product_info_form').append(input);
      };
    };
  }

  $(document).ready(function() {

    var mallsData = [
      @foreach(App\Country::all() as $country)
        {
          "text": "{{ $country->{'name_' . lang()} }}",
          "children": [
            @foreach($country->malls as $mall)
              {
                "id": "{{ $mall->id }}",
                "text": "{{ $mall->{'name_' . lang()} }}",
                @if(isset($product))
                  "selected": "{{ App\MallProduct::where([['product_id', $product->id], ['mall_id', $mall->id]])->count() > 0 ? true : false }}"
                @endif
              },
            @endforeach
          ]
        },
      @endforeach
    ];

    var colorsData = [
      @foreach(App\Color::all() as $color)
        {
          "id": "{{ $color->id }}",
          "text": "{{ $color->{'name_' . lang()} }}",
          @if(isset($product))
            "selected": "{{ App\ColorProduct::where([['product_id', $product->id], ['color_id', $color->id]])->count() > 0 ? true : false }}"
          @endif
        },
      @endforeach
    ];

    $('.malls_select2').select2({data: mallsData});
    $('.sizes_select2').select2();
    $('.colors_select2').select2({data: colorsData});

    @if(isset($product))
      $(document).on('click', 'a.copy', function(){
        var url = '{{ isset($product) ? url("admin/products/copy/" . $product->id) : url("admin/products") }}';
        $.ajax({
          url: url,
          type: 'post',
          data: {_token: '{{ csrf_token() }}'},
          beforeSend: function(){
            $('.copy_loading').removeClass('hidden');
            $('.list_validation_errors').html('');
            $('.validation_errors').addClass('hidden');
          },
          success: function(data){
            $('.copy_loading').addClass('hidden');
            setTimeout(function(){
              window.location.href = '{{ url("admin/products") }}/' + data.id + "/edit";
            }, 1000);
          },
          error: function(xhr){
            $('.copy_loading').addClass('hidden');
            var error_li = '';
            $.each(xhr.responseJSON.errors, function(k, v){
              error_li += '<li>' + v + '</li>';
            });
            $('.list_validation_errors').html(error_li);
            $('.validation_errors').removeClass('hidden');
          }
        });
        return false;
      });
    @endif

    $(document).on('click', '.save_and_continue', function(){
      addOtherData();
      var form = $('#product_info_form');
      var formDate = new FormData(form[0]);
      var url = '{{ isset($product) ? url("admin/products/" . $product->id) : url("admin/products") }}';
      $.ajax({
        url: url,
        type: 'post',
        async: true,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        data: formDate,
        beforeSend: function(){
          $('.save_and_c').removeClass('hidden');
          $('.list_validation_errors').html('');
          $('.validation_errors').addClass('hidden');
        },
        success: function(data){
          $('.save_and_c').addClass('hidden');
          if(!data.status){
            var error_li = '<li>' + data.message + '</li>';
            $('.list_validation_errors').html(error_li);
            $('.validation_errors').removeClass('hidden');
          }
        },
        error: function(xhr){
          $('.save_and_c').addClass('hidden');
          var error_li = '';
          $.each(xhr.responseJSON.errors, function(k, v){
            error_li += '<li>' + v + '</li>';
          });
          $('.list_validation_errors').html(error_li);
          $('.validation_errors').removeClass('hidden');
        }
      });
      return false;
    });

    $(document).on('click', '.save', function(){
      addOtherData();
      document.getElementById('product_info_form').submit(); return false;
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

  <div class="box">
    <div class="container">

        <h3 class="box-title">{{ isset($product) ? trans('admin.edit_product') : trans('admin.new_product') }}</h3>

        {{ Form::open(['id' => 'product_info_form', 'url' => url(isset($product) ? 'admin/products/' . $product->id : 'admin/products'), 'method' => isset($product) ? 'put' : 'post', 'files' => true]) }}

        <a href="javascript:{}" class="btn btn-primary save"><i class="fa fa-floppy-o"></i>  {{ trans('admin.save') }}</a>
        @if(isset($product))
        <a href="#" class="btn btn-info copy"><i class="fa fa-copy"></i> {{ trans('admin.copy') }} <i class="fa fa-spin fa-spinner copy_loading hidden"></i></a>
        <a href="#" class="btn btn-success save_and_continue"><i class="fa fa-floppy-o"></i> {{ trans('admin.save_and_continue') }} <i class="fa fa-spin fa-spinner save_and_c hidden"></i></a>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myModal{{$product->id}}"><i class="fa fa-trash"></i> {{ trans('admin.delete') }}</button>
        @endif
        <hr/>

        <div class="alert alert-danger validation_errors hidden">
          <ul class="list_validation_errors">

          </ul>
        </div>

        <div class="card">
          <div class="card-header card-header-primary">
            <ul class="nav nav-tabs">
              <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">{{ trans('admin.product_info') }} <i class="fa fa-info"></i></a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product_department">{{ trans('admin.product_department') }} <i class="fa fa-list"></i></a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product_settings">{{ trans('admin.product_settings') }} <i class="fa fa-cog"></i></a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product_media">{{ trans('admin.product_media') }} <i class="fa fa-photo"></i></a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#shipping_info">{{ trans('admin.shipping_info') }} <i class="fa fa-info-circle"></i></a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#quantities">{{ trans('admin.quantities') }} <i class="fa fa-pie-chart"></i></a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#other_data">{{ trans('admin.other_data') }} <i class="fa fa-database"></i></a></li>
            </ul>
          </div>

          <div class="card-body tab-content">
            @include('admin.products.tabs.product_info')
            @include('admin.products.tabs.product_department')
            @include('admin.products.tabs.product_settings')
            @include('admin.products.tabs.product_media')
            @include('admin.products.tabs.shipping_info')
            @include('admin.products.tabs.quantities')
            @include('admin.products.tabs.other_data')
            
          </div>
        </div>
        {{ Form::close() }}
      </div>
  </div>

  @if(isset($product))
    <!-- Modal -->
    <div id="myModal{{$product->id}}" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">{{ trans('admin.delete') }}!</h4>
          </div>
          <div class="modal-body">
            <p>{{ trans('admin.confirm_delete') . $product->{'name_' . lang()} }} ?</p>
          </div>
          <div class="modal-footer">
            {{ Form::open(['url' => url('admin/products/' . $product->id), 'method' => 'delete']) }}
            <button type="button" class="btn btn-info" data-dismiss="modal">{{ trans('admin.close') }}</button>
            {{ Form::submit(trans('admin.yes'), ['class' => 'btn btn-danger']) }}
            {{ Form::close() }}
          </div>
        </div>

      </div>
    </div>

  @endif

@endsection